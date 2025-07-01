<?php
declare(strict_types=1);
namespace App\Domain\Admin\Inventory;
use App\Domain\Admin\Inventory\ValueObject\ProductInventoryDetail;
use App\Domain\Admin\Inventory\ProductCategory;
class ProductInventory
{
    public int $id;
    public ProductInventoryDetail $productInventoryDetail;
    public ProductCategory $productCategory;

    public function __construct(
        ?int $id,
        ProductInventoryDetail $productInventoryDetail,
        ProductCategory $productCategory
    ) {
        $this->id = $id;
        $this->productInventoryDetail = $productInventoryDetail;
        $this->productCategory = $productCategory;
    }
}
