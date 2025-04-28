<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $orders = Order::all();
        return response()->json($orders, Response::HTTP_OK);
    }

    /**
     * Store a newly created order.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_date' => 'required|date',
            'status' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $order = Order::create($validated);

        return response()->json($order, Response::HTTP_CREATED);
    }

    /**
     * Display the specified order.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($order, Response::HTTP_OK);
    }

    /**
     * Update the specified order.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'order_date' => 'required|date',
            'status' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $order->update($validated);

        return response()->json($order, Response::HTTP_OK);
    }

    /**
     * Remove the specified order.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], Response::HTTP_OK);
    }
}
