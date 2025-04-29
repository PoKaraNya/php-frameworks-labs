<?php

namespace App\Repositories;

use App\Models\Order;
use App\Services\PaginationService;

class OrderRepository
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
        $query = Order::query();

        if (!empty($filters['status'])) {
            $query->where('status', 'like', '%'.$filters['status'].'%');
        }

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (!empty($filters['order_date'])) {
            $query->whereDate('order_date', $filters['order_date']);
        }

        $p = $this->pagination->paginate($query, $perPage, $page);

        return [
            'items'           => $p->items(),
            'totalPageCount'  => $p->lastPage(),
            'totalItems'      => $p->total(),
        ];
    }
}
