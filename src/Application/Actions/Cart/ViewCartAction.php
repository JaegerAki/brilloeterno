<?php
declare(strict_types=1);
namespace App\Application\Actions\Cart;
use App\Application\Service\CartManager;
use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Cart\Cart;
use App\Infrastructure\Persistence\Cart\SessionCartRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
class ViewCartAction
{
    private Twig $twig;
    private CartManager $cartManager;

    public function __construct(Twig $twig, CartManager $cartManager)
    {
        $this->twig = $twig;
        $this->cartManager = $cartManager;
    }
    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        $cart = $this->cartManager->getRepository();
        return $this->twig->render($response, 'cart/view.twig', [
            'cart' => $cart,
        ]);
    }
}