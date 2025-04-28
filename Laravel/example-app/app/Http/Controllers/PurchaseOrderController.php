<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the purchase orders.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $purchaseOrders = PurchaseOrder::all();
        return response()->json($purchaseOrders, Response::HTTP_OK);
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
