<?php
declare(strict_types=1);
namespace App\Domain\Cart;
use App\Domain\Product\Product;
interface CartRepositoryInterface
{
    /**
     * @param int $customerid The ID of the customer.
     * @return Product[]
     */
    public function findByIdCustomerId(int $customerid): array;

    /**
     * Adds a product to the customer's cart.
     *
     * @param int $customerid The ID of the customer.
     * @param int $productid The ID of the product to add.
     * @return void
     */

    /**
     * Removes a product from the customer's cart.
     *
     * @param int $customerid The ID of the customer.
     * @param int $productid The ID of the product to remove.
     * @return void
     */
    public function addProductToCart(int $customerid, int $productid): void;
    public function removeProductFromCart(int $customerid, int $productid): void;
}
?>