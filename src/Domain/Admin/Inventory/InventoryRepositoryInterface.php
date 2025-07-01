<?php
namespace App\Domain\Admin\Inventory;
use App\Domain\Admin\Inventory\ProductInventory;
use App\Domain\Admin\Inventory\ProductCategory;

interface InventoryRepositoryInterface
{
    public function findAll(bool $isObject = true): array;
    public function get(int $id): ?ProductInventory;
    public function update(ProductInventory $product): int;
    public function insert(ProductInventory $product): int;
    public function delete(int $id): int;
}