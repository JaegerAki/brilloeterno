<?php
declare(strict_types=1);
namespace App\Domain\Customer;
use JsonSerializable;
class Customer implements JsonSerializable
{
    private ?int $id;
    private string $email;
    private string $fullname;
    private ?string $passwordHash;
    private ?string $direction;
    private ?string $phone;
    private ?string $typeDocument;
    private ?string $numberDocument;

    public function __construct(?int $id, string $email, string $fullname, string $passwordHash)
    {
        $this->id = $id;
        $this->email = $email;
        $this->fullname = $fullname;
        $this->passwordHash = $passwordHash;
    }
    
    public function getId(): ?int
    {
        print_r($this->id);
        return $this->id;
    }

    public function getEmail(): string
    {
        print_r($this->id);
        return $this->email;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'fullname' => $this->fullname,
            'passwordHash' => $this->passwordHash,
        ];
    }
}

?>