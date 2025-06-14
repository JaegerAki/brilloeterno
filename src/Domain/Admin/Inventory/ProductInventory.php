<?php
declare(strict_types=1);
namespace App\Domain\Admin\Inventory;
use App\Domain\Admin\Inventory\ValueObject\ProductInventoryDetail;
use App\Domain\Admin\Inventory\ProductCategory;
class ProductInventory
{
    public readonly int $id;
    public readonly ProductInventoryDetail $productInventoryDetail;
    public readonly ProductCategory $productCategory;

    public function __construct(
        int $id,
        ProductInventoryDetail $productInventoryDetail,
        ProductCategory $productCategory
    ) {
        $this->id = $id;
        $this->productInventoryDetail = $productInventoryDetail;
        $this->productCategory = $productCategory;
    }
}
