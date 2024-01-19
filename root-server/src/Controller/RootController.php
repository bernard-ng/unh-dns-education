<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\TLDServerRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class Controller.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class RootController extends AbstractController
{
    #[Route('/search/{code}', name: 'search', methods: ['GET', 'POST'])]
    public function search(string $code, Request $request, TLDServerRepository $repository): JsonResponse
    {
        $recursive = $request->query->getBoolean('recursive', false);
        return $recursive ? $this->recursive($code, $repository) : $this->iterative($code, $repository);
    }

    private function recursive(string $code, TLDServerRepository $repository): JsonResponse
    {
        $tld = $repository->findOneBy(['code' => substr($code, 0, 2)]);

        if ($tld) {
            try {
                $client = HttpClient::create();
                $response = $client->request('GET', "http://{$tld->getIpAddress()}:8000/search/{$code}?recursive=true");

                $data = [
                    'province' => $response->toArray(),
                ];

                return new JsonResponse($data, $response->getStatusCode());
            } catch (\Throwable $e) {
                dump($e);
                return new JsonResponse(['message' => 'Not Found !'], 404);
            }
        }

        return new JsonResponse(['message' => 'Not Found'], 404);
    }

    private function iterative(string $code, TLDServerRepository $repository): JsonResponse
    {
        $tld = $repository->findOneBy(['code' => substr($code, 0, 2)]);

        return $tld ?
            new JsonResponse([
                'code' => $tld->getCode(),
                'name' => $tld->getName(),
                'ip_address' => $tld->getIpAddress()
            ]) :
            new JsonResponse(['message' => 'Not Found !'], 404);
    }
}
