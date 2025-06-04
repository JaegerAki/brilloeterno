<?php
declare(strict_types=1);
namespace App\Domain\Store;
use App\Domain\Store\ValueObject\StoreItem;
use App\Domain\Product\Product;
use JsonSerializable;
class StoreDetail implements JsonSerializable
{
    private StoreItem $storeItem;

    public function __construct(StoreItem $storeItem)
    {
        $this->storeItem = $storeItem;
    }

    public function getStoreItem(): StoreItem
    {
        return $this->storeItem;
    }
    public function jsonSerialize(): array
    {
        return [
            'storeItem' => $this->storeItem,
        ];
    }
}