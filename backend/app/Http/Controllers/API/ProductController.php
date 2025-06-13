<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a paginated listing of products.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input("per_page", 10);
        $query = Product::with("category")->where("is_active", true);

        // Filter by category if provided
        if ($request->has("category_id")) {
            $query->where("category_id", $request->input("category_id"));
        }

        // Search by name or description if provided
        if ($request->has("search")) {
            $searchTerm = $request->input("search");
            $query->where(function ($q) use ($searchTerm) {
                $q->where("name", "LIKE", "%{$searchTerm}%")->orWhere(
                    "description",
                    "LIKE",
                    "%{$searchTerm}%"
                );
            });
        }

        $products = $query->paginate($perPage);

        return response()->json([
            "success" => true,
            "data" => $products,
        ]);
    }

    /**
     * Display the specified product.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::with("category")->find($id);

        if (!$product) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Product not found",
                ],
                404
            );
        }

        return response()->json([
            "success" => true,
            "data" => $product,
        ]);
    }
}
