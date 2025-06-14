<?php
declare(strict_types=1);
namespace App\Domain\Product\ValueObject;
use JsonSerializable;
class ProductDetail
{
    private string $name;
    private string $description;
    private float $price;
    private string $picture;

    public function __construct(string $name, string $description, float $price, string $picture)
    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->picture = $picture;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'picture' => $this->picture,
        ];
    }
}