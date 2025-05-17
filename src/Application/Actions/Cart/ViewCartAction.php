<?php

declare(strict_types=1);

namespace App\Application\Actions\Cart;

use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Cart\Cart;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ViewCartAction
{
    private Twig $twig;
    private CartRepositoryInterface $cartRepository;

    public function __construct(Twig $twig, CartRepositoryInterface $cartRepository)
    {
        $this->twig = $twig;
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        $customerId = $_SESSION['user_id'] ?? 0;

        // If user is not logged in, you can use session cart or show empty cart
        if ($customerId) {
            $products = $this->cartRepository->findByIdCustomerId($customerId);
            $cartItems = $products;
        } else {
            $cartItems = $_SESSION['cart'] ?? [
                ['name'=>'Product 1', 'price'=>10.00, 'quantity'=>1 ,'picture' => 'public\assets\img\no_image.jpg'],
                ['name'=>'Product 2', 'price'=>10.00, 'quantity'=>1,'picture' => 'public\assets\img\no_image.jpg'],
                ['name'=>'Product 3', 'price'=>10.00, 'quantity'=>1,'picture' => 'public\assets\img\no_image.jpg'],
            ];
        }

        return $this->twig->render($response, 'cart/view.twig', [
            'cart' => $cartItems,
        ]);
    }
}