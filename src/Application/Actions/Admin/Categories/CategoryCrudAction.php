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

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        if ($request->getMethod() === 'POST') {
            return $this->post($request, $response, $args);
        }
        return $this->get($request, $response, $args);
    }

    public function get(Request $request, Response $response, array $args = []): Response
    {
        $id = $args['id'] ?? null;
        $toSendData = null;
        if ($id !== null ) {
            $category = $this->categoryRepository->findById((int) $id);
            // Convertir el objeto Category a un array plano
            $toSendData = [
                'id' => $category->id ?? 0,
                'name' => $category->detail->name ?? '',
                'description' => $category->detail->description ?? '',
            ];
        } else {
            $toSendData = $this->categoryRepository->findAll(false);
        }
        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Consulta exitosa',
            'data' => $toSendData
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function post(Request $request, Response $response, array $args = []): Response
    {
        $data = $request->getParsedBody();
        $operation = $data['operation'] ?? '';

        switch ($operation) {
            case 'new':
                return $this->create($request, $response, $data);
            case 'edit':
                return $this->update($request, $response, $data);
            //case 'delete':
            //return $this->delete($request, $response, $data);
            default:
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'message' => 'Invalid operation.'
                ]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
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

        $this->categoryRepository->save($category);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Category created successfully.',
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function update(Request $request, Response $response, array $data): Response
    {
        // Implement update logic here
        $this->categoryRepository->update(new Category(
            $data['id'],
            new CategoryDetail(
                $data['name'] ?? '',
                $data['description'] ?? ''
            )
        ));
        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Category updated successfully.',
        ]));

        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}

