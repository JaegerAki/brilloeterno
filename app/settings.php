<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

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
                    'driver' => 'mysql',
                    'server' => 'localhost',
                    'dbname' => 'brilloeterno',
                    'username' => 'root',
                    'password' => '',
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
                ],
            ]);
        }
    ]);
};
