<?php

namespace App\Repository;

use App\Entity\Supplier;
use App\Utility\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Supplier>
 */
class SupplierRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Supplier::class);
        $this->paginationService = $paginationService;
    }

    /**
     * @param array $data
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    #[ArrayShape([
        'suppliers' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('supplier');

        if (!empty($data['name'])) {
            $queryBuilder->andWhere('supplier.name LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%');
        }

        if (!empty($data['contact_name'])) {
            $queryBuilder->andWhere('supplier.contactName LIKE :contactName')
                ->setParameter('contactName', '%' . $data['contact_name'] . '%');
        }

        if (!empty($data['contact_phone'])) {
            $queryBuilder->andWhere('supplier.contactPhone LIKE :contactPhone')
                ->setParameter('contactPhone', '%' . $data['contact_phone'] . '%');
        }

        if (!empty($data['contact_email'])) {
            $queryBuilder->andWhere('supplier.contactEmail LIKE :contactEmail')
                ->setParameter('contactEmail', '%' . $data['contact_email'] . '%');
        }

        if (!empty($data['address'])) {
            $queryBuilder->andWhere('supplier.address LIKE :address')
                ->setParameter('address', '%' . $data['address'] . '%');
        }

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }
}