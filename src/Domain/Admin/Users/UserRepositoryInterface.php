<?php
declare(strict_types=1);
namespace App\Domain\Admin\Users;
use App\Domain\Admin\Roles\Role;
use App\Domain\Admin\Users\ValueObject\UserDetail;
Use App\Domain\Admin\Users\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findAll(): array;

    public function save(UserDetail $detail, Role $role): bool;

    public function delete(int $id,string $email): bool;
}