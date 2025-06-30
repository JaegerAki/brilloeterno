<?php

declare(strict_types=1);

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

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

use App\Application\Actions\Admin\Inventory\InventoryAction;
use App\Application\Actions\Admin\Inventory\InventoryReadAction;
use App\Application\Actions\Admin\Inventory\InventoryCreateAction;
use App\Application\Actions\Admin\Inventory\InventoryEditAction;
use App\Application\Actions\Admin\Inventory\InventoryDeleteAction;

use App\Application\Actions\Admin\Users\UserAction as AdminUserAction;
use App\Application\Actions\Admin\Users\UserReadAction as AdminUserReadAction;

use App\Application\Actions\Admin\Categories\CategoryAction;
use App\Application\Actions\Admin\Categories\CategoryCrudAction;

use App\Application\Actions\Admin\Customers\CustomerAction;

return function (App $app) {

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
        $group->group('/inventory', function (Group $inventory) {
            $inventory->get('', InventoryAction::class);
            $inventory->map(['GET', 'POST'], '/create', InventoryCreateAction::class);
            $inventory->map(['GET', 'POST'], '/delete/{id}', InventoryDeleteAction::class);
            $inventory->map(['GET', 'POST'], '/edit/{id}', InventoryEditAction::class);
            $inventory->get('/{id}', InventoryReadAction::class);
        });

        $group->group('/users', function (Group $users) {
            $users->get('', AdminUserAction::class);
            $users->get('/{id}', AdminUserReadAction::class);
        });
        
        $group->group('/categories', function (Group $categories) {
            $categories->get('', CategoryAction::class);
            $categories->map(['GET', 'POST'], '/crud', CategoryCrudAction::class);
            $categories->map(['GET'], '/crud/{id}', CategoryCrudAction::class);
        });
        $group->group('/customers', function (Group $customers) {
            $customers->get('', CustomerAction::class);
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
    })->add(new AuthMiddleware());
};
