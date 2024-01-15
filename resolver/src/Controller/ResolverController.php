<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransfert\QueryRecord;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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
        if ($request->getMethod() === 'POST') {
            $query = new QueryRecord($request->get('code'));
            $recursive = $request->query->getBoolean('recursive', false);

            $watch = new Stopwatch(true);
            $watch->start('resolution');

            try {
                $ip = self::ROOT_IP;
                $client = HttpClient::create();
                $response = $client->request('GET', "http://{$ip}:8000/search/{$query->code}?recursive={$recursive}");
                $watch->stop('resolution');

                dd($response->toArray(), (string) $watch->getEvent('resolution'));
            } catch (\Throwable $e) {
                dd($e);
            }
        }

        return $this->render('index.html.twig');
    }
}
