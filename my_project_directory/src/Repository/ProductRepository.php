<?php

namespace App\Repository;

use App\Entity\Product;
use App\Utility\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Product::class);
        $this->paginationService = $paginationService;
    }

    /**
     * @param array $data
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    #[
        ArrayShape([
            'products' => "array",
            'totalPageCount' => "float",
            'totalItems' => "int"
        ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('product');

        if (!empty($data['category_id'])) {
            $queryBuilder->andWhere('product.category = :categoryId')
                ->setParameter('categoryId', (int)$data['category_id']);
        }

        if (!empty($data['supplier_id'])) {
            $queryBuilder->andWhere('product.supplier = :supplierId')
                ->setParameter('supplierId', (int)$data['supplier_id']);
        }

        if (!empty($data['name'])) {
            $queryBuilder->andWhere('product.name LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%');
        }

        if (!empty($data['description'])) {
            $queryBuilder->andWhere('product.description LIKE :description')
                ->setParameter('description', '%' . $data['description'] . '%');
        }

        if (!empty($data['min_price'])) {
            $queryBuilder->andWhere('product.price >= :minPrice')
                ->setParameter('minPrice', (float)$data['min_price']);
        }

        if (!empty($data['max_price'])) {
            $queryBuilder->andWhere('product.price <= :maxPrice')
                ->setParameter('maxPrice', (float)$data['max_price']);
        }

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }

}