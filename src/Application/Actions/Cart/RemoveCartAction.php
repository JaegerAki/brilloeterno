<?php
namespace App\Application\Actions\Cart;

use App\Domain\Cart\CartRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RemoveCartAction
{
    private CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        $productId = (int) $args['id'];
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }

        return $response
            ->withHeader('Location', '/cart')
            ->withStatus(302);
    }
}