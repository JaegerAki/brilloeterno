<?php
declare(strict_types=1);
namespace App\Application\Actions\Admin\Users;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Domain\Admin\Users\UserRepositoryInterface;
class UserReadAction
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
            $userId = (int)$args['id'];
            $user = $this->userRepository->findById($userId);
            if (!$user) {
                $response->getBody()->write(json_encode([
                    'error' => 'User not found'
                ]));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }
            return $this->twig->render($response, 'admin/users/read.twig', [
                'title' => 'User Details',
                'description' => 'Details of the user with ID: ' . $userId,
                'user' => $user,
            ]);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'An error occurred while fetching user details: ' . $e->getMessage()
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}