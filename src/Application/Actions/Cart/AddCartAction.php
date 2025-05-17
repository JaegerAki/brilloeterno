<?php
namespace App\Application\Actions\Cart;

use App\Domain\Cart\CartRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddCartAction
{
    private CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {

    }
}
?>