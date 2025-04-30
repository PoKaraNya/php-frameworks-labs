<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 *
 */
#[Route('api/customer', name: 'customer_routes')]
class CustomerController extends AbstractController
{
    public const ITEMS_PER_PAGE = 2;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param CustomerRepository $customerRepository
     */
    public function __construct(EntityManagerInterface $entityManager, CustomerRepository $customerRepository)
    {
        $this->entityManager = $entityManager;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[IsGranted("ROLE_USER")]
    #[Route('/', name: 'get_customers', methods: ['GET'])]
    public function getCustomers(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = isset($requestData['itemsPerPage']) ? (int)$requestData['itemsPerPage'] : self::ITEMS_PER_PAGE;
        $page = isset($requestData['page']) ? (int)$requestData['page'] : 1;

        $data = $this->customerRepository->getAllByFilter($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[IsGranted("ROLE_MANAGER")]
    #[Route('/', name: 'create_customer', methods: ['POST'])]
    public function createCustomer(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $customer = new Customer();
        $customer->setName($data['name'] ?? '');
        $customer->setEmail($data['email'] ?? '');
        $customer->setPhone($data['phone'] ?? '');
        $customer->setAddress($data['address'] ?? '');

        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return new JsonResponse($customer->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[IsGranted("ROLE_USER")]
    #[Route('/{id}', name: 'get_customer', methods: ['GET'])]
    public function getCustomer(int $id): JsonResponse
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return new JsonResponse(['message' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($customer->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    #[IsGranted("ROLE_MANAGER")]
    #[Route('/{id}', name: 'update_customer', methods: ['PATCH'])]
    public function updateCustomer(Request $request, int $id): JsonResponse
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return new JsonResponse(['message' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $customer->setName($data['name']);
        }
        if (isset($data['email'])) {
            $customer->setEmail($data['email']);
        }
        if (isset($data['phone'])) {
            $customer->setPhone($data['phone']);
        }
        if (isset($data['address'])) {
            $customer->setAddress($data['address']);
        }

        $this->entityManager->flush();

        return new JsonResponse($customer->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/{id}', name: 'delete_customer', methods: ['DELETE'])]
    public function deleteCustomer(int $id): JsonResponse
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return new JsonResponse(['message' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($customer);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Customer deleted successfully'], Response::HTTP_OK);
    }
}