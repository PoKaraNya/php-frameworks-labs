<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $customers = Customer::all();
        return response()->json($customers, Response::HTTP_OK);
    }

    /**
     * Store a newly created customer.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
        ]);

        $customer = Customer::create($validated);

        return response()->json($customer, Response::HTTP_CREATED);
    }

    /**
     * Display the specified customer.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($customer, Response::HTTP_OK);
    }

    /**
     * Update the specified customer.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
        ]);

        $customer->update($validated);

        return response()->json($customer, Response::HTTP_OK);
    }

    /**
     * Remove the specified customer.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully'], Response::HTTP_OK);
    }
}
