<?php
declare(strict_types=1);
namespace App\Application\Actions\Admin\Categories;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Admin\Categories\CategoryRepositoryInterface;
use Slim\Views\Twig;

class CategoryReadAction
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
        // Retrieve the category ID from the route parameters
        $id = (int)$args['id'];

        // Fetch the category details from the repository
        $category = $this->categoryRepository->findById($id);

        if (!$category) {
            $response->getBody()->write('Category not found');
            return $response->withStatus(404);
        }

        // Render the category details view
        return $this->twig->render($response, 'admin/categories/read.twig', [
            'title' => 'Category Details',
            'category' => $category,
        ]);
    }
}

