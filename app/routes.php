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
use App\Application\Actions\Cart\AddCartItemAction;
use App\Application\Actions\Cart\RemoveCartItemAction;
use App\Application\Actions\Cart\IncreaseCartItemAction;
use App\Application\Actions\Cart\DecreaseCartItemAction;

use App\Application\Actions\Auth\LoginAction;
use App\Application\Actions\Auth\RegisterAction;
use App\Application\Actions\Auth\LogoutAction;
use App\Application\Actions\Auth\ChangePasswordAction;

use App\Application\Actions\About\AboutAction;

use App\Application\Middleware\AuthMiddleware;
use App\Application\Middleware\SessionMiddleware;

use App\Application\Actions\Admin\Dashboard\DashboardAction;

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
    });

    $app->group('/auth', function (Group $group) {
        $group->get('', LoginAction::class);
        $group->map(['GET', 'POST'], '/login', LoginAction::class);
        $group->map(['GET', 'POST'], '/register', RegisterAction::class);
        $group->map(['GET', 'POST'], '/change-password', ChangePasswordAction::class);
        $group->map(['GET', 'POST'], '/logout', LogoutAction::class);
    });

    $app->group('/admin', function (Group $group) {
        $group->group('/auth', function (Group $auth) {
            $auth->map(['GET', 'POST'], '', LoginAction::class);
            $auth->map(['GET', 'POST'], '/login', LoginAction::class);
            $auth->map(['GET', 'POST'], '/logout', LogoutAction::class);
            $auth->map(['GET', 'POST'], '/change-password', ChangePasswordAction::class);
        });
        $group->get('', DashboardAction::class);
        $group->get('/', DashboardAction::class);

    })->add(new SessionMiddleware());

    $app->group('/api', function (Group $group) {
        $group->group('/cart', function (Group $cart) {
            $cart->post('/add', AddCartItemAction::class);
            $cart->post('/remove', RemoveCartItemAction::class);
            $cart->post('/increase', IncreaseCartItemAction::class);
            $cart->post('/decrease', DecreaseCartItemAction::class);
        });

        $group->group('/admin', function (Group $admin) {
            //$admin->post('', DashboardAction::class);
        });
    })->add(new AuthMiddleware());
};
