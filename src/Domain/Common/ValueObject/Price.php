<?php
declare(strict_types=1);
namespace App\Domain\Common\ValueObject;
final class Price
{
    private float $price;

    public function __construct(float $price)
    {
        if ($price < 0) {
            throw new \InvalidArgumentException("Price must be a positive number");
        }
        $this->price = $price;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function format(): string
    {
        return number_format($this->price, 2, '.', '');
    }
}