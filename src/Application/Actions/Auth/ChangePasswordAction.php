<?php
declare(strict_types=1);
namespace App\Application\Actions\Auth;
use App\Domain\Customer\CustomerRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Views\Twig;

class ChangePasswordAction
{
    private Twig $twig;

    public function __construct(Twig $twig, CustomerRepositoryInterface $customerRepository)
    {
        $this->twig = $twig;
        $this->customerRepository = $customerRepository;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        session_start();
        $data = $request->getParsedBody() ?? [];
        $error = null;

        if ($request->getMethod() === 'POST') {
            
            $currentPassword = trim($data['current_password'] ?? '');
            $newPassword = trim($data['new_password'] ?? '');
            $confirmPassword = trim($data['confirm_password'] ?? '');

            if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
                $error = 'Todos los campos son obligatorios';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'Las contraseñas no coinciden';
            } else {
                
            }
        }

        return $this->twig->render($response, 'auth/change_password.twig', [
            'error' => $error,
        ]);
    }
}