<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Admin\Users;
use App\Domain\Admin\Users\ValueObject\UserDetail;
use App\Domain\Admin\Users\User;
use App\Domain\Admin\Roles\Role;
use App\Domain\Admin\Users\UserRepositoryInterface;
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
                u.idusuario AS id,
                u.nombres AS username,
                u.email AS email,
                r.nombre AS role_name,
                u.estado AS state
            FROM usuario u
            LEFT JOIN rol r ON u.idrol = r.idrol
            WHERE u.idusuario = :id'
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $role = new Role(
            (int) $row['id'],
            $row['role_name'],
            ''
        );
        $userDetail = new UserDetail(
            $row['username'],
            $row['email'],
        );
        return new User(
            (int) $row['id'],
            $userDetail,
            $role
        );
    }
    public function findAll(): array
    {
        $stmt = $this->db->query(
            'SELECT 
                u.idusuario AS id,
                u.nombres AS username,
                u.email AS email,
                r.nombre AS role_name,
                u.estado AS state
            FROM usuario u
            LEFT JOIN rol r ON u.idrol = r.idrol'
        );
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $role = new Role(
                (int) $row['id'],
                $row['role_name'],
                ''
            );
            $userDetail = new UserDetail(
                $row['username'],
                $row['email'],
            );
            $user = new User(
                (int) $row['id'],
                $userDetail,
                $role
            );
            $users[] = $user;
        }
        return $users;
    }

    public function save(UserDetail $detail, Role $role): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuario (nombreusuario, email, idrol, estado) 
            VALUES (:username, :email, :role_id, :state)'
        );
        $stmt->bindParam(':username', $detail->names);
        $stmt->bindParam(':email', $detail->email);
        $stmt->bindParam(':role_id', $role->id);
        return $stmt->execute();
    }

    public function delete(int $id,string $email): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM usuario WHERE idusuario = :id AND email = :email'
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }
}
