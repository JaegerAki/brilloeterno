<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Domain\Customer\CustomerRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class LoginAction
{
    private Twig $twig;
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(Twig $twig, CustomerRepositoryInterface $customerRepository)
    {
        $this->twig = $twig;
        $this->customerRepository = $customerRepository;
    }

    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        $data = $request->getParsedBody() ?? [];
        $error = null;

        if ($request->getMethod() === 'POST') {
            $username = trim($data['username'] ?? '');
            $password = $data['password'] ?? '';

            $customer = $this->customerRepository->findByEmail($username);

            //if (!$customer || !password_verify($password, $customer->getPasswordHash())) {
            if (!$customer || $customer->getPasswordHash() !== $password) {
                $error = 'Correo o contraseña incorrectos';
            } else {
                $_SESSION['user_id'] = $customer->getId();
                $_SESSION['user_name'] = $customer->getFullname();
                $_SESSION['user_email'] = $customer->getEmail();

                //$_SESSION['flash_message']? $_SESSION['flash_message']:'Bienvenido ' . $customer->getFullname() . '.';

                $baseUrl = $this->twig->getEnvironment()->getGlobals()['basePath'] ?? '/brilloeterno';
                // Redirect to the home
                return $response
                    ->withHeader('Location', $baseUrl . '/')
                    ->withStatus(302);
                /*return $this->twig->render($response, 'home.twig', [
                    'user' => $customer,
                ]);*/
            }
        }

        return $this->twig->render($response, 'auth/login.twig', [
            'error' => $error,
        ]);
    }
}