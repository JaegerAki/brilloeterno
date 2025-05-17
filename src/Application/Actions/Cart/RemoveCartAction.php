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
        $customerId = (int)$args['customerid'];
        $productId = (int)$args['productid'];

        // Remove the item from the cart
        $this->cartRepository->removeItemFromCart($customerId, $productId);

        // Return a response
        $response->getBody()->write(json_encode(['message' => 'Item removed from cart successfully.']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}