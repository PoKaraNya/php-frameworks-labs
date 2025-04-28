<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the shipments.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $shipments = Shipment::all();
        return response()->json($shipments, Response::HTTP_OK);
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
