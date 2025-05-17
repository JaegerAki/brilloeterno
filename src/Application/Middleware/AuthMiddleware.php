<?php
namespace App\Application\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
class AuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   print_r($_SESSION);
        if (empty($_SESSION['user_id'])) {
            $response = new \Slim\Psr7\Response();
            return $response
                ->withHeader('Location', 'auth/login')
                ->withStatus(302);
        }
        return $handler->handle($request);
    }
}