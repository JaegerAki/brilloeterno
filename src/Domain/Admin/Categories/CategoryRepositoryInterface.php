<?php
declare(strict_types=1);
namespace App\Domain\Admin\Categories;
use App\Domain\Admin\Categories\Category;
use App\Domain\Admin\Categories\ValueObject\CategoryDetail;

interface CategoryRepositoryInterface
{
    public function findAll(bool $isObject = true): array;
    public function get(int $id): ?Category;
    public function insert(Category $category): int;
    public function update(Category $category): int;
}