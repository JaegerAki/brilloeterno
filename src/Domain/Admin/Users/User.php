<?php
declare(strict_types=1);
namespace App\Domain\Admin\Users;
use App\Domain\Admin\Roles\Role;
use App\Domain\Admin\Users\ValueObject\UserDetail;

class User
{
    public readonly int $id;
    public readonly UserDetail $detail;
    public readonly Role $role;
    public function __construct(
        int $id,
        UserDetail $detail,
        Role $role
    ) {
        $this->id = $id;
        $this->detail = $detail;
        $this->role = $role;
    }
}