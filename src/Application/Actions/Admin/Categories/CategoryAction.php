<?php
declare(strict_types=1);
namespace App\Application\Actions\Admin\Categories;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Admin\Categories\CategoryRepositoryInterface;
use Slim\Views\Twig;

class CategoryAction{
    private Twig $twig;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(Twig $twig, CategoryRepositoryInterface $categoryRepository)
    {
        $this->twig = $twig;
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        //$categories = $this->categoryRepository->findAll();
        return $this->twig->render($response, 'admin/categories/list.twig', [
            'title' => 'Categorias',
            'model' => ['id','nombre','descripcion'],
        ]);
    }
}