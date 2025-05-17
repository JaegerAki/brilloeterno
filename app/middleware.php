<?php

declare(strict_types=1);


use Slim\App;

use App\Application\Middleware\SessionMiddleware;
use App\Application\Middleware\AuthMiddleware;
use App\Application\Middleware\UserSessionMiddleware;

return function (App $app) {
    //$app->add(UserSessionMiddleware::class);
    //$app->add(SessionMiddleware::class);
    //$app->add(AuthMiddleware::class);
};
