<?php

namespace App\Repository;

use App\Entity\Inventory;
use App\Utility\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Inventory>
 */
class InventoryRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Inventory::class);
        $this->paginationService = $paginationService;
    }

    /**
     * @param array $data
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    #[ArrayShape([
        'inventories' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('inventory');

        if (!empty($data['product_id'])) {
            $queryBuilder->andWhere('inventory.product = :productId')
                ->setParameter('productId', (int)$data['product_id']);
        }

        if (!empty($data['min_quantity'])) {
            $queryBuilder->andWhere('inventory.quantity >= :minQuantity')
                ->setParameter('minQuantity', (int)$data['min_quantity']);
        }

        if (!empty($data['max_quantity'])) {
            $queryBuilder->andWhere('inventory.quantity <= :maxQuantity')
                ->setParameter('maxQuantity', (int)$data['max_quantity']);
        }

        if (!empty($data['start_date'])) {
            $queryBuilder->andWhere('inventory.lastUpdated >= :startDate')
                ->setParameter('startDate', new \DateTime($data['start_date']));
        }

        if (!empty($data['end_date'])) {
            $queryBuilder->andWhere('inventory.lastUpdated <= :endDate')
                ->setParameter('endDate', new \DateTime($data['end_date']));
        }

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }

}