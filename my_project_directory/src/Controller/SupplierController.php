<?php

namespace App\Controller;

use App\Entity\Supplier;
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
#[Route('/supplier', name: 'supplier_routes')]
class SupplierController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var SupplierRepository
     */
    private SupplierRepository $supplierRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param SupplierRepository $supplierRepository
     */
    public function __construct(EntityManagerInterface $entityManager, SupplierRepository $supplierRepository)
    {
        $this->entityManager = $entityManager;
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_suppliers', methods: ['GET'])]
    public function getSuppliers(): JsonResponse
    {
        $suppliers = $this->supplierRepository->findAll();
        $data = array_map(fn(Supplier $supplier) => $supplier->jsonSerialize(), $suppliers);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'create_supplier', methods: ['POST'])]
    public function createSupplier(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $supplier = new Supplier();
        $supplier->setName($data['name'] ?? '');
        $supplier->setContactName($data['contactName'] ?? '');
        $supplier->setContactPhone($data['contactPhone'] ?? '');
        $supplier->setContactEmail($data['contactEmail'] ?? '');
        $supplier->setAddress($data['address'] ?? '');

        $this->entityManager->persist($supplier);
        $this->entityManager->flush();

        return new JsonResponse($supplier->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_supplier', methods: ['GET'])]
    public function getSupplier(int $id): JsonResponse
    {
        $supplier = $this->supplierRepository->find($id);

        if (!$supplier) {
            return new JsonResponse(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($supplier->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update_supplier', methods: ['PATCH'])]
    public function updateSupplier(Request $request, int $id): JsonResponse
    {
        $supplier = $this->supplierRepository->find($id);

        if (!$supplier) {
            return new JsonResponse(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $supplier->setName($data['name']);
        }
        if (isset($data['contactName'])) {
            $supplier->setContactName($data['contactName']);
        }
        if (isset($data['contactPhone'])) {
            $supplier->setContactPhone($data['contactPhone']);
        }
        if (isset($data['contactEmail'])) {
            $supplier->setContactEmail($data['contactEmail']);
        }
        if (isset($data['address'])) {
            $supplier->setAddress($data['address']);
        }

        $this->entityManager->flush();

        return new JsonResponse($supplier->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_supplier', methods: ['DELETE'])]
    public function deleteSupplier(int $id): JsonResponse
    {
        $supplier = $this->supplierRepository->find($id);

        if (!$supplier) {
            return new JsonResponse(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($supplier);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Supplier deleted successfully'], Response::HTTP_OK);
    }
}
