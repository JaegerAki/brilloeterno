<?php
declare(strict_types=1);
namespace App\Domain\Admin\Dashboard\ValueObject;
use App\Domain\Common\ValueObject\Name;
use App\Domain\Common\ValueObject\Email;
use JsonSerializable;
class UserInfo implements JsonSerializable
{
    private int $id;
    private Name $name;
    private Email $email;
    private string $role;
    private ?string $status;
    public function __construct(int $id, Name $name, Email $email, string $role, ?string $status = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
        ];
    }
}