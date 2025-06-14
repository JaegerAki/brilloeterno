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


class InventoryEditAction
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
        if ($request->getMethod() === 'GET') {
            return $this->get($request, $response, $args);
        } elseif ($request->getMethod() === 'POST') {
            return $this->post($request, $response, $args);
        }
        return $response->withStatus(405)->withHeader('Allow', 'GET, POST');
    }
    private function get(Request $request, Response $response, array $args = []): Response
    {
        try {
            // Fetch product details by ID from the inventory repository
            $productId = (int) $args['id'];
            $product = $this->inventoryRepository->getProductById($productId);

            if (!$product) {
                throw new \Exception('Product not found');
            }
            $categories = $this->inventoryRepository->getAllCategories(); // Assuming this method exists to fetch categories
            if (!$categories) {
                throw new \Exception('No categories found');
            }
            // Render the inventory edit view with the fetched product
            return $this->twig->render($response, 'admin/inventory/edit.twig', [
                'title' => 'Editar producto',
                'description' => 'Editar los detalles del producto en inventario',
                'product' => $product,
                'categories' => $categories, // Pass categories to the view
                'action' => '/admin/inventory/edit/' . $productId, // The action URL for the form
            ]);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'An error occurred while fetching product details: ' . $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    private function post(Request $request, Response $response, array $args = []): Response
    {
        try {
            $data = $request->getParsedBody() ?? [];
            $productId = (int) $args['id'];
            $productInventory = $this->inventoryRepository->getProductById($productId);
            if (!$productInventory) {
                throw new \Exception('Product not found');
            }
            $uploadedFiles = $request->getUploadedFiles();
            $pictureFile = $uploadedFiles['picture'] ?? null;
            $pictureName = null;

            if ($pictureFile && $pictureFile->getError() === UPLOAD_ERR_OK) {
                $extension = pathinfo($pictureFile->getClientFilename(), PATHINFO_EXTENSION);
                $pictureName = uniqid('img_', true) . '.' . $extension;
                $path = __DIR__ . '/../../../../../public/assets/img/_tmp/' . $pictureName;
                
                $pictureFile->moveTo($path);
            }

            $category = $this->inventoryRepository->getCategoryById((int) $data['category_id']);

            $productDetail = new ProductInventoryDetail(
                $data['name'],
                $data['description'],
                (int) $data['stock'],
                (float) $data['price'],
                $pictureName
            );
            $this->inventoryRepository->saveProduct(
                new ProductInventory(
                    $productId,
                    $productDetail,
                    $category

                )
            );
            $baseUrl = $this->twig->getEnvironment()->getGlobals()['basePath'] ?? '/brilloeterno';
            $response->getBody()->write(json_encode([
                'message' => 'Product created successfully.',
            ]));
            return $response
                ->withHeader('Location', $baseUrl . '/admin/inventory')
                ->withStatus(302);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'An error occurred while updating the product: ' . $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}