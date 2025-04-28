<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PurchaseOrderItemController extends Controller
{
    /**
     * Display a listing of the purchase order items.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $purchaseOrderItems = PurchaseOrderItem::all();
        return response()->json($purchaseOrderItems, Response::HTTP_OK);
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
