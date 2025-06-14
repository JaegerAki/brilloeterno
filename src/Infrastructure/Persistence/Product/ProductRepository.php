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
        return $this->products ?? [];
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