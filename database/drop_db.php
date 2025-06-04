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
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo Output::color("Ejecutando eliminación '$dbname'", ConsoleColor::BLUE) . "\n";
    $pdo->exec("DROP DATABASE IF EXISTS `$dbname`");
    echo Output::color("Base de datos '$dbname' eliminada (si existía).", ConsoleColor::GREEN) . "\n";
} catch (PDOException $e) {
    echo Output::color("Error al eliminar la base de datos: " . $e->getMessage(), ConsoleColor::RED) . "\n";
}