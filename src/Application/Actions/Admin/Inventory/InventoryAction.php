<?php
declare(strict_types=1);
namespace App\Application\Actions\Admin\Inventory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class InventoryAction
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        try {

            return $this->twig->render($response, 'admin/inventory/list.twig', [
                'title' => 'Inventario',
                'description' => 'Lista de productos en inventario, incluyendo sus cantidades y precios.',
            ]);
        }
        catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'An error occurred while fetching inventory: ' . $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}