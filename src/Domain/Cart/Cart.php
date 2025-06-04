<?php
declare(strict_types=1);
namespace App\Domain\Cart;
use App\Domain\Product\Product;
use App\Domain\Cart\ValueObject\CartItem;
use IteratorAggregate;
use JsonSerializable;
use Traversable;
use ArrayIterator;

class Cart implements JsonSerializable, IteratorAggregate
{
    private int $customerId;
    /**
     * @var CartItem[]
     */
    private array $cartItems;

    /**
     * @param int $customerId
     * @param CartItem[] $cartItems
     */
    public function __construct(?int $customerId, array $cartItems = [])
    {
        $this->customerId = $customerId;
        $this->cartItems = $cartItems;
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

    public function getTotalPrice(): float
    {
        $total = 0.0;
        foreach ($this->cartItems as $item) {
            $total += $item->getPrice();
        }
        return $total;
    }

    public function getTotalItems(): int
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            $total += $item->getQuantity();
        }
        return $total;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->cartItems);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'customerId' => $this->customerId,
            'cartItems' =>  $this->cartItems,
            'totalPrice' => $this->getTotalPrice(),
            'totalItems' => $this->getTotalItems(),
        ];
    }
}
?>