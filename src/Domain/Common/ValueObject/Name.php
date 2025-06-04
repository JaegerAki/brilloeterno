<?php
declare(strict_types=1);
namespace App\Domain\Common\ValueObject;

class Name
{
    private string $fullname;
    public function __construct(string $fullname)
    {
        if (empty($fullname)) {
            throw new \InvalidArgumentException("Full name cannot be empty");
        }
        if (strlen($fullname) > 255) {
            throw new \InvalidArgumentException("Full name cannot exceed 255 characters");
        }
        if (!preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
            throw new \InvalidArgumentException("Full name can only contain letters and spaces");
        }
        $this->fullname = $fullname;
    }
    public function getFullname(): string
    {
        return $this->fullname;
    }
    public function getFirstName(): string
    {
        $parts = explode(' ', $this->fullname);
        return $parts[0] ?? '';
    }
}