<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $inventories = Inventory::with('product')->get();
        return response()->json($inventories, Response::HTTP_OK);
    }

    /**
     * Store a newly created inventory.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $inventory = Inventory::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'last_updated' => now(),
        ]);

        return response()->json($inventory, Response::HTTP_CREATED);
    }

    /**
     * Display the specified inventory.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $inventory = Inventory::with('product')->find($id);

        if (!$inventory) {
            return response()->json(['message' => 'Inventory not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($inventory, Response::HTTP_OK);
    }

    /**
     * Update the specified inventory.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json(['message' => 'Inventory not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'product_id' => 'sometimes|required|exists:products,id',
            'quantity' => 'sometimes|required|integer|min:0',
        ]);

        $inventory->update(array_merge(
            $validated,
            ['last_updated' => now()]
        ));

        return response()->json($inventory, Response::HTTP_OK);
    }

    /**
     * Remove the specified inventory.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json(['message' => 'Inventory not found'], Response::HTTP_NOT_FOUND);
        }

        $inventory->delete();

        return response()->json(['message' => 'Inventory deleted successfully'], Response::HTTP_OK);
    }
}
