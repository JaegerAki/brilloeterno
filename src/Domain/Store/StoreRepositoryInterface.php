<?php
declare(strict_types=1);
namespace App\Domain\Store;
use App\Domain\Store\Store;
use App\Domain\Store\ValueObject\StoreItem;
use App\Domain\Store\StoreDetail;
interface StoreRepositoryInterface
{
    /**
     * @return StoreItem[]
     */
    public function findStore(): Store;
    public function findStoreItemById(int $id): ?StoreItem;
    public function findStoreItemDetailItemByProductId(int $productId): ?StoreDetail;
}