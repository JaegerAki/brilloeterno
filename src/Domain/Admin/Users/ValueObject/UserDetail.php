<?php
declare(strict_types=1);
namespace App\Domain\Admin\Users\ValueObject;

class UserDetail
{
    public readonly string $names;
    public readonly string $email;

    public function __construct(
        string $names,
        string $email,
    ) {
        $this->names = $names;
        $this->email = $email;
    }
}