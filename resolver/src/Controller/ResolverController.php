<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\QueryRecordType;
use App\DataTransfert\QueryRecord;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ResolverController.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class ResolverController extends AbstractController
{
    private const string ROOT_IP = '192.168.1.10';

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $query = new QueryRecord();
        $form = $this
            ->createForm(QueryRecordType::class, $query)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $client = HttpClient::create();
                $watch = new Stopwatch(true);

                $response = $query->recursive ?
                    $this->recursive( $query, $client) :
                    $this->iterative($query, $client);
                $watch->start('resolution');
                dump((string) $watch->getEvent('resolution'));

                return $response;
            } catch (\Throwable $e) {
                return $this->redirectToRoute('index');
            }
        }

        return $this->render('index.html.twig', ['form' => $form]);
    }

    private function iterative(QueryRecord $query, HttpClientInterface $client): Response
    {
        try {
           $rq = $client->request('GET', "http://" . (self::ROOT_IP) . ":8000/search/{$query->code}");
           $tq = $client->request('GET', "http://{$rq->toArray()['ip_address']}:8000/search/{$query->code}");
           $aq = $client->request('GET', "http://{$tq->toArray()['ip_address']}:8000/search/{$query->code}");

           $data = [
               ...$tq->toArray(),
               ...$aq->toArray()
           ];

           return $this->render('result.html.twig', ['data' => $data]);
       } catch (\Throwable $e) {
           $this->addFlash('error', "Impossible de trouver l'enregistrement");
           return $this->redirectToRoute('index');
       }
    }

    private function recursive(QueryRecord $query, HttpClientInterface $client): Response
    {
        try {
            $rq = $client->request('GET', "http://" . (self::ROOT_IP) . ":8000/search/{$query->code}");
            $data = $rq->toArray();

            return $this->render('result.html.twig', ['data' => $data]);
        } catch (\Throwable $e) {
            $this->addFlash('error', "Impossible de trouver l'enregistrement");
            return $this->redirectToRoute('index');
        }
    }
}
