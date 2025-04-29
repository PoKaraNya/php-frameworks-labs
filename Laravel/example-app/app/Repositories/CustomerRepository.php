<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Services\PaginationService;

class CustomerRepository
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
        $query = Customer::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%'.$filters['email'].'%');
        }

        if (!empty($filters['phone'])) {
            $query->where('phone', 'like', '%'.$filters['phone'].'%');
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
