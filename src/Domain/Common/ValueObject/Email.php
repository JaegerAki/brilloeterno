<?php
declare(strict_types=1);
namespace App\Domain\Common\ValueObject;

final class Email
{
    private string $email;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address");
        }
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getEmailObfuscated(): string
    {
        $parts = explode('@', $this->email);
        $localPart = $parts[0];
        $domainPart = $parts[1];

        // Obfuscate the local part, keeping the first and last character visible
        if (strlen($localPart) > 2) {
            $obfuscatedLocalPart = substr($localPart, 0, 1) . str_repeat('*', strlen($localPart) - 2) . substr($localPart, -1);
        } else {
            $obfuscatedLocalPart = str_repeat('*', strlen($localPart));
        }
        return $obfuscatedLocalPart . '@' . $domainPart;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}