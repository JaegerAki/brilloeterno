<?php
namespace App\Application\Actions\Admin\Categories;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Domain\Admin\Categories\CategoryRepositoryInterface;
use App\Domain\Admin\Categories\Category;
use App\Domain\Admin\Categories\ValueObject\CategoryDetail;

class CategoryCrudAction
{
    private Twig $twig;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(Twig $twig, CategoryRepositoryInterface $categoryRepository)
    {
        $this->twig = $twig;
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        if ($request->getMethod() === 'POST') {
            return $this->post($request, $response);
        }
        return $this->get($request, $response);
    }

    public function get(Request $request, Response $response): Response
    {
        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Categories retrieved successfully.',
            'data' => $this->categoryRepository->findAll(false),
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

        public function post(Request $request, Response $response): Response
        {
            $data = $request->getParsedBody();
            $operation = $data['operation'] ?? '';

            switch ($operation) {
                case 'read':
                    return $this->read($request, $response, $data);
                case 'create':
                    return $this->create($request, $response, $data);
                case 'update':
                    return $this->update($request, $response, $data);
                case 'delete':
                    return $this->delete($request, $response, $data);
                default:
                    $response->getBody()->write(json_encode([
                        'success' => false,
                        'message' => 'Invalid operation.'
                    ]));
                    return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
        }

        private function read(Request $request, Response $response, array $data): Response
        {
            // Implement read logic here
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Read operation not implemented.'
            ]));
            return $response->withStatus(501)->withHeader('Content-Type', 'application/json');
        }

        private function create(Request $request, Response $response, array $data): Response
        {
            $name = trim($data['name'] ?? '');
            $description = trim($data['description'] ?? '');

            if (empty($name) || empty($description)) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'message' => 'Name and description are required.'
                ]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            $category = new Category(null, new CategoryDetail(
                $name,
                $description
            ));

            $_id = $this->categoryRepository->save($category);

            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Category created successfully.',
                'category' => [
                    'id' => $_id,
                    'name' => $category->detail->name,
                    'description' => $category->detail->description,
                ]
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        }

        private function update(Request $request, Response $response, array $data): Response
        {
            // Implement update logic here
            $this->categoryRepository->save(new Category(
                $data['id'],
                new CategoryDetail(
                    $data['name'] ?? '',
                    $data['description'] ?? ''
                )
            ));
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Update operation not implemented.'
            ]));
            return $response->withStatus(501)->withHeader('Content-Type', 'application/json');
        }
}

