<?php
declare(strict_types=1);
use App\Domain\Cart\Cart;
use DI\ContainerBuilder;

use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\Persistence\User\UserRepository;

use App\Domain\Product\ProductRepositoryInterface;
use App\Infrastructure\Persistence\Product\ProductRepository;

use App\Domain\Cart\CartRepositoryInterface;
use App\Infrastructure\Persistence\Cart\CartRepository;
use App\Infrastructure\Persistence\Cart\SessionCartRepository;

use App\Domain\Store\StoreRepositoryInterface;
use App\Infrastructure\Persistence\Store\StoreRepository;

use App\Domain\Customer\CustomerRepositoryInterface;
use App\Infrastructure\Persistence\Customer\CustomerRepository;

use App\Application\Service\CartManager;

use App\Domain\Admin\Dashboard\DashboardRepositoryInterface;
use App\Infrastructure\Persistence\Admin\Dashboard\DashboardRepository;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        CustomerRepositoryInterface::class => fn($c) => new CustomerRepository($c->get(PDO::class)),
        UserRepositoryInterface::class => fn($c) => new UserRepository($c->get(PDO::class)),
        ProductRepositoryInterface::class => fn($c) => new ProductRepository($c->get(PDO::class)),
        StoreRepositoryInterface::class => fn($c): StoreRepository => new StoreRepository($c->get(PDO::class)),
        CartManager::class => fn($c) => new CartManager(
            new CartRepository($c->get(PDO::class)),
            new SessionCartRepository($c->get(PDO::class))
        ),
        DashboardRepositoryInterface::class => fn($c) => new DashboardRepository($c->get(PDO::class)),
    ]);
};
