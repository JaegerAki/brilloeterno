<?php
declare(strict_types=1);
namespace App\Domain\Admin\Dashboard;
use App\Domain\Admin\Dashboard\ValueObject\MenuOption;
use App\Domain\Admin\Dashboard\ValueObject\UserInfo;
use JsonSerializable;
use IteratorAggregate;
class Dashboard implements JsonSerializable, IteratorAggregate
{
    private array $menuOptions;
    private UserInfo $userInfo;

    public function __construct(UserInfo $userInfo, array $menuOptions = [] )
    {
        $this->menuOptions = $menuOptions;
        $this->userInfo = $userInfo;
    }

    public function getMenuOptions(): array
    {
        return $this->menuOptions;
    }

    public function getUserInfo(): UserInfo
    {
        return $this->userInfo;
    }
    
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->menuOptions);
    }

    public function jsonSerialize(): array
    {
        return [
            'menuOptions' => array_map(
                fn(MenuOption $option) => $option->jsonSerialize(),
                $this->menuOptions
            ),
            'userInfo' => $this->userInfo->jsonSerialize()
        ];
    }
}