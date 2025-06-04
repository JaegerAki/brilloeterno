<?php
declare(strict_types=1);
namespace App\Domain\Store\ValueObject;
use App\Domain\Product\Product;
final class StoreItem
{
    private Product $product;
    private int $quantity;
    private float $discount = 0.0;

    public function __construct(Product $product, int $quantity, int $discount = 0)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->discount = $discount / 100.0; // Convert percentage to decimal
    }
    public function getProduct(): Product
    {
        return $this->product;
    }
    public function getQuantity(): int
    {
        return $this->quantity;
    }
    public function getDiscount(): float
    {
        return $this->discount;
    }
    public function getDiscountedPrice(): float
    {
        return $this->product->getPrice() * (1 - $this->discount);
    }
}