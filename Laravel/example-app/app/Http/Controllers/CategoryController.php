<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();
        return response()->json($categories, Response::HTTP_OK);
    }

    /**
     * Store a newly created category.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $category = Category::create($validated);

        return response()->json($category, Response::HTTP_CREATED);
    }

    /**
     * Display the specified category.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * Update the specified category.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $category->update($validated);

        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * Remove the specified category.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], Response::HTTP_OK);
    }
}
