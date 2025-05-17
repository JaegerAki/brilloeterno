<?php
declare(strict_types=1);
namespace App\Application\Actions\Store;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Product\ProductRepositoryInterface;
use Slim\Views\Twig;

class DetailStoreAction
{
    private $view;
    private ProductRepositoryInterface $productRepository;
    public function __construct(Twig $view, ProductRepositoryInterface $productRepository)
    {
        $this->view = $view;
        $this->productRepository = $productRepository;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $productId = (int)$args['id'];
        $product = $this->productRepository->findById($productId);

        if ($product) {
            return $this->view->render($response, 'store/detail.twig', [
                'product' => $product,
            ]);
        } else {
            //($response->getBody()->write('Product not found');
            return $response->withStatus(404);
        }
    }
}