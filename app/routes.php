<?php

declare(strict_types=1);


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\Customer\ListCustomersAction;

use App\Application\Actions\Home\HomeAction;
use App\Application\Actions\Store\StoreAction;
use App\Application\Actions\Store\DetailStoreAction;
use App\Application\Actions\Cart\ViewCartAction;
use App\Application\Actions\Cart\AddCartAction;
use App\Application\Actions\Cart\CheckoutAction;
use App\Application\Actions\Auth\LoginAction;
use App\Application\Actions\Auth\RegisterAction;
use App\Application\Actions\Auth\LogoutAction;
use App\Application\Actions\Auth\ChangePasswordAction;
use App\Application\Actions\About\AboutAction;

use App\Application\Middleware\AuthMiddleware;
use App\Application\Middleware\SessionMiddleware;

return function (App $app) {
    /*$app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });*/

    $app->get('/', HomeAction::class);
    $app->get('/about', AboutAction::class);

    $app->group('/store', function (Group $group) {
        $group->get('', StoreAction::class);
        $group->get('/{id}', DetailStoreAction::class);
    });

    $app->group('/cart', function (Group $group) {
        $group->get('', ViewCartAction::class);
        $group->post('/add', [AddCartAction::class, 'agregar']);
        $group->get('/checkout', CheckoutAction::class);//->add(new AuthMiddleware());
    });

    $app->group('/auth', function (Group $group) {
        $group->map(['GET', 'POST'], '/login', LoginAction::class);
        $group->map(['GET', 'POST'], '/register', RegisterAction::class);
        $group->map(['GET', 'POST'], '/change-password', ChangePasswordAction::class);
        $group->map(['GET', 'POST'], '/logout', LogoutAction::class);
    });
};
