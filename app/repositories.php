<?php
declare(strict_types=1);
use DI\ContainerBuilder;

use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\Persistence\User\UserRepository;

use App\Domain\Product\ProductRepositoryInterface;
use App\Infrastructure\Persistence\Product\ProductRepository;

use App\Infrastructure\Persistence\Cart\CartRepository;
use App\Infrastructure\Persistence\Cart\SessionCartRepository;

use App\Domain\Store\StoreRepositoryInterface;
use App\Infrastructure\Persistence\Store\StoreRepository;

use App\Domain\Customer\CustomerRepositoryInterface;
use App\Infrastructure\Persistence\Customer\CustomerRepository;

use App\Application\Service\CartManager;

use App\Domain\Admin\Dashboard\DashboardRepositoryInterface;
use App\Infrastructure\Persistence\Admin\Dashboard\DashboardRepository;

use App\Domain\Admin\Inventory\InventoryRepositoryInterface;
use App\Infrastructure\Persistence\Admin\Inventory\InventoryRepository;

use App\Domain\Admin\Users\UserRepositoryInterface as AdminUserRepositoryInterface;
use App\Infrastructure\Persistence\Admin\Users\UserRepository as AdminUserRepository;

use App\Domain\Admin\Categories\CategoryRepositoryInterface;
use App\Infrastructure\Persistence\Admin\Categories\CategoryRepository;

use App\Domain\Admin\Customers\CustomerRepositoryInterface as AdminCustomerRepositoryInterface;
use App\Infrastructure\Persistence\Admin\Customers\CustomerRepository as AdminCustomerRepository;

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
        InventoryRepositoryInterface::class => fn($c) => new InventoryRepository($c->get(PDO::class)),
        AdminUserRepositoryInterface::class => fn($c) => new AdminUserRepository($c->get(PDO::class)),
        CategoryRepositoryInterface::class => fn($c) => new CategoryRepository($c->get(PDO::class)),
        AdminCustomerRepositoryInterface::class => fn($c) => new AdminCustomerRepository($c->get(PDO::class))
    ]);
};
