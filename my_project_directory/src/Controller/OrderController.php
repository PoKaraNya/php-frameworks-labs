<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/order', name: 'order_routes')]
class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private OrderRepository $orderRepository;
    private CustomerRepository $customerRepository;

    public function __construct(EntityManagerInterface $entityManager, OrderRepository $orderRepository, CustomerRepository $customerRepository)
    {
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
    }

    #[Route('/', name: 'get_orders', methods: ['GET'])]
    public function getOrders(): JsonResponse
    {
        $orders = $this->orderRepository->findAll();
        $data = array_map(fn(Order $order) => $order->jsonSerialize(), $orders);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_order', methods: ['POST'])]
    public function createOrder(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $customer = $this->customerRepository->find($data['customerId'] ?? null);
        if (!$customer) {
            return new JsonResponse(['message' => 'Invalid customer.'], Response::HTTP_BAD_REQUEST);
        }

        $order = new Order();
        $order->setCustomer($customer);
        $order->setOrderDate(new \DateTime($data['orderDate'] ?? 'now'));
        $order->setStatus($data['status'] ?? 'Pending');

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return new JsonResponse($order->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'get_order', methods: ['GET'])]
    public function getOrder(int $id): JsonResponse
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return new JsonResponse(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($order->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'update_order', methods: ['PATCH'])]
    public function updateOrder(Request $request, int $id): JsonResponse
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return new JsonResponse(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['status'])) {
            $order->setStatus($data['status']);
        }

        if (isset($data['orderDate'])) {
            $order->setOrderDate(new \DateTime($data['orderDate']));
        }

        if (isset($data['customerId'])) {
            $customer = $this->customerRepository->find($data['customerId']);
            if ($customer) {
                $order->setCustomer($customer);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse($order->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_order', methods: ['DELETE'])]
    public function deleteOrder(int $id): JsonResponse
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return new JsonResponse(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Order deleted successfully'], Response::HTTP_OK);
    }
}
