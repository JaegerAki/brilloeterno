<?php

declare(strict_types=1);

namespace App\Application\Actions\Home;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class HomeAction
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        return $this->twig->render($response, 'home.twig');
    }
}