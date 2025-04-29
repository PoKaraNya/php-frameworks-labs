<?php

namespace App\Http\Controllers;

use App\Repositories\SupplierRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public const ITEMS_PER_PAGE = 2;

    private SupplierRepository $repo;

    public function __construct(SupplierRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the suppliers with pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'name',
            'contact_name',
            'contact_phone',
            'contact_email',
            'address'
        ]);

        $perPage = (int) $request->query('itemsPerPage', self::ITEMS_PER_PAGE);
        $page    = (int) $request->query('page', 1);

        $data = $this->repo->getAllByFilter($filters, $perPage, $page);

        return response()->json($data, JsonResponse::HTTP_OK);
    }

/**
     * Store a newly created supplier.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::create($validated);

        return response()->json($supplier, Response::HTTP_CREATED);
    }

    /**
     * Display the specified supplier.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($supplier, Response::HTTP_OK);
    }

    /**
     * Update the specified supplier.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $supplier->update($validated);

        return response()->json($supplier, Response::HTTP_OK);
    }

    /**
     * Remove the specified supplier.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        $supplier->delete();

        return response()->json(['message' => 'Supplier deleted successfully'], Response::HTTP_OK);
    }
}
