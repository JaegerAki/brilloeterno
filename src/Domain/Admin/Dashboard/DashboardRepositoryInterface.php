<?php
declare(strict_types=1);
namespace App\Domain\Admin\Dashboard;

interface DashboardRepositoryInterface
{
    public function getDashboardData(int $idUser): Dashboard;
}