<?php
declare(strict_types=1);
namespace App\Domain\Common\ValueObject;
use InvalidArgumentException;
final class Stock
{
    private int $quantity;

    public function __construct(int $quantity)
    {
        if ($quantity < 0) {
            throw new InvalidArgumentException("Debes ingresar una cantidad positiva");
        }
        $this->quantity = $quantity;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function isAvailable(): bool
    {
        return $this->quantity > 0;
    }
}