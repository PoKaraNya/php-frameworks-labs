<?php

namespace App\Controller;

use App\Entity\Inventory;
use App\Repository\InventoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/inventory', name: 'inventory_routes')]
class InventoryController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private InventoryRepository $inventoryRepository;
    private ProductRepository $productRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        InventoryRepository $inventoryRepository,
        ProductRepository $productRepository
    ) {
        $this->entityManager = $entityManager;
        $this->inventoryRepository = $inventoryRepository;
        $this->productRepository = $productRepository;
    }

    #[Route('/', name: 'get_inventories', methods: ['GET'])]
    public function getInventories(): JsonResponse
    {
        $inventories = $this->inventoryRepository->findAll();
        $data = array_map(fn(Inventory $inventory) => $inventory->jsonSerialize(), $inventories);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_inventory', methods: ['POST'])]
    public function createInventory(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $product = $this->productRepository->find($data['productId'] ?? null);
        if (!$product) {
            return new JsonResponse(['message' => 'Invalid product.'], Response::HTTP_BAD_REQUEST);
        }

        $inventory = new Inventory();
        $inventory->setProduct($product);
        $inventory->setQuantity($data['quantity'] ?? 0);
        $inventory->setLastUpdated(new \DateTime($data['lastUpdated'] ?? 'now'));

        $this->entityManager->persist($inventory);
        $this->entityManager->flush();

        return new JsonResponse($inventory->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'get_inventory', methods: ['GET'])]
    public function getInventory(int $id): JsonResponse
    {
        $inventory = $this->inventoryRepository->find($id);

        if (!$inventory) {
            return new JsonResponse(['message' => 'Inventory not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($inventory->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'update_inventory', methods: ['PATCH'])]
    public function updateInventory(Request $request, int $id): JsonResponse
    {
        $inventory = $this->inventoryRepository->find($id);

        if (!$inventory) {
            return new JsonResponse(['message' => 'Inventory not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['quantity'])) {
            $inventory->setQuantity($data['quantity']);
        }
        if (isset($data['lastUpdated'])) {
            $inventory->setLastUpdated(new \DateTime($data['lastUpdated']));
        }
        if (isset($data['productId'])) {
            $product = $this->productRepository->find($data['productId']);
            if ($product) {
                $inventory->setProduct($product);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse($inventory->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_inventory', methods: ['DELETE'])]
    public function deleteInventory(int $id): JsonResponse
    {
        $inventory = $this->inventoryRepository->find($id);

        if (!$inventory) {
            return new JsonResponse(['message' => 'Inventory not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($inventory);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Inventory deleted successfully'], Response::HTTP_OK);
    }
}
