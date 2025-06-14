<?php
namespace App\Domain\Admin\Categories\ValueObject;
class CategoryDetail
{
    public string $name;
    public string $description;
    public function __construct(
        string $name,
        string $description,
    ) {
        $this->name = $name;
        $this->description = $description;
    }
}