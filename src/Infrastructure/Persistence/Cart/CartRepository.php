<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Cart;
use App\Domain\Product\Product;
use App\Domain\Cart\Cart;
use App\Domain\Cart\ValueObject\CartItem;
use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Product\ValueObject\ProductDetail;
use PDO;

class CartRepository implements CartRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findCartByIdCustomerId(int $customerid = 0): cart
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
        WHERE c.idcliente = ? AND cd.cantidad > 0'
        );
        $stmt->execute([$customerid]);
        $cart = new Cart($customerid, []);
        $cartItems = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new Product(
                (int) $row['idproducto'],
                new ProductDetail(
                    $row['producto'],
                    $row['descripcion'],
                    (float) $row['precio'],
                    $row['imagen']
                )
            );
            $cartItem = new CartItem($product, (int) $row['cantidad']);
            $cartItems[] = $cartItem;
        }
        $cart = new Cart($customerid, $cartItems);
        return $cart;
    }
    public function addItemToCart(int $customerid, int $productid): bool
    {
        //agregar un producto al carrito, si ya existe se incrementa la cantidad
        $this->pdo->beginTransaction();
        try {
            // Verificar si el carrito ya existe para el cliente
            $stmt = $this->pdo->prepare(
                'SELECT idcarrito FROM carrito WHERE idcliente = ?'
            );
            $stmt->execute([$customerid]);
            $cartId = $stmt->fetchColumn();

            // Si no existe, crear un nuevo carrito
            if (!$cartId) {
                $stmt = $this->pdo->prepare(
                    'INSERT INTO carrito (idcliente) VALUES (?)'
                );
                $stmt->execute([$customerid]);
                $cartId = (int) $this->pdo->lastInsertId();
            }

            // Verificar si el producto ya está en el carrito
            $stmt = $this->pdo->prepare(
                'SELECT id FROM carrito_detalles WHERE idcarrito = ? AND idproducto = ?'
            );
            $stmt->execute([$cartId, $productid]);
            $itemId = $stmt->fetchColumn();

            if ($itemId) {
                // Si el producto ya está en el carrito, incrementar la cantidad
                $stmt = $this->pdo->prepare(
                    'UPDATE carrito_detalles SET cantidad = cantidad + 1 WHERE id = ?'
                );
                $stmt->execute([$itemId]);
            } else {
                // Si no está, agregarlo al carrito con cantidad 1
                $stmt = $this->pdo->prepare(
                    'INSERT INTO carrito_detalles (idcarrito, idproducto, cantidad) VALUES (?, ?, 1)'
                );
                $stmt->execute([$cartId, $productid]);
            }
            $this->pdo->commit();
            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw new \Exception('Error adding item to cart: ' . $e->getMessage());
        }
    }
    public function removeItemFromCart(int $customerid, int $productid): bool
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM carrito a JOIN carrito_detalles cd ON a.idcarrito = cd.idcarrito
            WHERE a.idcliente = ? AND cd.idproducto = ?'
        );
        $stmt->execute([$customerid, $productid]);
        return $stmt->rowCount() > 0;
    }
    public function increaseItemToCart(int $customerid, int $productid, int $quantity = 1): bool
    {
        // Aumentar la cantidad del producto en el carrito
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                'UPDATE carrito a
                JOIN carrito_detalles cd ON a.idcarrito = cd.idcarrito
                SET cd.cantidad = cd.cantidad + ?
                WHERE a.idcliente = ? AND cd.idproducto = ?'
            );
            $stmt->execute([$quantity, $customerid, $productid]);

            if ($stmt->rowCount() === 0) {
                // Si no se actualizó ninguna fila, significa que el producto no estaba en el carrito
                $this->addItemToCart($customerid, $productid);
            }

            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        return true;
    }
    public function decreaseItemFromCart(int $customerid, int $productid, int $quantity = 1): bool
    {
        // Disminuir la cantidad del producto en el carrito, eliminando el ítem si la cantidad llega a 0
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                // Actualizar la cantidad del producto en el carrito la tabla carrito es la cabecera y carrito_detalles es el detalle, en la tabla carrito tiene el customerid
                'UPDATE carrito a
                JOIN carrito_detalles cd ON a.idcarrito = cd.idcarrito
                SET cd.cantidad = GREATEST(cd.cantidad - ?, 0)
                WHERE a.idcliente = ? AND cd.idproducto = ? AND cd.cantidad > 0'
            );
            $stmt->execute([$quantity, $customerid, $productid]);

            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM carrito_detalles WHERE idcarrito = ? AND idproducto = ?'
        );
        $stmt->execute([$customerid, $productid]);

        return true;
    }
}
