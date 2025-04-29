<?php

namespace App\Http\Controllers;

use App\Repositories\ShipmentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public const ITEMS_PER_PAGE = 2;

    private ShipmentRepository $repo;

    public function __construct(ShipmentRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the shipments with pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'order_id',
            'status',
            'shipment_date',
            'delivery_date'
        ]);

        $perPage = (int) $request->query('itemsPerPage', self::ITEMS_PER_PAGE);
        $page    = (int) $request->query('page', 1);

        $data = $this->repo->getAllByFilter($filters, $perPage, $page);

        return response()->json($data, JsonResponse::HTTP_OK);
    }

/**
     * Store a newly created shipment.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'shipment_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'status' => 'required|string|max:255',
        ]);

        $shipment = Shipment::create($validated);

        return response()->json($shipment, Response::HTTP_CREATED);
    }

    /**
     * Display the specified shipment.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json(['message' => 'Shipment not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($shipment, Response::HTTP_OK);
    }

    /**
     * Update the specified shipment.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json(['message' => 'Shipment not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'shipment_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'status' => 'required|string|max:255',
        ]);

        $shipment->update($validated);

        return response()->json($shipment, Response::HTTP_OK);
    }

    /**
     * Remove the specified shipment.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json(['message' => 'Shipment not found'], Response::HTTP_NOT_FOUND);
        }

        $shipment->delete();

        return response()->json(['message' => 'Shipment deleted successfully'], Response::HTTP_OK);
    }
}
