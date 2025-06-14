<?php
declare(strict_types=1);
namespace App\Domain\Admin\Categories;
use App\Domain\Admin\Categories\Category;
use App\Domain\Admin\Categories\ValueObject\CategoryDetail;

interface CategoryRepositoryInterface
{
    public function findById(int $id): ?Category;
    public function findAll(): array;
    public function save(Category $category): bool;
}