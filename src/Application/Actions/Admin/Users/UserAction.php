<?php
declare(strict_types=1);
namespace App\Application\Actions\Admin\Users;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use App\Domain\Admin\Users\UserRepositoryInterface;

class UserAction
{
    private Twig $twig;
    private UserRepositoryInterface $userRepository;

    public function __construct(Twig $twig, UserRepositoryInterface $userRepository)
    {
        $this->twig = $twig;
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        try {
            $users = $this->userRepository->findAll();

            return $this->twig->render($response, 'admin/users/list.twig', [
                'title' => 'Usuarios',
                'description' => 'Lista de usuarios registrados en el sistema.',
                'users' => $users,
            ]);
            
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'An error occurred while fetching users: ' . $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}
