<?php
namespace App\Application\Actions\Admin\Customers;
use App\Domain\Admin\Customers\CustomerRepositoryInterface as AdminCustomerRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class CustomerAction
{
    private AdminCustomerRepositoryInterface $customerRepository;
    private Twig $twig;

    public function __construct(Twig $twig, AdminCustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $customer = $this->customerRepository->findAll();

        return $this->twig->render($response, 'admin/customers/list.twig', [
            'title' => 'Clientes',
            'customers' => $customer
        ]);
    }
}