<?php
declare(strict_types=1);
namespace App\Domain\Common\ValueObject;
use InvalidArgumentException;

final class Uuid
{
    private string $uuid;

    public function __construct(string $uuid)
    {
        if (!preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $uuid)) {
            throw new InvalidArgumentException("Invalid UUID format");
        }
        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}