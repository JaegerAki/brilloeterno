<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Admin\Dashboard;

use App\Domain\Admin\Dashboard\DashboardRepositoryInterface;
use App\Domain\Admin\Dashboard\Dashboard;
use App\Domain\Admin\Dashboard\ValueObject\MenuOption;
use App\Domain\Admin\Dashboard\ValueObject\UserInfo;
use App\Domain\Common\ValueObject\Name;
use App\Domain\Common\ValueObject\Email;
use PDO;
class DashboardRepository implements DashboardRepositoryInterface
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function getDashboardData(int $userId): Dashboard
    {
        //contexto de tablas: "database\migration\001_create_tables.sql"
        $queryUser =    "SELECT 
                            a.idusuario
                            ,a.nombres
                            ,a.email
                            ,b.nombre as rol
                            ,a.estado
                        FROM usuario a 
                        JOIN rol b on a.idrol = b.idrol 
                        WHERE a.idusuario = :id";
        $queryMenuOptions = "SELECT
                                distinct(c.nombre)
                                ,c.descripcion
                                ,c.ruta
                            FROM rol_opcion a 
                            JOIN rol b ON a.idrol = b.idrol
                            JOIN opcion c ON a.idopcion = c.idopcion
                            JOIN accion d ON a.idaccion = d.idaccion
                            JOIN usuario e ON a.idrol = e.idrol 
                            WHERE e.idusuario = :id";
        $stmtUser = $this->pdo->prepare($queryUser);
        $stmtUser->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmtUser->execute();
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            throw new \Exception("User not found");
        }
        $stmtMenuOptions = $this->pdo->prepare($queryMenuOptions);
        $stmtMenuOptions->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmtMenuOptions->execute();
        $menuOptions = $stmtMenuOptions->fetchAll(PDO::FETCH_ASSOC);
        $menuOptionsObjects = array_map(function ($option) {
            return new MenuOption($option['nombre'],$option['ruta'] ,$option['descripcion']);
        }, $menuOptions);
        $userInfo = new UserInfo(
            (int)$user['idusuario'],
            new Name($user['nombres']),
            new Email($user['email']),
            $user['estado']
        );
        return new Dashboard($userInfo, $menuOptionsObjects);
    }
}


