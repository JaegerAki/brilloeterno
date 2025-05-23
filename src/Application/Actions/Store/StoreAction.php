<?php

declare(strict_types=1);

namespace App\Application\Actions\Store;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Store\StoreRepositoryInterface;
use Slim\Views\Twig;

class StoreAction
{
    private Twig $twig;
    private StoreRepositoryInterface $storeRepository;

    public function __construct(Twig $twig, StoreRepositoryInterface $storeRepository)
    {
        $this->twig = $twig;
        $this->storeRepository = $storeRepository;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        $products = $this->storeRepository->findAll();
        return $this->twig->render($response, 'store/list.twig', [
            'products' => $products,
        ]);
    }
}
