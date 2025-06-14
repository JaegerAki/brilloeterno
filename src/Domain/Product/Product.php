<?php
declare(strict_types=1);

namespace App\Domain\Product;

use App\Domain\Product\ValueObject\ProductDetail;

use JsonSerializable;

class Product implements JsonSerializable
{
    private ?int $id;
    private ProductDetail $productDetail;

    public function __construct(?int $id, ProductDetail $productDetail)
    {
        $this->id = $id;
        $this->productDetail = $productDetail;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getDetail(): ProductDetail
    {
        return $this->productDetail;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'detail' => $this->productDetail,
        ];
    }
}
?>