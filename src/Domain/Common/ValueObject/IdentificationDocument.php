<?php
declare(strict_types=1);
namespace App\Domain\Common\ValueObject;
use InvalidArgumentException;
final class IdentificationDocument {
    public string $type;  // ej: 'DNI', 'RUC', 'PASSPORT'
    public string $number;

    public function __construct(string $type, string $number) {
        if (!in_array($type, ['DNI', 'RUC', 'PASSPORT'])) {
            throw new InvalidArgumentException("Tipo de documento no válido");
        }

        if ($type === 'DNI' && !preg_match('/^\d{8}$/', $number)) {
            throw new InvalidArgumentException("DNI inválido");
        }

        if ($type === 'RUC' && !preg_match('/^\d{11}$/', $number)) {
            throw new InvalidArgumentException("RUC inválido");
        }
        // más validaciones por tipo...
        $this->type = $type;
        $this->number = $number;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getNumber(): string {
        return $this->number;
    }

    public function getNumberOfuscated(): string {
        return str_repeat('*', strlen($this->number) - 4) . substr($this->number, -4);
    }

    public function value(): string {
        return $this->type . ':' . $this->number;
    }
}
