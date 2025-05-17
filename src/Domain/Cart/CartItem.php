<?php
namespace App\Domain\Cart;

use App\Domain\Product\Product;

class CartItem
{
    private Product $product;
    private int $quantity;

    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function addQuantity(int $quantity = 1): void
    {
        $this->quantity += $quantity;
    }
    public function removeQuantity(int $quantity = 1): void
    {
        if ($this->quantity > $quantity) {
            $this->quantity -= $quantity;
        } else {
            $this->quantity = 0;
        }
    }
}