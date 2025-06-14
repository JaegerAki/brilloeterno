<?php
declare(strict_types=1);
namespace App\Domain\Common\ValueObject;
final class Price
{
    public readonly float $price;
    public readonly string $currency;
    public function __construct(float $price, string $currency = 'PEN')
    {
        if ($price < 0) {
            throw new \InvalidArgumentException("Price must be a positive number");
        }
        $this->price = $price;
        $this->currency = $currency;
    }
    public function __toString(): string
    {
        return sprintf('%s %.2f', $this->currency, $this->price);
    }
}