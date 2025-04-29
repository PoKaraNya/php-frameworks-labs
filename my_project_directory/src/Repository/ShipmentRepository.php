<?php

namespace App\Repository;

use App\Entity\Shipment;
use App\Utility\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Shipment>
 */
class ShipmentRepository extends ServiceEntityRepository
{
    /**
     * @var PaginationService
     */
    private PaginationService $paginationService;

    /**
     * @param ManagerRegistry $registry
     * @param PaginationService $paginationService
     */
    public function __construct(
        ManagerRegistry   $registry,
        PaginationService $paginationService)
    {
        parent::__construct($registry, Shipment::class);
        $this->paginationService = $paginationService;
    }

    /**
     * @param array $data
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    #[ArrayShape([
        'shipments' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('shipment');
        if (!empty($data['order_id'])) {
            $queryBuilder->andWhere('shipment.order = :orderId')
                ->setParameter('orderId', (int)$data['order_id']);
        }

        if (!empty($data['status'])) {
            $queryBuilder->andWhere('shipment.status = :status')
                ->setParameter('status', $data['status']);
        }

        if (!empty($data['min_shipment_date'])) {
            $queryBuilder->andWhere('shipment.shipmentDate >= :minShipmentDate')
                ->setParameter('minShipmentDate', $data['min_shipment_date']);
        }

        if (!empty($data['max_shipment_date'])) {
            $queryBuilder->andWhere('shipment.shipmentDate <= :maxShipmentDate')
                ->setParameter('maxShipmentDate', $data['max_shipment_date']);
        }

        if (!empty($data['min_delivery_date'])) {
            $queryBuilder->andWhere('shipment.deliveryDate >= :minDeliveryDate')
                ->setParameter('minDeliveryDate', $data['min_delivery_date']);
        }

        if (!empty($data['max_delivery_date'])) {
            $queryBuilder->andWhere('shipment.deliveryDate <= :maxDeliveryDate')
                ->setParameter('maxDeliveryDate', $data['max_delivery_date']);
        }

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }

}