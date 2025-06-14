<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Admin\Customers;
use App\Domain\Admin\Customers\ValueObject\CustomerDetail;
use App\Domain\Admin\Customers\Customer;
use App\Domain\Admin\Customers\CustomerRepositoryInterface;
use App\Domain\Common\ValueObject\IdentificationDocument;
use PDO;
class CustomerRepository implements CustomerRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?Customer
    {
        $stmt = $this->pdo->prepare('SELECT
                                        a.idcliente AS id,
                                        a.nombres as names,
                                        a.email,
                                        b.nombre AS document_type,
                                        a.numero_identificacion AS document_number
                                    FROM cliente a LEFT JOIN tipo_identificacion b on a.idtipoidentificacion = b.idtipoidentificacion WHERE a.idcliente = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $customerDetail = new CustomerDetail(
                $row['names'],
                $row['email'],
                new IdentificationDocument($row['document_type'], $row['document_number'])
            );
            return new Customer((int)$row['id'], $customerDetail);
        }
        return null;
    }
    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT
                                        a.idcliente AS id,
                                        a.nombres as names,
                                        a.email,
                                        b.nombre AS document_type,
                                        a.numero_identificacion AS document_number
                                    FROM cliente a LEFT JOIN tipo_identificacion b on a.idtipoidentificacion = b.idtipoidentificacion');
        $customers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $customerDetail = new CustomerDetail(
                $row['names'],
                $row['email'],
                new IdentificationDocument($row['document_type'], $row['document_number'])
            );
            $customers[] = new Customer((int)$row['id'], $customerDetail);
        }
        return $customers;
    }
}