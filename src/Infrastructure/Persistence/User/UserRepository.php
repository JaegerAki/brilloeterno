<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\Common\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\PersonalInfo;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare(
            "SELECT 
                        idcliente as id
                        ,email as email
                        ,nombres as fullname
                        ,'' as direction
                        ,'' as phone
                    FROM cliente WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row)
            return null;
        return new User(
            $row['id'],
            new PersonalInfo($row['fullname'], $row['direction'], $row['phone']),
            new Email($row['email']),
            new Password($row['password']),
        );
    }

    public function findByUsername(string $email): ?User
    {
        $stmt = $this->db->prepare(
            "SELECT 
                        idcliente as id
                        ,email as email
                        ,nombres as fullname
                        ,'' as direction
                        ,'' as phone
                    FROM cliente WHERE id = :id"
        );
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        if (!$row)
            return null;
        return new User(
            $row['id'],
            new PersonalInfo($row['fullname'], $row['direction'], $row['phone']),
            new Email($row['email']),
            new Password($row['password']),
        );
    }

    public function save(User $user): void
    {
        if ($user->getId()) {
            $stmt = $this->db->prepare('UPDATE users SET email = :email, nombres = :fullname, last_name = :lastName, email=:email ,password = :password WHERE id = :id');
            $stmt->execute([
                'fullname' => $user->getPersonalInfo()->getFullname(),
                'email' => $user->getEmail(),
                'password' => $user->getPasswordHash(),
                'id' => $user->getId(),
            ]);
        } else {
            $stmt = $this->db->prepare('INSERT INTO users (email, nombres, last_name,email, password) VALUES (:email, :fullname, :lastName, :password)');
            $stmt->execute([
                'fullname' => $user->getPersonalInfo()->getFullname(),
                'email' => $user->getEmail(),
                'password' => $user->getPasswordHash(),
            ]);
        }
    }
}