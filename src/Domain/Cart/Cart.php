<?php
declare(strict_types=1);
namespace App\Domain\Cart;
use App\Domain\Product\Product;
use App\Domain\Cart\CartItem;
use JsonSerializable;
class Cart implements JsonSerializable
{
    private ?int $id;
    private int $customerId;

    /**
     * @var CartItem[]
     */
    private array $cartItems;

    /**
     * @param int $customerId
     * @param CartItem[] $cartItems
     */
    public function __construct(?int $customerId, ?array $cartItems)
    {
        $this->customerId = $customerId;
        $this->cartItems = $cartItems ?? [];
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @return CartItem[]
     */
    public function getProducts(): array
    {
        return $this->cartItems;
    }


    public function addItem(Product $product, int $quantity = 1): void
    {
        foreach ($this->cartItems as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                $item->addQuantity($quantity);
                return;
            }
        }
        $this->cartItems[] = new CartItem($product, $quantity);
    }

    public function removeItem(Product $product, int $quantity = 1): void
    {
        foreach ($this->cartItems as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                $item->removeQuantity($quantity);
                if ($item->getQuantity() <= 0) {
                    $this->cartItems = array_filter($this->cartItems, function ($i) use ($item) {
                        return $i !== $item;
                    });
                }
                return;
            }
        }
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'customerId' => $this->customerId,
            'cartItems' => $this->cartItems,
        ];
    }
}
?>