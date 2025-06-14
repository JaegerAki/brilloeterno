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

    public function getAllProducts(): array
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
            //que sea iterables para twig
            $products[] = $product;
        }
        return $products;
    }
    

    public function getProductById(int $id): ?ProductInventory
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

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
        return null;
    }

    public function saveProduct(ProductInventory $product): void
    {
        $fields = ['idproducto', 'nombre', 'descripcion', 'precio', 'stock', 'idcategoria'];
        $params = [':id', ':name', ':description', ':price', ':stock', ':category_id'];
        $updates = [
            'nombre = :name',
            'descripcion = :description',
            'precio = :price',
            'stock = :stock',
            'idcategoria = :category_id'
        ];

        $picture = $product->productInventoryDetail->picture;
        if ($picture !== null) {
            $fields[] = 'imagen';
            $params[] = ':picture';
            $updates[] = 'imagen = :picture';
        }

        $sql = sprintf(
            'INSERT INTO producto (%s) VALUES (%s)
            ON DUPLICATE KEY UPDATE %s',
            implode(', ', $fields),
            implode(', ', $params),
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
            throw new \Exception('Error saving product: ' . implode(', ', $stmt->errorInfo()));
        }
    }

    public function deleteProduct(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM producto WHERE idproducto = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            throw new \Exception('Product not found or could not be deleted.');
        }
    }

    public function getAllCategories(): array
    {
        $stmt = $this->db->query('SELECT idcategoria AS id, nombre AS name, descripcion AS description FROM categoria');
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new ProductCategory(
                (int) $row['id'],
                $row['name'],
                $row['description'] ?? ''
            );
        }
        return $categories;
    }

    public function getCategoryById(int $id): ?ProductCategory
    {
        $stmt = $this->db->prepare('SELECT idcategoria AS id, nombre AS name, descripcion AS description FROM categoria WHERE idcategoria = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new ProductCategory(
                (int) $row['id'],
                $row['name'],
                $row['description'] ?? ''
            );
        }
        return null;
    }

    public function saveCategory(ProductCategory $category): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO categoria (idcategoria, nombre, descripcion)
            VALUES (:id, :name, :description)
            ON DUPLICATE KEY UPDATE 
                nombre = :name,
                descripcion = :description'
        );
        $stmt->bindValue(':id', $category->id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $category->name);
        $stmt->bindValue(':description', $category->description);
        if (!$stmt->execute()) {
            throw new \Exception('Error saving category: ' . implode(', ', $stmt->errorInfo()));
        }
    }

    public function deleteCategory(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM categoria WHERE idcategoria = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            throw new \Exception('Category not found or could not be deleted.');
        }
    }
}