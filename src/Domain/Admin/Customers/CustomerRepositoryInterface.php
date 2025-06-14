<?php
declare(strict_types=1);
namespace App\Domain\Admin\Customers;
use App\Domain\Admin\Customers\Customer;

interface CustomerRepositoryInterface
{
    public function findById(int $id): ?Customer;
    public function findAll(): array;
}