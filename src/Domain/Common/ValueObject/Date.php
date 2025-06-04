<?php
declare(strict_types=1);
namespace App\Domain\Common\ValueObject;
use InvalidArgumentException;
use DateTimeImmutable;

final class Date
{
    private DateTimeImmutable $date;

    public function __construct(string $date)
    {
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d', $date);
        if ($dateTime === false) {
            throw new InvalidArgumentException("Fecha inválida, debe estar en formato YYYY-MM-DD");
        }
        $this->date = $dateTime;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function format(string $format = 'Y-m-d'): string
    {
        return $this->date->format($format);
    }

    public function value(): string
    {
        return $this->date->format('Y-m-d');
    }
}