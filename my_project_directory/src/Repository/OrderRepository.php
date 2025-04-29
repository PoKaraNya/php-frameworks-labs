<?php

namespace App\Repository;

use App\Entity\Order;
use App\Utility\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Order::class);
        $this->paginationService = $paginationService;
    }

    /**
     * @param array $data
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    #[ArrayShape([
        'orders' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('orders');

        if (!empty($data['customer_id'])) {
            $queryBuilder->andWhere('orders.customer = :customerId')
                ->setParameter('customerId', (int)$data['customer_id']);
        }

        if (!empty($data['start_date'])) {
            $queryBuilder->andWhere('orders.orderDate >= :startDate')
                ->setParameter('startDate', new \DateTime($data['start_date']));
        }

        if (!empty($data['end_date'])) {
            $queryBuilder->andWhere('orders.orderDate <= :endDate')
                ->setParameter('endDate', new \DateTime($data['end_date']));
        }

        if (!empty($data['status'])) {
            $queryBuilder->andWhere('orders.status = :status')
                ->setParameter('status', $data['status']);
        }

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }

}