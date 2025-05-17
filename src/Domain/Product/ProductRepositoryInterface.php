<?php
namespace App\Domain\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): Product;
}
?>