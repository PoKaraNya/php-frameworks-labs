<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public const ITEMS_PER_PAGE = 2;

    private OrderRepository $repo;

    public function __construct(OrderRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the orders with pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'customer_id', 'order_date']);
        $perPage = (int) $request->query('itemsPerPage', self::ITEMS_PER_PAGE);
        $page    = (int) $request->query('page', 1);

        $data = $this->repo->getAllByFilter($filters, $perPage, $page);

        return response()->json($data, JsonResponse::HTTP_OK);
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
