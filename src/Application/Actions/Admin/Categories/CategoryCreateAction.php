<?php
namespace App\Application\Actions\Admin\Categories;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Domain\Admin\Categories\CategoryRepositoryInterface;
use App\Domain\Admin\Categories\Category;
use App\Domain\Admin\Categories\ValueObject\CategoryDetail;

class CategoryCreateAction
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
        // Handle the request and return a response
        if ($request->getMethod() === 'POST') {
            return $this->post($request, $response);
        }
        return $this->get($request, $response);
    }

    public function get(Request $request, Response $response): Response
    {
        // Render the category creation form
        return $this->twig->render($response, 'admin/categories/create.twig', [
            'title' => 'Create Categoria',
            'action' => '/admin/categories/create',
        ]);
    }
    public function post(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $name = trim($data['name'] ?? '');
        $description = trim($data['description'] ?? '');

        if (empty($name) || empty($description)) {
            $response->getBody()->write('Name and description are required.');
            return $response->withStatus(400);
        }

        $category = new Category(null, ne);

        $this->categoryRepository->save($category);

        $baseUrl = $this->twig->getEnvironment()->getGlobals()['basePath'] ?? '/brilloeterno';
        $response->getBody()->write(json_encode([
            'message' => 'Category created successfully.',
        ]));
        return $response->withHeader('Location', $baseUrl.'/admin/categories')->withStatus(302);
    }
}

