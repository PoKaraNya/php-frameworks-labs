<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Services\PaginationService;

class SupplierRepository
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
        $query = Supplier::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (!empty($filters['contact_name'])) {
            $query->where('contact_name', 'like', '%'.$filters['contact_name'].'%');
        }

        if (!empty($filters['contact_phone'])) {
            $query->where('contact_phone', 'like', '%'.$filters['contact_phone'].'%');
        }

        if (!empty($filters['contact_email'])) {
            $query->where('contact_email', 'like', '%'.$filters['contact_email'].'%');
        }

        if (!empty($filters['address'])) {
            $query->where('address', 'like', '%'.$filters['address'].'%');
        }

        $p = $this->pagination->paginate($query, $perPage, $page);

        return [
            'items'           => $p->items(),
            'totalPageCount'  => $p->lastPage(),
            'totalItems'      => $p->total(),
        ];
    }
}
