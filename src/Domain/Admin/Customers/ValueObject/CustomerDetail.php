<?php
declare(strict_types=1);
namespace App\Domain\Admin\Customers\ValueObject;
use App\Domain\Common\ValueObject\IdentificationDocument;
class CustomerDetail
{
    public string $names;
    public string $email;
    public ?IdentificationDocument $identification= null;

    public function __construct(
        string $names,
        string $email,
        ?IdentificationDocument $identificationDocument = null
    ) {
        $this->names = $names;
        $this->email = $email;
        $this->identification = $identificationDocument;
    }
}