<?php
declare(strict_types=1);
namespace App\Application\Actions\About;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class AboutAction
{
    private Twig $twig;
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }
    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        // Aquí puedes agregar la lógica para manejar la acción de "Acerca"
        // Por ejemplo, cargar una vista o devolver un JSON con información

        return $this->twig->render($response, 'about/about.twig', ['title' => 'Acerca de nosotros']);
    }
}