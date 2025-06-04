<?php
namespace App\Application\Service;

use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Cart\Cart;

class CartManager
{
    private CartRepositoryInterface $dbCartRepository;
    private CartRepositoryInterface $sessionCartRepository;

    public function __construct(
        CartRepositoryInterface $dbCartRepository,
        CartRepositoryInterface $sessionCartRepository
    ) {
        $this->dbCartRepository = $dbCartRepository;
        $this->sessionCartRepository = $sessionCartRepository;
    }

    public function getRepository(): Cart
    {
        $customerId = $_SESSION['user_id'] ?? 0;
        if ($customerId) {
            return $this->dbCartRepository->findCartByIdCustomerId($customerId);
        }
        return $this->sessionCartRepository->findCartByIdCustomerId();
    }

    public function addItemToCart(int $productId): bool
    {
        $customerId = $_SESSION['user_id'] ?? 0;
        if ($customerId) {
            return $this->dbCartRepository->addItemToCart($customerId, $productId);
        } else {
            return $this->sessionCartRepository->addItemToCart(0, $productId);
        }
    }

    public function removeItemFromCart(int $productId): bool
    {
        $customerId = $_SESSION['user_id'] ?? 0;
        if ($customerId) {
            return $this->dbCartRepository->removeItemFromCart($customerId, $productId);
        } else {
            return $this->sessionCartRepository->removeItemFromCart(0, $productId);
        }
    }

    public function increaseItemToCart(int $productId, int $quantity = 1): bool
    {
        $customerId = $_SESSION['user_id'] ?? 0;
        if ($customerId) {
            return $this->dbCartRepository->increaseItemToCart($customerId, $productId, $quantity);
        } else {
            return $this->sessionCartRepository->increaseItemToCart(0, $productId, $quantity);
        }
    }

    public function decreaseItemFromCart(int $productId, int $quantity = 1): bool
    {
        $customerId = $_SESSION['user_id'] ?? 0;
        if ($customerId) {
            return $this->dbCartRepository->decreaseItemFromCart($customerId, $productId, $quantity);
        } else {
            return $this->sessionCartRepository->decreaseItemFromCart(0, $productId, $quantity);
        }
    }
}