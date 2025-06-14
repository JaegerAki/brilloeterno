<?php
declare(strict_types=1);
namespace App\Domain\Admin\Roles;

final class Role
{
    public readonly int $id;
    public readonly string $name;
    public readonly string $description;
    public readonly string $state;
    public function __construct(
        int $id,
        string $name,
        string $description,
        string $state = 'active'
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->state = $state;
    }
}