<?php
declare(strict_types=1);
namespace App\Application\Actions\Cart;
use App\Application\Service\CartManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Throwable;
class DecreaseCartItemAction
{
    private CartManager $cartManager;
    public function __construct(CartManager $cartManager)
    {
        $this->cartManager = $cartManager;
    }
    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        try {
            $data = $request->getParsedBody();
            $productId = (int) ($data['product_id'] ?? 0);
            $quantity = (int) ($data['quantity'] ?? 1);

            if ($productId <= 0 || $quantity <= 0) {
                throw new \InvalidArgumentException('Invalid product ID or quantity.', 400);
            }

            if (!$this->cartManager->decreaseItemFromCart($productId, $quantity)) {
                throw new \Exception('Failed to add item to cart.', 400);
            }

            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Item added to cart successfully.',
                'cart' => $this->cartManager->getRepository() // Assuming Cart has a toArray method
            ]));
            return $response;
        
        } catch (Throwable $e) {
            $response->getBody()->write(json_encode( [
                'error' => 'An error occurred while adding item to cart: ' . $e->getMessage()
            ]));
            return $response
            ->withStatus(400)
            ->withHeader('Content-Type', 'application/json');
        }
    }
}