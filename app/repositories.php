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

use App\Domain\Store\StoreRepositoryInterface;
use App\Infrastructure\Persistence\Store\StoreRepository;

use App\Domain\Customer\CustomerRepositoryInterface;
use App\Infrastructure\Persistence\Customer\CustomerRepository;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        CustomerRepositoryInterface::class => fn($c) => new CustomerRepository($c->get(PDO::class)),
        UserRepositoryInterface::class => fn($c) => new UserRepository($c->get(PDO::class)),
        ProductRepositoryInterface::class => fn($c) => new ProductRepository($c->get(PDO::class)),
        CartRepositoryInterface::class => fn($c) => new CartRepository($c->get(PDO::class)),
        StoreRepositoryInterface::class => fn($c) => new StoreRepository($c->get(PDO::class)),
    ]);
};
