<?php

namespace App\Repository;

use App\Entity\PurchaseOrder;
use App\Utility\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<PurchaseOrder>
 */
class PurchaseOrderRepository extends ServiceEntityRepository
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
        parent::__construct($registry, PurchaseOrder::class);
        $this->paginationService = $paginationService;
    }

    /**
     * @param array $data
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    #[ArrayShape([
        'purchaseOrders' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('purchase_order');

        if (!empty($data['supplier_id'])) {
            $queryBuilder->andWhere('purchase_order.supplier = :supplierId')
                ->setParameter('supplierId', (int)$data['supplier_id']);
        }

        if (!empty($data['start_date'])) {
            $queryBuilder->andWhere('purchase_order.orderDate >= :startDate')
                ->setParameter('startDate', new \DateTime($data['start_date']));
        }

        if (!empty($data['end_date'])) {
            $queryBuilder->andWhere('purchase_order.orderDate <= :endDate')
                ->setParameter('endDate', new \DateTime($data['end_date']));
        }

        if (!empty($data['status'])) {
            $queryBuilder->andWhere('purchase_order.status = :status')
                ->setParameter('status', $data['status']);
        }

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }
}