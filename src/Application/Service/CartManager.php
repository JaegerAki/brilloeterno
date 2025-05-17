<?php
namespace App\Application\Service;

use App\Domain\Cart\CartRepositoryInterface;

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

    public function getRepository(): CartRepositoryInterface
    {
        return isset($_SESSION['user_id'])
            ? $this->dbCartRepository
            : $this->sessionCartRepository;
    }
}