<?php
namespace App\Domain\Common\ValueObject;
final class Address
{
    public function __construct(
        public readonly string $street,
        public readonly string $city,
        public readonly string $state,
        public readonly string $postalCode,
        public readonly string $country,
    ) {
    }
    public function __toString(): string
    {
        return sprintf(
            '%s, %s, %s, %s, %s',
            $this->street,
            $this->city,
            $this->state,
            $this->postalCode,
            $this->country
        );
    }
}