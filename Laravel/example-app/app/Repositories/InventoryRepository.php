<?php

namespace App\Repositories;

use App\Models\Inventory;
use App\Services\PaginationService;

class InventoryRepository
{
    private PaginationService $pagination;

    public function __construct(PaginationService $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @param array $filters
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function getAllByFilter(array $filters, int $perPage, int $page): array
    {
        $query = Inventory::query();

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['quantity'])) {
            $query->where('quantity', $filters['quantity']);
        }

        if (!empty($filters['last_updated'])) {
            $query->whereDate('last_updated', $filters['last_updated']);
        }

        $p = $this->pagination->paginate($query, $perPage, $page);

        return [
            'items'           => $p->items(),
            'totalPageCount'  => $p->lastPage(),
            'totalItems'      => $p->total(),
        ];
    }
}
