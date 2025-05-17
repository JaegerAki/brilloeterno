<?php
// filepath: c:\xampp\htdocs\brilloeterno\src\Domain\Store\Store.php
declare(strict_types=1);

namespace App\Domain\Store;

use App\Domain\Product\Product;
use JsonSerializable;

class Store implements JsonSerializable
{
    /** @var Product[] */
    private array $products;

    public function __construct(array $products = [])
    {
        $this->products = $products;
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }
    public function jsonSerialize(): array
    {
        return [
            'products' => $this->products,
        ];
    }
}