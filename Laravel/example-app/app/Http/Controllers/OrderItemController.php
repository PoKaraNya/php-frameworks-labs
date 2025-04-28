<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the order items.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $orderItems = OrderItem::all();
        return response()->json($orderItems, Response::HTTP_OK);
    }

    /**
     * Store a newly created order item.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
        ]);

        $orderItem = OrderItem::create($validated);

        return response()->json($orderItem, Response::HTTP_CREATED);
    }

    /**
     * Display the specified order item.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $orderItem = OrderItem::find($id);

        if (!$orderItem) {
            return response()->json(['message' => 'OrderItem not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($orderItem, Response::HTTP_OK);
    }

    /**
     * Update the specified order item.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $orderItem = OrderItem::find($id);

        if (!$orderItem) {
            return response()->json(['message' => 'OrderItem not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
        ]);

        $orderItem->update($validated);

        return response()->json($orderItem, Response::HTTP_OK);
    }

    /**
     * Remove the specified order item.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $orderItem = OrderItem::find($id);

        if (!$orderItem) {
            return response()->json(['message' => 'OrderItem not found'], Response::HTTP_NOT_FOUND);
        }

        $orderItem->delete();

        return response()->json(['message' => 'OrderItem deleted successfully'], Response::HTTP_OK);
    }
}
