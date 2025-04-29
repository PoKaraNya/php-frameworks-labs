<?php

namespace App\Repositories;

use App\Models\OrderItem;
use App\Services\PaginationService;

class OrderItemRepository
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
        $query = OrderItem::query();

        if (!empty($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['quantity'])) {
            $query->where('quantity', $filters['quantity']);
        }

        if (!empty($filters['price_per_unit'])) {
            $query->where('price_per_unit', $filters['price_per_unit']);
        }

        $p = $this->pagination->paginate($query, $perPage, $page);

        return [
            'items'           => $p->items(),
            'totalPageCount'  => $p->lastPage(),
            'totalItems'      => $p->total(),
        ];
    }
}
