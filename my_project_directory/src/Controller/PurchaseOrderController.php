<?php

namespace App\Controller;

use App\Entity\PurchaseOrder;
use App\Repository\PurchaseOrderRepository;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 */
#[Route('/purchase-order', name: 'purchase_order_routes')]
class PurchaseOrderController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var PurchaseOrderRepository
     */
    private PurchaseOrderRepository $purchaseOrderRepository;
    /**
     * @var SupplierRepository
     */
    private SupplierRepository $supplierRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param PurchaseOrderRepository $purchaseOrderRepository
     * @param SupplierRepository $supplierRepository
     */
    public function __construct(EntityManagerInterface $entityManager, PurchaseOrderRepository $purchaseOrderRepository, SupplierRepository $supplierRepository)
    {
        $this->entityManager = $entityManager;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_purchase_orders', methods: ['GET'])]
    public function getPurchaseOrders(): JsonResponse
    {
        $purchaseOrders = $this->purchaseOrderRepository->findAll();
        $data = array_map(fn(PurchaseOrder $purchaseOrder) => $purchaseOrder->jsonSerialize(), $purchaseOrders);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_purchase_order', methods: ['POST'])]
    public function createPurchaseOrder(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $supplier = $this->supplierRepository->find($data['supplierId'] ?? null);
        if (!$supplier) {
            return new JsonResponse(['message' => 'Invalid supplier.'], Response::HTTP_BAD_REQUEST);
        }

        $purchaseOrder = new PurchaseOrder();
        $purchaseOrder->setSupplier($supplier);
        $purchaseOrder->setOrderDate(new \DateTime($data['orderDate'] ?? 'now'));
        $purchaseOrder->setStatus($data['status'] ?? 'Pending');

        $this->entityManager->persist($purchaseOrder);
        $this->entityManager->flush();

        return new JsonResponse($purchaseOrder->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_purchase_order', methods: ['GET'])]
    public function getPurchaseOrder(int $id): JsonResponse
    {
        $purchaseOrder = $this->purchaseOrderRepository->find($id);

        if (!$purchaseOrder) {
            return new JsonResponse(['message' => 'Purchase order not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($purchaseOrder->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_purchase_order', methods: ['PATCH'])]
    public function updatePurchaseOrder(Request $request, int $id): JsonResponse
    {
        $purchaseOrder = $this->purchaseOrderRepository->find($id);

        if (!$purchaseOrder) {
            return new JsonResponse(['message' => 'Purchase order not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['orderDate'])) {
            $purchaseOrder->setOrderDate(new \DateTime($data['orderDate']));
        }
        if (isset($data['status'])) {
            $purchaseOrder->setStatus($data['status']);
        }
        if (isset($data['supplierId'])) {
            $supplier = $this->supplierRepository->find($data['supplierId']);
            if ($supplier) {
                $purchaseOrder->setSupplier($supplier);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse($purchaseOrder->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_purchase_order', methods: ['DELETE'])]
    public function deletePurchaseOrder(int $id): JsonResponse
    {
        $purchaseOrder = $this->purchaseOrderRepository->find($id);

        if (!$purchaseOrder) {
            return new JsonResponse(['message' => 'Purchase order not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($purchaseOrder);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Purchase order deleted successfully'], Response::HTTP_OK);
    }
}
