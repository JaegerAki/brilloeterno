<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class LogoutAction
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        //$_SESSION['flash_message'] = 'Te desconectaste. Vuelve pronto.';

        $baseUrl = $this->twig->getEnvironment()->getGlobals()['basePath'] ?? '/brilloeterno';
        // Redirect to the home
        return $response
            ->withHeader('Location', $baseUrl . '/auth/login')
            ->withStatus(302);
        // Redirect to the login page or home page
        /*return $this->twig->render($response, 'auth/login.twig', [
            'message' => 'Te desconectaste. Vuelve pronto.',
        ]);*/
    }
}