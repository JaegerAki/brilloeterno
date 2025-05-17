<?php

declare(strict_types=1);

namespace App\Application\Actions\Cart;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class CheckoutAction
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        $cart = $_SESSION['cart'] ?? [];

        // Here you would normally process the order, save it to the database, etc.
        // For now, just show the checkout page with cart contents.

        return $this->twig->render($response, 'cart/checkout.twig', [
            'cart' => $cart,
        ]);
    }
}