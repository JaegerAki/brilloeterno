<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class RegisterAction
{
    private Twig $twig;
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(Twig $twig, CustomerRepositoryInterface $customerRepository)
    {
        $this->twig = $twig;
        $this->customerRepository = $customerRepository;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];
        $error = null;

        if ($request->getMethod() === 'POST') {
            $email = trim($data['email'] ?? '');
            $password = trim($data['password']) ?? '';
            $confirm = trim($data['confirm_password']) ?? '';
            $firstName = trim($data['firstname'] ?? '');
            $lastName = trim($data['lastname'] ?? '');

            if ($email === '' || $password === '' || $confirm === '') {
                $error = 'Todos los campos son obligatorios.';
            } elseif ($password !== $confirm) {
                $error = 'Las contraseñas no coinciden.';
            } elseif ($this->customerRepository->findByEmail($email)) {
                $error = 'Ya esta en uso este correo.';
            } else {
                //$passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $user = new Customer(null, $email, ucfirst(strtolower($firstName)) . ' ' . ucfirst(strtolower($lastName)), $password);
                if ($this->customerRepository->save($user)) {
                    // Registration successful, redirect to login or home page
                    return $this->twig->render($response, 'auth/login.twig', [
                        'message' => 'Registrado correctamente. Vuelve a iniciar sesión.',
                    ]);
                } else {
                    $error = 'failed to register user.';
                }
            }
        }

        return $this->twig->render($response, 'auth/register.twig', [
            'error' => $error,
        ]);
    }
}