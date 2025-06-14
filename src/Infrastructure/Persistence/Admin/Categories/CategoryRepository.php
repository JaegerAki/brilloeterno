<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Admin\Categories;
use App\Domain\Admin\Categories\CategoryRepositoryInterface;
use App\Domain\Admin\Categories\Category;
use App\Domain\Admin\Categories\ValueObject\CategoryDetail;
use PDO;

class CategoryRepository implements CategoryRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?Category
    {
        $stmt = $this->db->prepare(
            'SELECT 
                c.idcategoria AS id,
                c.nombre AS name,
                c.descripcion AS description
            FROM categoria c
            WHERE c.idcategoria = :id'
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $categoryDetail = new CategoryDetail(
            $row['name'],
            $row['description']
        );
        return new Category(
            (int) $row['id'],
            $categoryDetail,
        );
    }

    public function findAll(): array
    {
        $stmt = $this->db->query(
            'SELECT 
                c.idcategoria AS id,
                c.nombre AS name,
                c.descripcion AS description
            FROM categoria c'
        );
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categoryDetail = new CategoryDetail(
                $row['name'],
                $row['description']
            );
            $categories[] = new Category(
                (int) $row['id'],
                $categoryDetail,
            );
        }
        return $categories;
    }

    public function save(Category $category): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO categoria (nombre, descripcion) 
            VALUES (:name, :description)'
        );
        $stmt->bindParam(':name', $category->detail->name);
        $stmt->bindParam(':description', $category->detail->description);
        return $stmt->execute();
    }
}