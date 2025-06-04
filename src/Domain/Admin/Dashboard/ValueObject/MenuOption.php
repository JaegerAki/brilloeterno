<?php
declare(strict_types=1);
namespace App\Domain\Admin\Dashboard\ValueObject;
use JsonSerializable;

class MenuOption implements JsonSerializable
{
    private string $name;
    private ?string $description;
    private string $route;

    public function __construct(string $name, string $route, ?string $description)
    {
        $this->name = $name;
        $this->description = $description;
        $this->route = $route;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getDescripcion(): ?string
    {
        return $this->description;
    }
    public function getRoute(): string
    {
        return $this->route;
    }
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'route' => $this->route
        ];
    }
}