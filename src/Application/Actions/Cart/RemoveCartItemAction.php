<?php
declare(strict_types=1);
namespace App\Application\Actions\Cart;
use App\Application\Service\CartManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Throwable;
class RemoveCartItemAction
{
    private CartManager $cartManager;

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        try {
            $data = $request->getParsedBody();
            $productId = (int) ($data['product_id'] ?? 0);
            $quantity = (int) ($data['quantity'] ?? 1);

            if ($productId <= 0 || $quantity <= 0) {
                throw new \InvalidArgumentException('Invalid product ID or quantity.', 400);
            }

            if (!$this->cartManager->removeItemFromCart($productId)) {
                throw new \Exception('Failed to add item to cart.', 400);
            }

            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Item removed from cart successfully.',
                'cart' => $this->cartManager->getRepository()
            ]));

            return $response;

        } catch (Throwable $e) {
            $response->getBody()->write(json_encode(value: [
                'error' => 'An error occurred while adding item to cart: ' . $e->getMessage()
            ]));
            return $response->withStatus($e->getCode())->withHeader('Content-Type', 'application/json');
        }
    }
}