<?php
declare(strict_types=1);
namespace App\Application\Actions\Admin\Inventory;
use App\Application\Actions\Action;
use App\Domain\Admin\Inventory\InventoryRepositoryInterface;
use App\Domain\Admin\Inventory\ProductInventory;
use App\Domain\Admin\Inventory\ProductCategory;
use App\Domain\Admin\Inventory\ValueObject\ProductInventoryDetail;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Admin\Categories\CategoryRepositoryInterface;

class InventoryCrudAction
{
    private InventoryRepositoryInterface $inventoryRepository;
    private CategoryRepositoryInterface $categoryRepository;
    public function __construct(InventoryRepositoryInterface $inventoryRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        switch ($request->getMethod()) {
            case 'GET':
                return $this->get($request, $response, $args);
            case 'POST':
                return $this->post($request, $response, $args);
            case 'PATCH':
                return $this->patch($request, $response, $args);
            case 'DELETE':
                return $this->delete($request, $response, $args);
            default:
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'message' => 'Method not allowed.'
                ]));
                return $response->withStatus(405)->withHeader('Content-Type', 'application/json');
        }
    }
    private function get(Request $request, Response $response, array $args): Response
    {
        try {
            $id = $args['id'] ?? null;
            if ($id !== null) {
                $product = $this->inventoryRepository->get((int) $id);
                $categories = $this->categoryRepository->findAll();
                $toSendData = [
                    [
                        'key' => 'id',
                        'value' => $product->id ?? 0,
                        'type' => 'number',
                        'input' => 'hidden',
                        'validations' => [
                            'required' => true,
                            'min' => 1,
                        ],
                    ],
                    [
                        'key' => 'name',
                        'value' => $product->productInventoryDetail->name ?? '',
                        'type' => 'string',
                        'input' => 'text',
                        'validations' => [
                            'required' => true,
                            'minLength' => 2,
                            'maxLength' => 100,
                        ],
                    ],
                    [
                        'key' => 'description',
                        'value' => $product->productInventoryDetail->description ?? '',
                        'type' => 'string',
                        'input' => 'textarea',
                        'validations' => [
                            'required' => true,
                            'minLength' => 5,
                            'maxLength' => 500,
                        ],
                    ],
                    [
                        'key' => 'price',
                        'value' => $product->productInventoryDetail->price ?? 0.0,
                        'type' => 'number',
                        'input' => 'number',
                        'validations' => [
                            'required' => true,
                            'min' => 1.0,
                            'step'=> 0.1
                        ],
                    ],
                    [
                        'key' => 'stock',
                        'value' => $product->productInventoryDetail->stock ?? 0,
                        'type' => 'integer',
                        'input' => 'number',
                        'validations' => [
                            'required' => true,
                            'min' => 1,
                            'step' => 1,
                        ],
                    ],
                    [
                        'key' => 'picture',
                        'value' => $product->productInventoryDetail->picture ?? '',
                        'type' => 'string',
                        'input' => 'file',
                        'validations' => [
                            'required' => true,
                            'fileType' => ['jpg', 'jpeg', 'png'],
                            'maxSizeMB' => 5,
                        ],
                    ],
                    [
                        'key' => 'category',
                        'value' => $product->productCategory->id ?? '',
                        'type' => 'number',
                        'input' => 'select',
                        'options' => array_merge(
                            [
                                [
                                    'value' => '',
                                    'label' => 'Selecciona',
                                ]
                            ],
                            array_map(function($categories) {
                                return [
                                    'value' => $categories->id,
                                    'label' => $categories->detail->name,
                                ];
                            }, $categories)
                        ),
                        'validations' => [
                            'required' => true,
                        ],
                    ],
                ];
            } else {
                $toSendData = $this->inventoryRepository->findAll(false);
            }
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Consulta exitosa',
                'data' => $toSendData,
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'An error occurred while fetching the product: ' . $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
    private function delete(Request $request, Response $response, array $args = []): Response
    {
        try {
            $id = (int) ($args['id'] ?? 0);
            if ($id <= 0) {
                throw new \InvalidArgumentException('Invalid product ID.');
            }
            $this->inventoryRepository->delete($id);
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Eliminado correctamente.',
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'An error occurred while deleting the product: ' . $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
    private function post(Request $request, Response $response, array $args = []): Response
    {
        try {
            $data = $request->getParsedBody();
            $name = trim($data['name'] ?? '');
            $description = trim($data['description'] ?? '');
            $price = (float) ($data['price'] ?? 0);
            $stock = (int) ($data['stock'] ?? 0);
            $categoryId = (int) ($data['category'] ?? 0);

            $uploadedFiles = $request->getUploadedFiles();
            $pictureFile = $uploadedFiles['picture'] ?? null;
            $pictureName = '';

            if ($pictureFile && $pictureFile->getError() === UPLOAD_ERR_OK) {
                $extension = pathinfo($pictureFile->getClientFilename(), PATHINFO_EXTENSION);
                $pictureName = uniqid('img_', true) . '.' . $extension;
                $path = __DIR__ . '/../../../../../public/assets/img/_tmp/' . $pictureName;
                $pictureFile->moveTo($path);
            }

            if (empty($name) || empty($description) || $price <= 0 || $stock < 0 || $categoryId <= 0) {
                throw new \InvalidArgumentException('All fields are required and must be valid.');
            }

            if (empty($pictureName)) {
                throw new \InvalidArgumentException('Picture is required.');
            }

            $productDetail = new ProductInventoryDetail($name, $description, $stock, $price, $pictureName);
            $productCategory = $this->categoryRepository->get($categoryId);
            $product = new ProductInventory(0, $productDetail, new ProductCategory($categoryId, $productCategory->detail->name, $productCategory->detail->description));

            $this->inventoryRepository->insert($product);
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Creado correctamente.',
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'An error occurred while creating the product: ' . $e->getMessage(),
                '$data' => $data
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    private function patch(Request $request, Response $response, array $args = []): Response
    {
        try {
            //recibir formdata
            $data4 = $request->getProtocolVersion();
            $data3 = $request->getAttributes();
            $data1 = $request->getQueryParams();
            $data0 = $request->getBody();
            $data = $request->getParsedBody();
            $id = (int) ($data['id'] ?? 0);
            $name = trim($data['name'] ?? '');
            $description = trim($data['description'] ?? '');
            $price = (float) ($data['price'] ?? 0);
            $stock = (int) ($data['stock'] ?? 0);
            $categoryId = (int) ($data['category'] ?? 0);

            if ($id <= 0) {
                throw new \InvalidArgumentException('Invalid product ID.');
            }

            $uploadedFiles = $request->getUploadedFiles();
            $pictureFile = $uploadedFiles['picture'] ?? null;
            $pictureName = $data['existing_picture'] ?? '';

            if ($pictureFile && $pictureFile->getError() === UPLOAD_ERR_OK) {
                $extension = pathinfo($pictureFile->getClientFilename(), PATHINFO_EXTENSION);
                $pictureName = uniqid('img_', true) . '.' . $extension;
                $path = __DIR__ . '/../../../../../public/assets/img/_tmp/' . $pictureName;
                $pictureFile->moveTo($path);
            }

            // Validate the form data
            if (empty($name) || empty($description) || $price <= 0 || $stock < 0 || $categoryId <= 0) {
                throw new \InvalidArgumentException('Todos los campos son obligatorios y deben ser válidos.');
            }

            $productDetail = new ProductInventoryDetail($name, $description, $stock, $price, $pictureName);
            $productCategory = $this->categoryRepository->get($categoryId);
            $product = new ProductInventory($id, $productDetail, new ProductCategory($categoryId, $productCategory->detail->name, $productCategory->detail->description));
            $this->inventoryRepository->update($product);
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Modificado correctamente.',
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Ocurrio un erro al intentar modificar: ' . $e->getMessage(),
                'data' => $data,
                '$data0' => $data0,
                '$data3' => $data3,
                '$data1' => $data1,
                '$data4' => $data4
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}