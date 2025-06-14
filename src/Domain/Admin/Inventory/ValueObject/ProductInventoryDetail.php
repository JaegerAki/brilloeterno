<?php
namespace App\Domain\Admin\Inventory\ValueObject;
class ProductInventoryDetail
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly int $stock,
        public readonly float $price,
        public readonly ?string $picture,
    ) {}
}