<?php
// filepath: c:\xampp\htdocs\brilloeterno\src\Domain\Store\Store.php
declare(strict_types=1);

namespace App\Domain\Store;

use App\Domain\Store\ValueObject\StoreItem;
use App\Domain\Product\Product;
use JsonSerializable;

class Store implements JsonSerializable, \IteratorAggregate
{
    /** @var StoreItem[] */
    private array $storeItems;

    public function __construct(array $storeItems = [])
    {
        $this->storeItems = $storeItems;
    }
    /**
     * @return StoreItem[]
     */
    public function getItems(): array
    {
        return $this->storeItems;
    }
    public function jsonSerialize(): array
    {
        return [
            'storeItems' => $this->storeItems,
        ];
    }
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->storeItems);
    }
}