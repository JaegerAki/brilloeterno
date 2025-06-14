<?php
declare(strict_types=1);
namespace App\Application\Actions\Admin\Inventory;
use App\Domain\Admin\Inventory\InventoryRepositoryInterface;
use App\Domain\Admin\Inventory\ProductInventory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class InventoryReadAction
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
        // Fetch product details by ID from the inventory repository
        try {
            $productId = (int) $args['id'];
            $product = $this->inventoryRepository->getProductById($productId);

            if (!$product) {
                throw new \Exception('Product not found');
            }

            // Render the inventory detail view with the fetched product
            return $this->twig->render($response, 'admin/inventory/read.twig', [
                'title' => 'Detalle del producto',
                'description' => 'Detalles del producto en inventario',
                'product' => $product,
            ]);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'An error occurred while fetching product details: ' . $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}