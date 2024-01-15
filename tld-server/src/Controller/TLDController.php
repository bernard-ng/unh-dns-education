<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AuthServerRepository;
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
final class TLDController extends AbstractController
{
    #[Route('/search/{code}', name: 'search', methods: ['GET', 'POST'])]
    public function search(string $code, Request $request, AuthServerRepository $repository): JsonResponse
    {
        $recursive = $request->query->getBoolean('recursive', false);
        return $recursive ? $this->recursive($code, $repository) : $this->iterative($code, $repository);
    }

    public function iterative(string $code, AuthServerRepository $repository): JsonResponse
    {
        $auth = $repository->findOneBy(['code' => substr($code, 0, 10)]);

        return $auth ?
            new JsonResponse([
                'code' => $auth->getCode(),
                'name' => $auth->getName(),
                'ip_address' => $auth->getIpAddress(),
            ]) :
            new JsonResponse(['message' => 'Not Found'], 404);
    }

    public function recursive(string $code, AuthServerRepository $repository): JsonResponse
    {
        $auth = $repository->findOneBy(['code' => substr($code, 0, 10)]);

        if ($auth) {
            try {
                $client = HttpClient::create();
                $response = $client->request('GET', "http://{$auth->getIpAddress()}:8000/search/{$code}?recursive=true");
                return new JsonResponse($response->toArray(), $response->getStatusCode());
            } catch (\Throwable $th) {
                return new JsonResponse(['message' => 'Not Found'], 404);
            }
        }

        return new JsonResponse(['message' => 'Not Found'], 404);
    }
}
