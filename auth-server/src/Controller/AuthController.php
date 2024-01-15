<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\RecordRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class Controller.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class AuthController extends AbstractController
{
    #[Route('/search/{code}', name: 'search', methods: ['GET', 'POST'])]
    public function search(string $code, RecordRepository $repository): JsonResponse
    {
        $record = $repository->findOneBy(['code' => $code]);

        return $record ?
            new JsonResponse([
                'code' => $record->getCode(),
                'name' => $record->getName(),
                'sex' => $record->getSex(),
                'year' => $record->getDiplomaYear(),
                'result' => $record->getDiplomaResult(),
            ]) :
            new JsonResponse(['message' => 'Not Found !'], 404);
    }
}
