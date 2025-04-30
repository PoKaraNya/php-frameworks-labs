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
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 *
 */
#[Route('api/purchase-order', name: 'purchase_order_routes')]
class PurchaseOrderController extends AbstractController
{
    public const ITEMS_PER_PAGE = 2;

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
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'get_purchase_orders', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function getPurchaseOrders(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = isset($requestData['itemsPerPage']) ? (int)$requestData['itemsPerPage'] : self::ITEMS_PER_PAGE;
        $page = isset($requestData['page']) ? (int)$requestData['page'] : 1;

        $data = $this->purchaseOrderRepository->getAllByFilter($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_purchase_order', methods: ['POST'])]
    #[IsGranted("ROLE_MANAGER")]
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
    #[IsGranted("ROLE_USER")]
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
    #[IsGranted("ROLE_MANAGER")]
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
    #[IsGranted("ROLE_ADMIN")]
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
