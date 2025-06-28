<?php
declare(strict_types=1);
namespace App\Domain\Admin\Categories;
use App\Domain\Admin\Categories\Category;
use App\Domain\Admin\Categories\ValueObject\CategoryDetail;

interface CategoryRepositoryInterface
{
    public function findById(int $id): ?Category;
    public function findAll(bool $isObject = true): array;
    public function save(Category $category): int;
    public function update(Category $category): int;
}