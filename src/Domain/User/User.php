<?php

declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\PersonalInfo;
use App\Domain\Common\ValueObject\Email;
use App\Domain\User\ValueObject\Phone;
use Person;

class User implements JsonSerializable
{
    private ?int $id;

    private PersonalInfo $personalInfo;

    private Email $email;

    private Password $password;

    public function __construct(
        ?int $id,
        PersonalInfo $personalInfo,
        Email $email,
        ?Password $password
    ) {
        $this->id = $id;
        $this->personalInfo = $personalInfo;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPersonalInfo(): PersonalInfo
    {
        return $this->personalInfo;
    }

    public function getPasswordHash(): ?Password
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'personalInfo' => $this->personalInfo,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
