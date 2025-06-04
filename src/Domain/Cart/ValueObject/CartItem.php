<?php
declare(strict_types=1);
namespace App\Domain\Cart\ValueObject;
use App\Domain\Product\Product;
use JsonSerializable;

final class CartItem implements JsonSerializable
{
    private Product $product;
    private int $quantity;
    private float $discount = 0.0;

    public function __construct(Product $product, int $quantity, int $discount = 0)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->discount = $discount / 100.0;
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

    public function getPrice(): float
    {
        return $this->product->getPrice() * $this->quantity * (1 - $this->discount);
    }


    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'product' => $this->product,
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'price' => $this->getPrice(),
        ];
    }
}