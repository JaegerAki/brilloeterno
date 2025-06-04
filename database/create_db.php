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

    // Verifica si la base de datos existe
    echo Output::color("Ejecutando levantamiento '$dbname'", ConsoleColor::BLUE) . "\n";
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbname'");
    $dbExists = $stmt->fetch() !== false;

    if (!$dbExists) {
        $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo Output::color("Base de datos '$dbname' creada.", ConsoleColor::GREEN) . "\n";

        // Conectar a la nueva base de datos y migrar tablas
        $pdoDb = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdoDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $migrationsDir = __DIR__ . '/migration';
        if (!is_dir($migrationsDir)) {
            echo Output::color("La carpeta migracion no existe: $migrationsDir\n", ConsoleColor::RED);
            exit(1);
        }
        $files = glob($migrationsDir . '/*.sql');

        foreach ($files as $file) {
            echo Output::color("Ejecutando migración: $file", ConsoleColor::BLUE), "\n";
            $sql = file_get_contents($file);
            try {
                $pdoDb->exec($sql);
                echo Output::color("Migración completada: $file", ConsoleColor::GREEN) . "\n";
            } catch (PDOException $e) {
                echo Output::color("Error en la migración $file: " . $e->getMessage(), ConsoleColor::RED) . "\n";
            }
        }
    } else {
        echo Output::color("La base de datos '$dbname' ya existe. No se ejecutaron migraciones.", ConsoleColor::YELLOW) . "\n";
    }
} catch (PDOException $e) {
    echo Output::color("Error al crear la base de datos: " . $e->getMessage(), ConsoleColor::RED) . "\n";
}