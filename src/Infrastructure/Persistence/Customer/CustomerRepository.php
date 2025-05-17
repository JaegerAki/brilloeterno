<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Customer;
use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepositoryInterface;
use PDO;
class CustomerRepository implements CustomerRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query('SELECT idcliente,nombres,email,contrasena FROM cliente');
        $customers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $customers[] = new Customer(
                (int) $row['idcliente'],
                $row['email'],
                $row['nombres'],
                ''
            );
        }
        return $customers;
    }
    public function findByEmail(string $email): ?Customer
    {
        $stmt = $this->connection->prepare('SELECT idcliente,nombres,email,contrasena FROM cliente WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Customer(
                (int) $row['idcliente'],
                $row['email'],
                $row['nombres'],
                $row['contrasena']
            );
        }
        return null;
    }
    public function findById(int $id): Customer
    {
        $stmt = $this->connection->prepare('SELECT idcliente,nombres,email,contrasena FROM cliente WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Customer(
                (int) $row['idcliente'],
                $row['email'],
                $row['nombres'],
                $row['contrasena']
            );
        }
        throw new \Exception('Customer not found');
    }
    public function save(Customer $customer): bool
    {
        $email = $customer->getEmail();
        $fullname = $customer->getFullname();
        $passwordHash = $customer->getPasswordHash();

        if ($customer->getId()) {
            $id = $customer->getId();
            $stmt = $this->connection->prepare('UPDATE cliente SET email = :email, nombres = :fullname, contrasena = :passwordHash WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        } else {
            $stmt = $this->connection->prepare('INSERT INTO cliente (email, nombres, contrasena) VALUES (:email, :fullname, :passwordHash)');
        }
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':passwordHash', $passwordHash);
        $result = $stmt->execute();
        return $result;
    }
    public function changePassword(int $id, string $email, string $oldPassword, string $newPassword): bool
    {
        $stmt = $this->connection->prepare('SELECT contrasena FROM cliente WHERE idcliente = :id AND email = :email');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && password_verify($oldPassword, $row['contrasena'])) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->connection->prepare('UPDATE cliente SET contrasena = :newPassword WHERE idcliente = :id');
            $stmt->bindParam(':newPassword', $newPasswordHash);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }
        return false;
    }
}