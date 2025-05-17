<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Store;

use App\Domain\Store\Store;
use App\Domain\Product\Product;
use App\Domain\Store\StoreRepositoryInterface;
use PDO;
class StoreRepository implements StoreRepositoryInterface
{
    private PDO $db;
    private array $products = [];
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    /**
     * {@inheritdoc}	
     */
    public function findAll(): array
    {
        $stmt = $this->db->query(
            'SELECT 
            idproducto as id
            ,nombre as name
            ,descripcion as description
            ,precio as price
            ,imagen as picture
            FROM producto'
        );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->products = [];
        foreach ($rows as $row) {
            $this->products[] = new Product(
                $row['id'],
                $row['name'],
                $row['description'],
                (float)$row['price'],
                $row['picture']
            );
        }
        return $this->products ?? [
            1 => new Product(1, 'Product 1', 'Description of product 1', 10.0, ''),
            2 => new Product(2, 'Product 2', 'Description of product 2', 20.0, ''),
            3 => new Product(3, 'Product 3', 'Description of product 3', 30.0, ''),
            4 => new Product(4, 'Product 4', 'Description of product 4', 40.0, ''),
            5 => new Product(5, 'Product 5', 'Description of product 5', 50.0, ''),
        ];
    }
}
?>