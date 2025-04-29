<?php

namespace App\Repositories;

use App\Models\Shipment;
use App\Services\PaginationService;

class ShipmentRepository
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
        $query = Shipment::query();

        if (!empty($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', 'like', '%'.$filters['status'].'%');
        }

        if (!empty($filters['shipment_date'])) {
            $query->whereDate('shipment_date', $filters['shipment_date']);
        }

        if (!empty($filters['delivery_date'])) {
            $query->whereDate('delivery_date', $filters['delivery_date']);
        }

        $p = $this->pagination->paginate($query, $perPage, $page);

        return [
            'items'           => $p->items(),
            'totalPageCount'  => $p->lastPage(),
            'totalItems'      => $p->total(),
        ];
    }
}
