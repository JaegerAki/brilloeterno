<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Product;

use App\Domain\Product\Product;
use App\Domain\Product\ProductRepositoryInterface;
use App\Domain\Product\ProductNotFoundException;
use PDO;

class ProductRepository implements ProductRepositoryInterface
{
    private PDO $db;
    private array $products = [];
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        return $this->products ?? [
            1 => new Product(1, 'Product 1', 'Description of product 1', 10.0, ''),
            2 => new Product(2, 'Product 2', 'Description of product 2', 20.0, ''),
            3 => new Product(3, 'Product 3', 'Description of product 3', 30.0, ''),
            4 => new Product(4, 'Product 4', 'Description of product 4', 40.0, ''),
            5 => new Product(5, 'Product 5', 'Description of product 5', 50.0, ''),
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function findById(int $id): Product
    {
        if (!isset($this->products[$id])) {
            throw new ProductNotFoundException();
        }
        return $this->products[$id];
    }
}
?>