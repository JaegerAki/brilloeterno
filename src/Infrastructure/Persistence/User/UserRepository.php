<?php

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
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
            'SELECT 
                        idcliente as id
                        ,email as username
                    FROM cliente WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return new User(
            $row['username'],
            $row['first_name'],
            $row['last_name'],
            $row['email'],
            $row['password']
        );
    }

    public function findByUsername(string $username): ?User
    {
        $stmt = $this->db->prepare('SELECT id,username,first_name,last_name,email FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return new User(
            $row['id'],
            $row['first_name'], 
            $row['last_name'],
            $row['email'],
            $row['password']
        );
    }

    public function save(User $user): void
    {
        if ($user->getId()) {
            // Update existing user
            $stmt = $this->db->prepare('UPDATE users SET username = :username, first_name = :firstName, last_name = :lastName, email=:email ,password = :password WHERE id = :id');
            $stmt->execute([
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'email' => $user->getEmail(),
                'password' => $user->getPasswordHash(),
                'id' => $user->getId(),
            ]);
        } else {
            // Insert new user
            $stmt = $this->db->prepare('INSERT INTO users (username, first_name, last_name,email, password) VALUES (:username, :firstName, :lastName, :password)');
            $stmt->execute([
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'email' => $user->getEmail(),
                'password' => $user->getPasswordHash(),
            ]);
        }
    }
}