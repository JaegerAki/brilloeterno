<?php
namespace App\Infrastructure\Persistence\Cart;

use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Product\ProductRepositoryInterface;
use App\Domain\Product\Product;
use App\Domain\Cart\Cart;
use App\Domain\Cart\ValueObject\CartItem;
use App\Infrastructure\Persistence\Product\ProductRepository;
class SessionCartRepository implements CartRepositoryInterface
{
    private $pdo;
    private ProductRepositoryInterface $productRepository;
    public function __construct( $pdo)
    {
        $this->pdo = $pdo;
        $this->productRepository = new ProductRepository($pdo);
    }
    public function findCartByIdCustomerId(int $customerid = 0): Cart
    {
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $cartItems = [];
            foreach ($_SESSION['cart'] as $item) {
                if (isset($item['product_id'], $item['quantity'])) {
                    $product = $this->productRepository->findById($item['product_id']);
                    if ($product) {
                        $cartItem = new CartItem($product, (int)$item['quantity']);
                        $cartItems[] = $cartItem;
                    }
                }
            }
        } else {
            $cartItems = [];
        }
        return new Cart(0, $cartItems);
    }

    public function addItemToCart(int $customerid, int $productid, int $quantity = 1): bool
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $product = $this->productRepository->findById($productid);
        if (!$product) {
            return false; // Product not found
        }

        // Check if the product is already in the cart
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] === $productid) {
                $item['quantity'] += $quantity;
                return true; // Item quantity updated
            }
        }

        // Add new item to the cart
        $_SESSION['cart'][] = [
            'product_id' => $productid,
            'quantity' => $quantity,
        ];
        return true; // New item added
    }
    public function removeItemFromCart(int $customerid, int $productid): bool
    {
        if (!isset($_SESSION['cart'])) {
            return false; // Cart is empty
        }

        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] === $productid) {
                unset($_SESSION['cart'][$key]);
                return true; // Item removed
            }
        }
        return false; // Item not found in cart
    }
    public function increaseItemToCart(int $customerid, int $productid, int $quantity = 1): bool
    {
        if (!isset($_SESSION['cart'])) {
            return false; // Cart is empty
        }

        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] === $productid) {
                $item['quantity'] += $quantity;
                return true; // Item quantity increased
            }
        }
        return false; // Item not found in cart
    }
    public function decreaseItemFromCart(int $customerid, int $productid, int $quantity = 1): bool
    {
        if (!isset($_SESSION['cart'])) {
            return false; // Cart is empty
        }

        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] === $productid) {
                if ($item['quantity'] > $quantity) {
                    $item['quantity'] -= $quantity;
                } else {
                    $item['quantity'] = 0; // Set to zero if quantity is less than or equal to the decrease amount
                    unset($_SESSION['cart'][$item]); // Remove item if quantity is zero
                }
                return true;
            }
        }
        return false;
    }
}