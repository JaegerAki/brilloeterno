<?php
declare(strict_types=1);
namespace App\Domain\Admin\Customers;
use App\Domain\Admin\Customers\ValueObject\CustomerDetail;
class Customer
{
    public readonly ?int $id;
    public readonly CustomerDetail $detail;
    public function __construct(?int $id, $customerDetail)
    {
        $this->id = $id ?? 0; // Default to 0 if id is null
        $this->detail = $customerDetail;
    }
}