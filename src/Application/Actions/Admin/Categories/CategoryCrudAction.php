<?php
namespace App\Application\Actions\Admin\Categories;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Domain\Admin\Categories\CategoryRepositoryInterface;
use App\Domain\Admin\Categories\Category;
use App\Domain\Admin\Categories\ValueObject\CategoryDetail;
use PhpParser\Node\Stmt\Switch_;

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
        switch ($request->getMethod()) {
            case 'GET':
                return $this->get($request, $response, $args);
            case 'POST':
                return $this->post($request, $response, $args);
            case 'PATCH':
                return $this->patch($request, $response, $args);
            default:
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'message' => 'Method not allowed.'
                ]));
                return $response->withStatus(405)->withHeader('Content-Type', 'application/json');
        }
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'] ?? null;
        $data = $request->getParsedBody();
        $toSendData = null;
        if ($id !== null) {
            $category = $this->categoryRepository->get((int) $id);
            // Convertir el objeto Category a un array plano
            $toSendData = [
                [
                    'key' => 'name',
                    'value' => $category->detail->name ?? '',
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
                    'value' => $category->detail->description ?? '',
                    'type' => 'string',
                    'input' => 'textarea',
                    'validations' => [
                        'required' => true,
                        'minLength' => 2,
                        'maxLength' => 255,
                    ],
                ],
                [
                    'key' => 'id',
                    'value' => $category->id ?? 0,
                    'type' => 'integer',
                    'input' => 'hidden',
                    'validations' => [
                        'required' => true,
                    ],
                ],
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

    private function post(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
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

        $this->categoryRepository->insert($category);

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Categoria creado exitosamente.',
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function patch(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody() ?: [];

        if (empty($data)) {
            $body = (string) $request->getBody();
            if (!empty($body)) {
                $data = json_decode($body, true) ?: [];
            }
        }

        if (empty($data['id']) || empty($data['name']) || empty($data['description'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'ID, name and description are required for update.',
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $this->categoryRepository->update(new Category(
            (int) $data['id'],
            new CategoryDetail(
                trim($data['name']),
                trim($data['description'])
            )
        ));

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Categoria actualizado exitosamente.',
        ]));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}

