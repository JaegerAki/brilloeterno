<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Store;
use App\Domain\Store\Store;
use App\Domain\Store\StoreDetail;
use App\Domain\Store\ValueObject\StoreItem;
use App\Domain\Product\Product;
use App\Domain\Store\StoreRepositoryInterface;
use PDO;
class StoreRepository implements StoreRepositoryInterface
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    public function findStore(): Store
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
        $storeItems = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new Product(
                (int) $row['id'],
                $row['name'],
                $row['description'],
                (float) $row['price'],
                $row['picture']
            );
            $storeItems[] = new StoreItem($product, 1);
        }
        return new Store($storeItems);
    }
    public function findStoreItemById(int $id): ?StoreItem
    {
        $stmt = $this->db->prepare(
            'SELECT 
            idproducto as id
            ,nombre as name
            ,descripcion as description
            ,precio as price
            ,imagen as picture
            FROM producto WHERE idproducto = :id'
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new Product(
                (int) $row['id'],
                $row['name'],
                $row['description'],
                (float) $row['price'],
                $row['picture']
            );
            return new StoreItem($product, 1);
        }
        return null;
    }
    public function findStoreItemDetailItemByProductId(int $productId): ?StoreDetail
    {
        $stmt = $this->db->prepare(
            'SELECT 
            idproducto as id
            ,nombre as name
            ,descripcion as description
            ,precio as price
            ,imagen as picture
            FROM producto WHERE idproducto = :productId'
        );
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new Product(
                (int) $row['id'],
                $row['name'],
                $row['description'],
                (float) $row['price'],
                $row['picture']
            );
            return new StoreDetail(new StoreItem($product, 1));
        }
        return null;
    }
}
?>