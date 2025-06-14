<?php
declare(strict_types=1);
namespace App\Domain\Admin\Categories;
use App\Domain\Admin\Categories\ValueObject\CategoryDetail;
class Category{

    public readonly int $id;
    public readonly CategoryDetail $detail;
    public function __construct(
        ?int $id,
        CategoryDetail $categoryDetail,
    ) {
        $this->id = $id ?? 0; // Default to 0 if id is null
        $this->detail = $categoryDetail;
    }
}