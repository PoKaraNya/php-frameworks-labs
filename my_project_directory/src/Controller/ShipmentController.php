<?php

namespace App\Controller;

use App\Entity\Shipment;
use App\Repository\ShipmentRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/shipment', name: 'shipment_routes')]
class ShipmentController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ShipmentRepository $shipmentRepository;
    private OrderRepository $orderRepository;

    public function __construct(EntityManagerInterface $entityManager, ShipmentRepository $shipmentRepository, OrderRepository $orderRepository)
    {
        $this->entityManager = $entityManager;
        $this->shipmentRepository = $shipmentRepository;
        $this->orderRepository = $orderRepository;
    }

    #[Route('/', name: 'get_shipments', methods: ['GET'])]
    public function getShipments(): JsonResponse
    {
        $shipments = $this->shipmentRepository->findAll();
        $data = array_map(fn(Shipment $shipment) => $shipment->jsonSerialize(), $shipments);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_shipment', methods: ['POST'])]
    public function createShipment(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $order = $this->orderRepository->find($data['orderId'] ?? null);
        if (!$order) {
            return new JsonResponse(['message' => 'Invalid order.'], Response::HTTP_BAD_REQUEST);
        }

        $shipment = new Shipment();
        $shipment->setOrder($order);
        $shipment->setShipmentDate(new \DateTime($data['shipmentDate'] ?? 'now'));
        $shipment->setDeliveryDate(new \DateTime($data['deliveryDate'] ?? 'now'));
        $shipment->setStatus($data['status'] ?? 'pending');

        $this->entityManager->persist($shipment);
        $this->entityManager->flush();

        return new JsonResponse($shipment->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'get_shipment', methods: ['GET'])]
    public function getShipment(int $id): JsonResponse
    {
        $shipment = $this->shipmentRepository->find($id);

        if (!$shipment) {
            return new JsonResponse(['message' => 'Shipment not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($shipment->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'update_shipment', methods: ['PATCH'])]
    public function updateShipment(Request $request, int $id): JsonResponse
    {
        $shipment = $this->shipmentRepository->find($id);

        if (!$shipment) {
            return new JsonResponse(['message' => 'Shipment not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['shipmentDate'])) {
            $shipment->setShipmentDate(new \DateTime($data['shipmentDate']));
        }
        if (isset($data['deliveryDate'])) {
            $shipment->setDeliveryDate(new \DateTime($data['deliveryDate']));
        }
        if (isset($data['status'])) {
            $shipment->setStatus($data['status']);
        }
        if (isset($data['orderId'])) {
            $order = $this->orderRepository->find($data['orderId']);
            if ($order) {
                $shipment->setOrder($order);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse($shipment->jsonSerialize(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_shipment', methods: ['DELETE'])]
    public function deleteShipment(int $id): JsonResponse
    {
        $shipment = $this->shipmentRepository->find($id);

        if (!$shipment) {
            return new JsonResponse(['message' => 'Shipment not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($shipment);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Shipment deleted successfully'], Response::HTTP_OK);
    }
}
