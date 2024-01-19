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
    private const string ROOT_IP = '127.0.0.1';

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $query = new QueryRecord();
        $form = $this
            ->createForm(QueryRecordType::class, $query)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->getSession()->has($query->code)) {
                return $this->redirectToRoute('result', ['code' => $query->code]);
            }

            try {
                $client = HttpClient::create();
                $watch = new Stopwatch(true);

                $watch->start('resolution');
                $data = $query->recursive ?
                    $this->recursive( $query, $client) :
                    $this->iterative($query, $client);
                $watch->stop('resolution');

                if (!empty($data)) {
                    $data['resolution_time'] = (string) $watch->getEvent('resolution');
                    $request->getSession()->set($query->code, $data);
                    return $this->redirectToRoute('result.html.twig', ['code' => $query->code]);
                }

                return $this->redirectToRoute('index');
            } catch (\Throwable $e) {
                return $this->redirectToRoute('index');
            }
        }

        return $this->render('index.html.twig', ['form' => $form]);
    }

    #[Route('/result/{code}', name: 'result')]
    public function result(string $code, Request $request): Response
    {
        if (!$request->getSession()->has($code)) {
            return $this->redirectToRoute('index');
        }

        $data = $request->getSession()->get($code);
        return $this->render('result.html.twig', ['data' => $data]);
    }

    private function iterative(QueryRecord $query, HttpClientInterface $client): array
    {
        try {
           $rq = $client->request('GET', "http://" . (self::ROOT_IP) . ":8000/search/{$query->code}");
           $tq = $client->request('GET', "http://127.0.0.1:8002/search/{$query->code}");
           $aq = $client->request('GET', "http://127.0.0.1:8003/search/{$query->code}");

           return [
               'province' => $rq->toArray(),
               'school' => $tq->toArray(),
               'student' => $aq->toArray()
           ];

       } catch (\Throwable $e) {
            dump($e);
           $this->addFlash('error', "Impossible de trouver l'enregistrement");
           return [];
       }
    }

    private function recursive(QueryRecord $query, HttpClientInterface $client): array
    {
        try {
            $rq = $client->request('GET', "http://" . (self::ROOT_IP) . ":8000/search/{$query->code}");
            return $rq->toArray();
        } catch (\Throwable $e) {
            dump($e);
            $this->addFlash('error', "Impossible de trouver l'enregistrement");
            return [];
        }
    }
}
