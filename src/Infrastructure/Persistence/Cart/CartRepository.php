<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Cart;

use App\Domain\Product\Product;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartRepositoryInterface;
use PDO;

class CartRepository implements CartRepositoryInterface
{
    private Cart $cart;
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByIdCustomerId(int $idcliente): array
{
    $stmt = $this->pdo->prepare(
        'SELECT
            c.idcarrito,
            cd.id AS idcarrito_detalle,
            p.idproducto,
            p.nombre AS producto,
            p.descripcion,
            p.precio,
            p.imagen,
            cd.cantidad,
            per.idpersonalizacion,
            per.descripcion AS personalizacion,
            dp.instrucciones,
            dp.precio_extra
        FROM carrito c
        INNER JOIN carrito_detalles cd ON c.idcarrito = cd.idcarrito
        INNER JOIN producto p ON cd.idproducto = p.idproducto
        LEFT JOIN personalizaciones per ON cd.idpersonalizacion = per.idpersonalizacion
        LEFT JOIN detalle_personalizacion dp ON dp.idcarrito_detalle = cd.id
        WHERE c.idcliente = ?'
    );
    $stmt->execute([$idcliente]);
    $products = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $product = [
            'id' => $row['idproducto'],
            'name' => $row['producto'],
            'description' => $row['descripcion'],
            'price' => $row['precio'],
            'picture' => $row['imagen'],
            //'cantidad' => $row['cantidad'],
            //'personalizacion' => $row['personalizacion'],
            //'instrucciones' => $row['instrucciones'],
            //'precio_extra' => $row['precio_extra'],
        ];
        $products[] = $product;
    }
    return $products;
}

    public function addItemToCart(int $idcliente, int $productid):void{

    }
    public function removeItemFromCart(int $idcliente, int $productid):void{
    }
}
