<?php
declare(strict_types=1);
namespace App\Domain\Cart;
use App\Domain\Product\Product;
use JsonSerializable;
class Cart implements JsonSerializable
{
    private ?int $id;
    private int $customerId;

    /**
     * @var Product[]
     */
    private array $products;

    /**
     * @param int $customerId
     * @param Product[] $products
     */
    public function __construct(?int $customerId, ?array $products)
    {
        $this->customerId = $customerId;
        $this->products = $products;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param Product[] $products
     */
    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }

    public function removeProduct(Product $product): void
    {
        foreach ($this->products as $key => $cartProduct) {
            if ($cartProduct->getId() === $product->getId()) {
                unset($this->products[$key]);
                break;
            }
        }
    }

    

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'customerId' => $this->customerId,
            'products' => $this->products,
        ];
    }
}
?>
