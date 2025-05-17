<?php
// filepath: c:\xampp\htdocs\brilloeterno\src\Application\Middleware\UserSessionMiddleware.php
namespace App\Application\Middleware;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\App;
use Slim\Views\Twig;

class UserSessionMiddleware implements Middleware
{
    /**
     * {@inheritdoc}
     */
    private $twig;
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }
    public function process(Request $request, RequestHandler $handler): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = null;
        if (isset($_SESSION['user_id'])) {
            $user = [
                'user_id' => $_SESSION['user_id'],
                'user_name' => $_SESSION['user_name'] ?? null,
                'user_email' => $_SESSION['user_email'] ?? null,
            ];
        }
        // Agrega el usuario como variable global en Twig
        $this->twig->getEnvironment()->addGlobal('session', $user);
        return $handler->handle($request);
    }
}
