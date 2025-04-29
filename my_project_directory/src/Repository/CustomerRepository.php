<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Utility\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Customer>
 */
class CustomerRepository extends ServiceEntityRepository
{
    /**
     * @var PaginationService
     */
    private PaginationService $paginationService;

    /**
     * @param ManagerRegistry $registry
     * @param PaginationService $paginationService
     */
    public function __construct(ManagerRegistry   $registry,
                                PaginationService $paginationService)
    {
        parent::__construct($registry, Customer::class);
        $this->paginationService = $paginationService;
    }

    /**
     * @param array $data
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    #[ArrayShape([
        'customers' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('customer');

        if (!empty($data['name'])) {
            $queryBuilder->andWhere('customer.name LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%');
        }

        if (!empty($data['email'])) {
            $queryBuilder->andWhere('customer.email LIKE :email')
                ->setParameter('email', '%' . $data['email'] . '%');
        }

        if (!empty($data['phone'])) {
            $queryBuilder->andWhere('customer.phone LIKE :phone')
                ->setParameter('phone', '%' . $data['phone'] . '%');
        }

        if (!empty($data['address'])) {
            $queryBuilder->andWhere('customer.address LIKE :address')
                ->setParameter('address', '%' . $data['address'] . '%');
        }

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }

}