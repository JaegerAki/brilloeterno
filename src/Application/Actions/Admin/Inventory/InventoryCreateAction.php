<?php
declare(strict_types=1);
namespace App\Application\Actions\Admin\Inventory;
use App\Application\Actions\Action;
use App\Domain\Admin\Inventory\InventoryRepositoryInterface;
use App\Domain\Admin\Inventory\ProductInventory;
use App\Domain\Admin\Inventory\ProductCategory;
use App\Domain\Admin\Inventory\ValueObject\ProductInventoryDetail;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Http\UploadedFile;
use Psr\Log\LoggerInterface;

class InventoryCreateAction
{

    private InventoryRepositoryInterface $inventoryRepository;
    private Twig $twig;
    public function __construct(InventoryRepositoryInterface $inventoryRepository, Twig $twig)
    {
        $this->inventoryRepository = $inventoryRepository;
        $this->twig = $twig;
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
        // Render the inventory create view
        $categories = $this->inventoryRepository->getAllCategories(); // Assuming this method retrieves all categories
        if ($categories === null) {
            $categories = [];
        }
        return $this->twig->render($response, 'admin/inventory/create.twig', [
            'title' => 'Crear producto',
            'description' => 'Crear un nuevo producto en inventario',
            'categories' => $categories,
            'action' => '/admin/inventory/create',
        ]);
    }
    private function post(Request $request, Response $response, array $args = []): Response
    {
        try {
            $data = $request->getParsedBody();
            $name = trim($data['name'] ?? '');
            $description = trim($data['description'] ?? '');
            $price = (float) ($data['price'] ?? 0);
            $stock = (int) ($data['stock'] ?? 0);
            $categoryId = (int) ($data['category_id'] ?? 0);
            
            $pictureFile = $uploadedFiles['picture'] ?? null;
            

            $uploadedFiles = $request->getUploadedFiles();
            $pictureFile = $uploadedFiles['picture'] ?? null;
            $pictureName = '';

            if ($pictureFile && $pictureFile->getError() === UPLOAD_ERR_OK) {
                $extension = pathinfo($pictureFile->getClientFilename(), PATHINFO_EXTENSION);
                $pictureName = uniqid('img_', true) . '.' . $extension;
                $path = __DIR__ . '/../../../../../public/assets/img/_tmp/' . $pictureName;
                
                $pictureFile->moveTo($path);
            }
            // Validate the form data
            if (empty($name) || empty($description) || $price <= 0 || $stock < 0 || $categoryId <= 0) {
                throw new \InvalidArgumentException('All fields are required and must be valid.');
            }

            if ($pictureName === null) {
                throw new \InvalidArgumentException('Picture is required.');
            }

            $productDetail = new ProductInventoryDetail($name, $description, $stock, $price, $pictureName);

            $productCategory = $this->inventoryRepository->getCategoryById($categoryId); // Assuming this method checks if the category exists
            $product = new ProductInventory(0, $productDetail, $productCategory);

            $this->inventoryRepository->saveProduct($product);
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