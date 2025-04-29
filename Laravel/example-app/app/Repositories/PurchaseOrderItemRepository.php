<?php

namespace App\Repositories;

use App\Models\PurchaseOrderItem;
use App\Services\PaginationService;

class PurchaseOrderItemRepository
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
        $query = PurchaseOrderItem::query();

        if (!empty($filters['purchase_order_id'])) {
            $query->where('purchase_order_id', $filters['purchase_order_id']);
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
