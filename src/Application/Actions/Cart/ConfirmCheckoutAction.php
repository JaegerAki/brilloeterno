<?php

declare(strict_types=1);

namespace App\Application\Actions\Cart;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ConfirmCheckoutAction
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        $cart = $_SESSION['cart'] ?? [];

        // Here you would normally save the order to the database, send emails, etc.
        // For now, just clear the cart and show a confirmation page.

        $_SESSION['cart'] = [];

        return $this->twig->render($response, 'cart/confirmation.twig', [
            'message' => 'Thank you for your purchase!',
        ]);
    }
}