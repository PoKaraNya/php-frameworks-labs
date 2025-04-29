<?php

namespace App\Http\Controllers;

use App\Repositories\OrderItemRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public const ITEMS_PER_PAGE = 2;

    private OrderItemRepository $repo;

    public function __construct(OrderItemRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the order items with pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['order_id', 'product_id', 'quantity', 'price_per_unit']);
        $perPage = (int) $request->query('itemsPerPage', self::ITEMS_PER_PAGE);
        $page    = (int) $request->query('page', 1);

        $data = $this->repo->getAllByFilter($filters, $perPage, $page);

        return response()->json($data, JsonResponse::HTTP_OK);
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
