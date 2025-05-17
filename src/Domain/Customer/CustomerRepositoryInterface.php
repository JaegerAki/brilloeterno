<?php
declare(strict_types=1);
namespace App\Domain\Customer;
interface CustomerRepositoryInterface
{
    public function findAll(): array;
    public function findByEmail(string $email): ?Customer;
    public function findById(int $id): Customer;
    public function save(Customer $customer): bool;
    public function changePassword(int $id, string $email, string $oldPassword, string $newPassword): bool;
}
?>