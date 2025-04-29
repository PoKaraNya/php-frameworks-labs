<?php

namespace App\Http\Controllers;

use App\Repositories\PurchaseOrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public const ITEMS_PER_PAGE = 2;

    private PurchaseOrderRepository $repo;

    public function __construct(PurchaseOrderRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the purchase orders with pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['supplier_id', 'status', 'order_date']);
        $perPage = (int) $request->query('itemsPerPage', self::ITEMS_PER_PAGE);
        $page    = (int) $request->query('page', 1);

        $data = $this->repo->getAllByFilter($filters, $perPage, $page);

        return response()->json($data, JsonResponse::HTTP_OK);
    }

/**
     * Store a newly created purchase order.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        $purchaseOrder = PurchaseOrder::create($validated);

        return response()->json($purchaseOrder, Response::HTTP_CREATED);
    }

    /**
     * Display the specified purchase order.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $purchaseOrder = PurchaseOrder::find($id);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'PurchaseOrder not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($purchaseOrder, Response::HTTP_OK);
    }

    /**
     * Update the specified purchase order.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $purchaseOrder = PurchaseOrder::find($id);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'PurchaseOrder not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        $purchaseOrder->update($validated);

        return response()->json($purchaseOrder, Response::HTTP_OK);
    }

    /**
     * Remove the specified purchase order.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $purchaseOrder = PurchaseOrder::find($id);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'PurchaseOrder not found'], Response::HTTP_NOT_FOUND);
        }

        $purchaseOrder->delete();

        return response()->json(['message' => 'PurchaseOrder deleted successfully'], Response::HTTP_OK);
    }
}
