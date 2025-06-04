<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;
use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

return function (ContainerBuilder $containerBuilder) {
    

    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError' => true,
                'logErrorDetails' => true,
                'logger' => [
                    'name' => 'Brillo Eterno',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'application' => [
                    'name' => 'Brillo Eterno',
                    'version' => '1.0.0',
                    'description' => 'A simple application for managing tasks.',
                    'author' => 'Akira Naganoma',
                    'author_email' => 'naganoma15@gmail.com',
                    'author_url' => '',
                    'base_url' => '/brilloeterno',
                    'timezone' => 'America/Lima',
                    'locale' => 'es_PE',
                    'charset' => 'UTF-8',
                    'picture_path' => '/brilloeterno/public/assets/img/_tmp',
                ],
                'database' => [
                    'driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
                    'server' => $_ENV['DB_HOST'] ?? 'localhost',
                    'dbname' => $_ENV['DB_NAME'] ?? 'brilloeterno',
                    'username' => $_ENV['DB_USER'] ?? 'root',
                    'password' => $_ENV['DB_PASS'] ?? '',
                ],
                'email' => [
                    'host' => 'smtp.example.com',
                    'port' => 587,
                    'username' => '',
                    'password' => '',
                    'encryption' => 'tls',
                ],
                'twig' => [
                    'path' => __DIR__ . '/../templates',
                    'cache' => false,
                    'debug' => true,
                ],
            ]);
        }
    ]);
};
