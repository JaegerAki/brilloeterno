<?php
declare(strict_types=1);
namespace App\Application\Actions\Admin\Users;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Domain\Admin\Users\UserRepositoryInterface;
class UserCreateAction
{
    private Twig $twig;
    private UserRepositoryInterface $userRepository;

    public function __construct(Twig $twig, UserRepositoryInterface $userRepository)
    {
        $this->twig = $twig;
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            return $this->twig->render($response, 'admin/users/create.twig', [
                'title' => 'Create User',
                'description' => 'Create a new user in the system.',
            ]);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'An error occurred while rendering the create user page: ' . $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}