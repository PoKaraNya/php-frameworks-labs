<?php

namespace App\Http\Controllers;

use App\Repositories\PurchaseOrderItemRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchaseOrderItemController extends Controller
{
    public const ITEMS_PER_PAGE = 2;

    private PurchaseOrderItemRepository $repo;

    public function __construct(PurchaseOrderItemRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the purchase order items with pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'purchase_order_id',
            'product_id',
            'quantity',
            'price_per_unit'
        ]);

        $perPage = (int) $request->query('itemsPerPage', self::ITEMS_PER_PAGE);
        $page    = (int) $request->query('page', 1);

        $data = $this->repo->getAllByFilter($filters, $perPage, $page);

        return response()->json($data, JsonResponse::HTTP_OK);
    }

/**
     * Store a newly created purchase order item.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
        ]);

        $purchaseOrderItem = PurchaseOrderItem::create($validated);

        return response()->json($purchaseOrderItem, Response::HTTP_CREATED);
    }

    /**
     * Display the specified purchase order item.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $purchaseOrderItem = PurchaseOrderItem::find($id);

        if (!$purchaseOrderItem) {
            return response()->json(['message' => 'PurchaseOrderItem not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($purchaseOrderItem, Response::HTTP_OK);
    }

    /**
     * Update the specified purchase order item.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $purchaseOrderItem = PurchaseOrderItem::find($id);

        if (!$purchaseOrderItem) {
            return response()->json(['message' => 'PurchaseOrderItem not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
        ]);

        $purchaseOrderItem->update($validated);

        return response()->json($purchaseOrderItem, Response::HTTP_OK);
    }

    /**
     * Remove the specified purchase order item.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $purchaseOrderItem = PurchaseOrderItem::find($id);

        if (!$purchaseOrderItem) {
            return response()->json(['message' => 'PurchaseOrderItem not found'], Response::HTTP_NOT_FOUND);
        }

        $purchaseOrderItem->delete();

        return response()->json(['message' => 'PurchaseOrderItem deleted successfully'], Response::HTTP_OK);
    }
}
