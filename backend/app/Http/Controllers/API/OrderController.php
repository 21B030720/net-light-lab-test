<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Get orders for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserOrders(Request $request): JsonResponse
    {
        $user = $request->user();
        $orders = Order::where("customer_email", $user->email)
            ->withCount("orderItems as items_count")
            ->orderBy("created_at", "desc")
            ->get();

        return response()->json([
            "success" => true,
            "data" => $orders,
        ]);
    }

    /**
     * Store a newly created order in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "customer_name" => "required|string|max:255",
            "customer_email" => "required|email|max:255",
            "items" => "required|array|min:1",
            "items.*.product_id" => "required|exists:products,id",
            "items.*.quantity" => "required|integer|min:1",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "errors" => $validator->errors(),
                ],
                422
            );
        }

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $items = $request->input("items");

            // Calculate total amount
            foreach ($items as $item) {
                $product = Product::findOrFail($item["product_id"]);
                $totalAmount += $product->price * $item["quantity"];
            }

            // Create order
            $order = Order::create([
                "customer_name" => $request->input("customer_name"),
                "customer_email" => $request->input("customer_email"),
                "total_amount" => $totalAmount,
                "status" => "pending",
            ]);

            // Create order items
            foreach ($items as $item) {
                $product = Product::findOrFail($item["product_id"]);

                OrderItem::create([
                    "order_id" => $order->id,
                    "product_id" => $product->id,
                    "quantity" => $item["quantity"],
                    "price" => $product->price,
                ]);
            }

            DB::commit();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Order created successfully",
                    "data" => $order->load("orderItems"),
                ],
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(
                [
                    "success" => false,
                    "message" => "Failed to create order",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
    }
}
