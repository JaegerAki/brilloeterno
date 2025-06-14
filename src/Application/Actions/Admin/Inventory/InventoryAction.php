<?php
declare(strict_types=1);
namespace App\Application\Actions\Admin\Inventory;
use App\Domain\Admin\Inventory\InventoryRepositoryInterface;
use App\Domain\Admin\Inventory\ProductInventory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class InventoryAction
{
    private Twig $twig;
    private InventoryRepositoryInterface $inventoryRepository;

    public function __construct(Twig $twig, InventoryRepositoryInterface $inventoryRepository)
    {
        $this->twig = $twig;
        $this->inventoryRepository = $inventoryRepository;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        try {
            $inventory = $this->inventoryRepository->getAllProducts();

            return $this->twig->render($response, 'admin/inventory/list.twig', [
                'title' => 'Inventario',
                'description' => 'Lista de productos en inventario, incluyendo sus cantidades y precios.',
                'inventory' => $inventory,
                //'inventory' => json_decode(json_encode($inventory),true),
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