<?php

namespace App\Controller;

use App\Entity\OrderItem;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 */
#[Route('/order-item', name: 'order_item_routes')]
class OrderItemController extends AbstractController
{
    public const ITEMS_PER_PAGE = 2;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var OrderItemRepository
     */
    private OrderItemRepository $orderItemRepository;
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param OrderItemRepository $orderItemRepository
     * @param OrderRepository $orderRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        OrderItemRepository $orderItemRepository,
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    ) {
        $this->entityManager = $entityManager;
        $this->orderItemRepository = $orderItemRepository;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'get_order_items', methods: ['GET'])]
    public function getOrderItems(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = isset($requestData['itemsPerPage']) ? (int)$requestData['itemsPerPage'] : self::ITEMS_PER_PAGE;
        $page = isset($requestData['page']) ? (int)$requestData['page'] : 1;

        $data = $this->orderItemRepository->getAllByFilter($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'create_order_item', methods: ['POST'])]
    public function createOrderItem(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $order = $this->orderRepository->find($data['orderId'] ?? null);
        $product = $this->productRepository->find($data['productId'] ?? null);

        if (!$order || !$product) {
            return new JsonResponse(['message' => 'Invalid order or product.'], Response::HTTP_BAD_REQUEST);
        }

        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setProduct($product);
        $orderItem->setQuantity($data['quantity'] ?? 1);
        $orderItem->setPricePerUnit($data['pricePerUnit'] ?? 0);

        $this->entityManager->persist($orderItem);
        $this->entityManager->flush();

        return new JsonResponse($orderItem->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_order_item', methods: ['GET'])]
    public function getOrderItem(int $id): JsonResponse
    {
        $orderItem = $this->orderItemRepository->find($id);

        if (!$orderItem) {
            return new JsonResponse(['message' => 'Order item not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($orderItem->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update_order_item', methods: ['PATCH'])]
    public function updateOrderItem(Request $request, int $id): JsonResponse
    {
        $orderItem = $this->orderItemRepository->find($id);

        if (!$orderItem) {
            return new JsonResponse(['message' => 'Order item not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['quantity'])) {
            $orderItem->setQuantity($data['quantity']);
        }
        if (isset($data['pricePerUnit'])) {
            $orderItem->setPricePerUnit($data['pricePerUnit']);
        }
        if (isset($data['orderId'])) {
            $order = $this->orderRepository->find($data['orderId']);
            if ($order) {
                $orderItem->setOrder($order);
            }
        }
        if (isset($data['productId'])) {
            $product = $this->productRepository->find($data['productId']);
            if ($product) {
                $orderItem->setProduct($product);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse($orderItem->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_order_item', methods: ['DELETE'])]
    public function deleteOrderItem(int $id): JsonResponse
    {
        $orderItem = $this->orderItemRepository->find($id);

        if (!$orderItem) {
            return new JsonResponse(['message' => 'Order item not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($orderItem);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Order item deleted successfully'], Response::HTTP_OK);
    }
}
