<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use App\Infrastructure\Persistence\Database;
use Slim\Views\Twig;

use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\Persistence\User\UserRepository;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        PDO::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $db = $settings->get('database');
            //new PDO("sqlsrv:Server=localhost;Database=testdb", "UserName", "Password");
            //$dsn = "{$db['driver']}:Server={$db['server']};Database={$db['dbname']}";
            $dsn = "{$db['driver']}:host={$db['server']};dbname={$db['dbname']}";
            return new PDO($dsn, $db['username'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        },
        Twig::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $twigSettings = $settings->get('twig');
            $twig = Twig::create($twigSettings['path'], ['cache' => $twigSettings['cache']]);
            $basePath = $settings->get('application')['base_url'];
            $appName = $settings->get('application')['name'];
            $appAuthor = $settings->get('application')['author'];
            $appVersion = $settings->get('application')['version'];
            $twig->getEnvironment()->addGlobal('basePath', $basePath);
            $twig->getEnvironment()->addGlobal('appName', $appName);
            $twig->getEnvironment()->addGlobal('appAuthor', $appAuthor);
            $twig->getEnvironment()->addGlobal('appVersion', $appVersion);
            return $twig;
        },
    ]);
};
