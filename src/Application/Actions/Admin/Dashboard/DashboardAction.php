<?php
declare(strict_types=1);
namespace App\Application\Actions\Admin\Dashboard;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Admin\Dashboard\DashboardRepositoryInterface;
use Slim\Views\Twig;
class DashboardAction
{
    private Twig $twig;
    private DashboardRepositoryInterface $dashboardRepository;

    public function __construct(Twig $twig,DashboardRepositoryInterface $dashboardRepository)
    {
        $this->twig = $twig;
        $this->dashboardRepository = $dashboardRepository;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        // Render the dashboard view
        return $this->twig->render($response, 'admin/dashboard.twig', [
            'title' => 'Admin Dashboard',
            'message' => 'Welcome to the Admin Dashboard!',
            'dashboardData' => $this->dashboardRepository->getDashboardData(1)//$request->getAttribute('userId')
        ]);
    }
}