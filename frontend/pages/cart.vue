<template>
    <div>
        <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

        <!-- Empty cart state -->
        <div
            v-if="cart.length === 0"
            class="text-center py-12 bg-white rounded-lg shadow-sm"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-16 w-16 text-gray-400 mx-auto mb-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                />
            </svg>
            <h2 class="text-xl font-semibold mb-2">Your cart is empty</h2>
            <p class="text-gray-600 mb-6">
                Looks like you haven't added any products to your cart yet.
            </p>
            <NuxtLink to="/products" class="btn btn-primary py-2 px-6">
                Continue Shopping
            </NuxtLink>
        </div>

        <!-- Cart with items -->
        <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Cart items list -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="font-semibold">
                            Cart Items ({{ itemCount }})
                        </h2>
                    </div>

                    <!-- Cart items -->
                    <div
                        v-for="item in cart"
                        :key="item.product.id"
                        class="p-4 border-b border-gray-100"
                    >
                        <div class="flex items-center space-x-4">
                            <img
                                :src="
                                    item.product.image_url
                                        ? `http://localhost:8000/storage/${item.product.image_url}`
                                        : '/placeholder.png'
                                "
                                :alt="item.product.name"
                                class="w-16 h-16 object-cover rounded"
                            />
                            <div class="flex-grow">
                                <h3 class="font-medium">
                                    {{ item.product.name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{
                                        item.product.category
                                            ? item.product.category.name
                                            : "Uncategorized"
                                    }}
                                </p>
                                <p class="text-blue-600 font-semibold">
                                    ${{ item.product.price.toFixed(2) }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button
                                    @click="
                                        updateQuantity(
                                            item.product.id,
                                            item.quantity - 1,
                                        )
                                    "
                                    class="p-1 rounded-full bg-gray-100 hover:bg-gray-200"
                                    :disabled="item.quantity <= 1"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M20 12H4"
                                        />
                                    </svg>
                                </button>
                                <span class="w-8 text-center">{{
                                    item.quantity
                                }}</span>
                                <button
                                    @click="
                                        updateQuantity(
                                            item.product.id,
                                            item.quantity + 1,
                                        )
                                    "
                                    class="p-1 rounded-full bg-gray-100 hover:bg-gray-200"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4v16m8-8H4"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <div class="text-right min-w-[80px]">
                                <div class="font-semibold">
                                    ${{
                                        (
                                            item.product.price * item.quantity
                                        ).toFixed(2)
                                    }}
                                </div>
                                <button
                                    @click="removeFromCart(item.product.id)"
                                    class="text-red-500 text-sm hover:underline"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Cart actions -->
                    <div class="p-4 flex justify-between">
                        <button
                            @click="clearCart"
                            class="text-gray-500 hover:text-red-500"
                        >
                            Clear Cart
                        </button>
                        <NuxtLink
                            to="/products"
                            class="text-blue-600 hover:underline"
                        >
                            Continue Shopping
                        </NuxtLink>
                    </div>
                </div>
            </div>

            <!-- Order summary -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-4 sticky top-4">
                    <h2 class="font-semibold mb-4 pb-4 border-b">
                        Order Summary
                    </h2>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600"
                                >Subtotal ({{ itemCount }} items)</span
                            >
                            <span>${{ totalPrice.toFixed(2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span>$0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span>${{ (totalPrice * 0.1).toFixed(2) }}</span>
                        </div>
                    </div>

                    <div
                        class="flex justify-between font-semibold text-lg border-t pt-4 mb-6"
                    >
                        <span>Total</span>
                        <span
                            >${{
                                (totalPrice + totalPrice * 0.1).toFixed(2)
                            }}</span
                        >
                    </div>

                    <NuxtLink
                        to="/checkout"
                        class="btn btn-primary w-full py-3 text-center block"
                    >
                        Proceed to Checkout
                    </NuxtLink>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

// Use our composables
const {
    cart,
    itemCount,
    totalPrice,
    removeFromCart,
    updateQuantity,
    clearCart,
} = useCart();
</script>
