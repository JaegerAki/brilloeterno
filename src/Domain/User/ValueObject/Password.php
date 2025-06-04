<?php
declare(strict_types=1);
namespace App\Domain\User\ValueObject;
use InvalidArgumentException;
class Password
{
    private string $password;

    public function __construct(string $password)
    {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException("Password must be at least 8 characters long");
        }
        /*if (!preg_match('/[A-Z]/', $password)) {
            throw new InvalidArgumentException("Password must contain at least one uppercase letter");
        }
        if (!preg_match('/[a-z]/', $password)) {
            throw new InvalidArgumentException("Password must contain at least one lowercase letter");
        }
        if (!preg_match('/[0-9]/', $password)) {
            throw new InvalidArgumentException("Password must contain at least one number");
        }
        if (!preg_match('/[\W_]/', $password)) {
            throw new InvalidArgumentException("Password must contain at least one special character");
        }*/
        $this->password = $password;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function hash(): string
    {
        return password_hash($this->password, PASSWORD_BCRYPT);
    }
    public function verify(string $hash): bool
    {
        return password_verify($this->password, $hash);
    }
}