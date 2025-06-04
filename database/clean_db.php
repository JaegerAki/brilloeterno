<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Migration\ConsoleColor;
use Migration\Output;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';
$dbname = $_ENV['DB_DATABASE'] ?? 'brilloeterno';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Deshabilitar claves foráneas
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

    // Obtener todas las tablas
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $table) {
        $pdo->exec("TRUNCATE TABLE `$table`");
        echo Output::color("Tabla '$table' limpiada.", ConsoleColor::GREEN) . "\n";
    }

    // Habilitar claves foráneas
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    echo Output::color("Todas las tablas han sido limpiadas.", ConsoleColor::GREEN) . "\n";
} catch (PDOException $e) {
    echo Output::color("Error al limpiar las tablas: " . $e->getMessage(), ConsoleColor::RED);
}