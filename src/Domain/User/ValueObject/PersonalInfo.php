<?php
declare(strict_types=1);
namespace App\Domain\User\ValueObject;
class PersonalInfo
{
    private ?string $fullname;
    private ?string $direction;
    private ?string $phone;
    public function __construct(?string $fullname, ?string $direction, ?string $phone)
    {
        $this->fullname = $fullname;
        $this->direction = $direction;
        $this->phone = $phone;
    }
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function getPhoneHided(): ?string
    {
        if ($this->phone === null) {
            return null;
        }
        $length = strlen($this->phone);
        if ($length <= 4) {
            return str_repeat('*', $length);
        }
        return str_repeat('*', $length - 4) . substr($this->phone, -4);
    }
}