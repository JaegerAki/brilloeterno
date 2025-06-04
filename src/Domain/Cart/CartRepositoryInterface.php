<?php
declare(strict_types=1);
namespace App\Domain\Cart;
use App\Domain\Product\Product;
interface CartRepositoryInterface
{
    public function findCartByIdCustomerId(int $customerid = 0): Cart;
    public function addItemToCart(int $customerid, int $productid): bool;
    public function removeItemFromCart(int $customerid, int $productid): bool;
    public function increaseItemToCart(int $customerid, int $productid, int $quantity = 1): bool;
    public function decreaseItemFromCart(int $customerid, int $productid, int $quantity = 1): bool;
}
?>