<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Admin\Inventory;
use App\Domain\Admin\Inventory\ProductInventory;
use App\Domain\Admin\Inventory\ValueObject\ProductInventoryDetail;
use App\Domain\Admin\Inventory\InventoryRepositoryInterface;
use App\Domain\Admin\Inventory\ProductCategory;
use PDO;
class InventoryRepository implements InventoryRepositoryInterface
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(bool $isObject = true): array
    {
        $stmt = $this->db->query(
            'SELECT 
            p.idproducto AS id,
            p.nombre AS name,
            p.descripcion AS description,
            p.precio AS price,
            p.stock AS stock,
            p.imagen AS picture,
            c.idcategoria AS category_id,
            c.nombre AS category_name,
            c.descripcion AS category_description
            FROM producto p
            LEFT JOIN categoria c ON p.idcategoria = c.idcategoria'
        );
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($isObject) {
                $category = new ProductCategory(
                    (int) $row['category_id'],
                    $row['category_name'],
                    $row['category_description'] ?? ''
                );
                $productDetail = new ProductInventoryDetail(
                    $row['name'],
                    $row['description'],
                    (int) $row['stock'],
                    (float) $row['price'],
                    $row['picture']
                );
                $product = new ProductInventory(
                    (int) $row['id'],
                    $productDetail,
                    $category
                );
                $products[] = $product;
            } else {
                $products[] = [
                    'id' => (int) $row['id'],
                    'picture' => $row['picture'],
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'price' => (float) $row['price'],
                    'stock' => (int) $row['stock'],
                    'category' => $row['category_name'],
                ];
            }
        }
        return $products;
    }


    public function get(int $id): ?ProductInventory
    {
        $stmt = $this->db->prepare(
            'SELECT 
                p.idproducto AS id,
                p.nombre AS name,
                p.descripcion AS description,
                p.precio AS price,
                p.stock AS stock,
                p.imagen AS picture,
                c.idcategoria AS category_id,
                c.nombre AS category_name,
                c.descripcion AS category_description
            FROM producto p
            LEFT JOIN categoria c ON p.idcategoria = c.idcategoria
            WHERE p.idproducto = :id'
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row) {
            return null; // No product found
        }   
        $category = new ProductCategory(
            (int) $row['category_id'],
            $row['category_name'],
            $row['category_description'] ?? ''
        );
        return new ProductInventory(
            (int) $row['id'],
            new ProductInventoryDetail(
                $row['name'],
                $row['description'],
                (int) $row['stock'],
                (float) $row['price'],
                $row['picture']
            ),
            $category
        );
        
    }

    public function insert(ProductInventory $product): int
    {
        $fields = ['idproducto', 'nombre', 'descripcion', 'precio', 'stock', 'idcategoria'];
        $params = [':id', ':name', ':description', ':price', ':stock', ':category_id'];

        $picture = $product->productInventoryDetail->picture;
        if ($picture !== null) {
            $fields[] = 'imagen';
            $params[] = ':picture';
        }

        $sql = sprintf(
            'INSERT INTO producto (%s) VALUES (%s)',
            implode(', ', $fields),
            implode(', ', $params)
        );

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $product->id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $product->productInventoryDetail->name);
        $stmt->bindValue(':description', $product->productInventoryDetail->description);
        $stmt->bindValue(':price', $product->productInventoryDetail->price);
        $stmt->bindValue(':stock', $product->productInventoryDetail->stock);
        $stmt->bindValue(':category_id', $product->productCategory->id, PDO::PARAM_INT);
        if ($picture !== null) {
            $stmt->bindValue(':picture', $picture);
        }
        if (!$stmt->execute()) {
            throw new \Exception('Error inserting product: ' . implode(', ', $stmt->errorInfo()));
        }
        return (int) $this->db->lastInsertId();
    }

    public function update(ProductInventory $product): int
    {
        $updates = [
            'nombre = :name',
            'descripcion = :description',
            'precio = :price',
            'stock = :stock',
            'idcategoria = :category_id'
        ];

        $picture = $product->productInventoryDetail->picture;
        if ($picture !== null || $picture !== '') {
            $updates[] = 'imagen = :picture';
        }

        $sql = sprintf(
            'UPDATE producto SET %s WHERE idproducto = :id',
            implode(', ', $updates)
        );

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $product->id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $product->productInventoryDetail->name);
        $stmt->bindValue(':description', $product->productInventoryDetail->description);
        $stmt->bindValue(':price', $product->productInventoryDetail->price);
        $stmt->bindValue(':stock', $product->productInventoryDetail->stock);
        $stmt->bindValue(':category_id', $product->productCategory->id, PDO::PARAM_INT);
        if ($picture !== null) {
            $stmt->bindValue(':picture', $picture);
        }
        if (!$stmt->execute()) {
            throw new \Exception('Error al intentar modificar: ' . implode(', ', $stmt->errorInfo()));
        }
        if ($stmt->rowCount() === 0) {
            throw new \Exception('El producto no ha sido modificado o no existe.');
        }
        return $stmt->rowCount();
    }

    public function delete(int $id): int
    {
        $stmt = $this->db->prepare('DELETE FROM producto WHERE idproducto = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            throw new \Exception('Product not found or could not be deleted.');
        }
        return $stmt->rowCount();
    }
}