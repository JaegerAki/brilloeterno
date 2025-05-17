<?php
namespace App\Infrastructure\Persistence\Cart;

use App\Domain\Cart\CartRepositoryInterface;

use App\Domain\Product\Product;
use App\Domain\Cart\Cart;
class SessionCartRepository implements CartRepositoryInterface
{
    public function addItemToCart(int $customerId, int $productId, int $cantidad = 1): void
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = 0;
        }
        $_SESSION['cart'][$productId] += $cantidad;
    }
    public function removeItemFromCart(int $customerId, int $productId, int $cantidad = 1): void
    {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] -= $cantidad;
            if ($_SESSION['cart'][$productId] <= 0) {
                unset($_SESSION['cart'][$productId]);
            }
        }
    }
    public function findByIdCustomerId(int $customer): array
    {

        return [];
    }
}