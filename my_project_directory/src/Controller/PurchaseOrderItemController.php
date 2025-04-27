<?php

namespace App\Controller;

use App\Entity\PurchaseOrderItem;
use App\Repository\PurchaseOrderItemRepository;
use App\Repository\PurchaseOrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/purchase-order-item', name: 'purchase_order_item_routes')]
class PurchaseOrderItemController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PurchaseOrderItemRepository $purchaseOrderItemRepository;
    private PurchaseOrderRepository $purchaseOrderRepository;
    private ProductRepository $productRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PurchaseOrderItemRepository $purchaseOrderItemRepository,
        PurchaseOrderRepository $purchaseOrderRepository,
        ProductRepository $productRepository
    ) {
        $this->entityManager = $entityManager;
        $this->purchaseOrderItemRepository = $purchaseOrderItemRepository;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->productRepository = $productRepository;
    }

    #[Route('/', name: 'get_purchase_order_items', methods: ['GET'])]
    public function getPurchaseOrderItems(): JsonResponse
    {
        $purchaseOrderItems = $this->purchaseOrderItemRepository->findAll();
        $data = array_map(fn(PurchaseOrderItem $item) => $item->jsonSerialize(), $purchaseOrderItems);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_purchase_order_item', methods: ['POST'])]
    public function createPurchaseOrderItem(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $purchaseOrder = $this->purchaseOrderRepository->find($data['purchaseOrderId'] ?? null);
        $product = $this->productRepository->find($data['productId'] ?? null);

        if (!$purchaseOrder || !$product) {
            return new JsonResponse(['message' => 'Invalid purchase order or product.'], Response::HTTP_BAD_REQUEST);
        }

        $item = new PurchaseOrderItem();
        $item->setPurchaseOrder($purchaseOrder);
        $item->setProduct($product);
        $item->setQuantity($data['quantity'] ?? 1);
        $item->setPricePerUnit($data['pricePerUnit'] ?? 0);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return new JsonResponse($item->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'get_purchase_order_item', methods: ['GET'])]
    public function getPurchaseOrderItem(int $id): JsonResponse
    {
        $item = $this->purchaseOrderItemRepository->find($id);

        if (!$item) {
            return new JsonResponse(['message' => 'Purchase order item not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($item->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'update_purchase_order_item', methods: ['PATCH'])]
    public function updatePurchaseOrderItem(Request $request, int $id): JsonResponse
    {
        $item = $this->purchaseOrderItemRepository->find($id);

        if (!$item) {
            return new JsonResponse(['message' => 'Purchase order item not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['quantity'])) {
            $item->setQuantity($data['quantity']);
        }
        if (isset($data['pricePerUnit'])) {
            $item->setPricePerUnit($data['pricePerUnit']);
        }
        if (isset($data['purchaseOrderId'])) {
            $purchaseOrder = $this->purchaseOrderRepository->find($data['purchaseOrderId']);
            if ($purchaseOrder) {
                $item->setPurchaseOrder($purchaseOrder);
            }
        }
        if (isset($data['productId'])) {
            $product = $this->productRepository->find($data['productId']);
            if ($product) {
                $item->setProduct($product);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse($item->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_purchase_order_item', methods: ['DELETE'])]
    public function deletePurchaseOrderItem(int $id): JsonResponse
    {
        $item = $this->purchaseOrderItemRepository->find($id);

        if (!$item) {
            return new JsonResponse(['message' => 'Purchase order item not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($item);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Purchase order item deleted successfully'], Response::HTTP_OK);
    }
}
