<?php
declare(strict_types=1);
namespace App\Application\Actions\Admin\Inventory;
use App\Domain\Admin\Inventory\ProductInventory;
use App\Domain\Admin\Inventory\ValueObject\ProductInventoryDetail;
use App\Domain\Admin\Inventory\ProductCategory;
use App\Domain\Admin\Inventory\InventoryRepositoryInterface;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class InventoryDeleteAction
{
    private InventoryRepositoryInterface $inventoryRepository;
    private Twig $twig; 

    public function __construct(Twig $twig,InventoryRepositoryInterface $inventoryRepository)
    {
        $this->twig = $twig;
        $this->inventoryRepository = $inventoryRepository;
    }
    
    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        if ($request->getMethod() === 'GET') {
            return $this->get($request, $response, $args);
        } elseif ($request->getMethod() === 'POST') {
            return $this->post($request, $response, $args);
        }
        return $response->withStatus(405)->withHeader('Allow', 'GET, POST');
    }
    public function get(Request $request, Response $response, array $args = []): Response
    {
        $id = (int) $args['id'];
        $product = $this->inventoryRepository->getProductById($id);
        if ($product === null) {
            $response->getBody()->write('Product not found');
            return $response->withStatus(404);
        }
        
        $baseUrl = $this->twig->getEnvironment()->getGlobals()['basePath'] ?? '/brilloeterno';

        // Render the delete confirmation page
        return $this->twig->render($response, 'admin/inventory/delete.twig', [
            'product' => $product,
            'action' => '/admin/inventory/delete/'.$id,
        ]);
    }
    public function post(Request $request, Response $response, array $args = []): Response
    {
        $id = (int) $args['id'];
        $product = $this->inventoryRepository->getProductById($id);
        if ($product === null) {
            $response->getBody()->write('Product not found');
            return $response->withStatus(404);
        }

        $baseUrl = $this->twig->getEnvironment()->getGlobals()['basePath'] ?? '/brilloeterno';
        $this->inventoryRepository->deleteProduct($id);
        
        $response->getBody()->write('Product deleted successfully');
        return $response->withHeader('Location', $baseUrl.'/admin/inventory')->withStatus(302);
    }
}