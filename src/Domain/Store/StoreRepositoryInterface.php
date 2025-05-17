<?php
declare(strict_types=1);
namespace App\Domain\Store;
use App\Domain\Product\Product;
interface StoreRepositoryInterface
{
    /**
     * @return Product[]
     */
    public function findAll(): array;
}