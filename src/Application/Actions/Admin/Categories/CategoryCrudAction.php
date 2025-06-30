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
        // Para PATCH, intentar parsear JSON primero
        $data = $request->getParsedBody() ?: [];
        
        // Si getParsedBody() está vacío, intentar parsear JSON del body
        if (empty($data)) {
            $body = (string) $request->getBody();
            if (!empty($body)) {
                $data = json_decode($body, true) ?: [];
            }
        }
        
        // Debug: Log de los datos recibidos
        //error_log("PATCH data received: " . print_r($data, true));
        
        // Validar que los datos requeridos estén presentes
        if (empty($data['id']) || empty($data['name']) || empty($data['description'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'ID, name and description are required for update.',
                //'debug' => $data // Agregar debug info
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

