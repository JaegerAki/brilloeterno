<?php
namespace App\Domain\Admin\Inventory;
use App\Domain\Admin\Inventory\ProductInventory;
use App\Domain\Admin\Inventory\ProductCategory;

interface InventoryRepositoryInterface
{
    /**
     * @return ProductInventory[]
     */
    public function getAllProducts(): array;

    /**
     * @param int $id
     * @return ProductInventory|null
     */
    public function getProductById(int $id): ?ProductInventory;
    /**
     * @param ProductInventory $product
     * @return void
     */
    public function saveProduct(ProductInventory $product): void;

    /**
     * @param int $id
     * @return void
     */
    public function deleteProduct(int $id): void;

    /**
     * @return ProductCategory[]
     */
    public function getAllCategories(): array;

    /**
     * @param int $id
     * @return ProductCategory|null
     */
    public function getCategoryById(int $id): ?ProductCategory;
    public function saveCategory(ProductCategory $category): void;
    public function deleteCategory(int $id): void;
}