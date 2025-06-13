<template>
    <div>
        <div
            class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden"
        >
            <!-- Order success header -->
            <div class="p-8 bg-green-50 text-center">
                <div
                    class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 text-green-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-green-700 mb-2">
                    Order Placed Successfully!
                </h1>
                <p class="text-gray-600">
                    Thank you for your purchase. Your order has been received.
                </p>
                <p class="text-gray-600 mt-2">
                    Order ID:
                    <span class="font-semibold">{{
                        orderInfo.id || "N/A"
                    }}</span>
                </p>
            </div>

            <!-- Order details -->
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Order Details</h2>

                <!-- Customer information -->
                <div class="mb-6 p-4 bg-gray-50 rounded">
                    <h3 class="font-medium mb-2">Customer Information</h3>
                    <p>
                        <span class="text-gray-600">Name:</span>
                        {{ orderInfo.customer_name }}
                    </p>
                    <p>
                        <span class="text-gray-600">Email:</span>
                        {{ orderInfo.customer_email }}
                    </p>
                    <p>
                        <span class="text-gray-600">Date:</span>
                        {{ formatDate(orderInfo.created_at) }}
                    </p>
                </div>

                <!-- Order items -->
                <div class="mb-6">
                    <h3 class="font-medium mb-3">Purchased Items</h3>
                    <div class="border rounded overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Product
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Quantity
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Price
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="item in orderInfo.items"
                                    :key="item.product.id"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 flex-shrink-0"
                                            >
                                                <img
                                                    class="h-10 w-10 rounded object-cover"
                                                    :src="
                                                        item.product.image_url
                                                            ? `http://localhost:8000/storage/${item.product.image_url}`
                                                            : '/placeholder.png'
                                                    "
                                                    :alt="item.product.name"
                                                />
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{ item.product.name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        {{ item.quantity }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        ${{ item.product.price.toFixed(2) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        ${{
                                            (
                                                item.quantity *
                                                item.product.price
                                            ).toFixed(2)
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Order summary -->
                <div class="mb-6">
                    <h3 class="font-medium mb-3">Order Summary</h3>
                    <div class="border-t border-b py-3">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-600">Subtotal</span>
                            <span>${{ calculateSubtotal().toFixed(2) }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-600">Shipping</span>
                            <span>$0.00</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-600">Tax (10%)</span>
                            <span
                                >${{
                                    (calculateSubtotal() * 0.1).toFixed(2)
                                }}</span
                            >
                        </div>
                        <div class="flex justify-between py-3 font-semibold">
                            <span>Total</span>
                            <span
                                >${{
                                    (calculateSubtotal() * 1.1).toFixed(2)
                                }}</span
                            >
                        </div>
                    </div>
                </div>

                <!-- What's next -->
                <div class="bg-blue-50 p-4 rounded mb-6">
                    <h3 class="font-medium text-blue-700 mb-2">What's Next?</h3>
                    <p class="text-gray-700 mb-2">
                        You'll receive an order confirmation email shortly at
                        <span class="font-semibold">{{
                            orderInfo.customer_email
                        }}</span
                        >.
                    </p>
                    <p class="text-gray-700">
                        Your order will be processed and shipped within 1-3
                        business days.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex justify-center space-x-4 mt-8">
                    <NuxtLink to="/products" class="btn btn-primary">
                        Continue Shopping
                    </NuxtLink>
                    <button @click="printReceipt" class="btn btn-secondary">
                        Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

// Order information
const orderInfo = ref({
    id: null,
    customer_name: "",
    customer_email: "",
    created_at: new Date().toISOString(),
    items: [],
    total_amount: 0,
});

// Load order information from localStorage
onMounted(() => {
    if (process.client) {
        const lastOrderId = localStorage.getItem("lastOrderId");
        const lastOrder = localStorage.getItem("lastOrder");

        if (lastOrder) {
            orderInfo.value = JSON.parse(lastOrder);
        } else {
            // If no order information is found, redirect to home page
            navigateTo("/");
        }
    }
});

// Calculate subtotal
const calculateSubtotal = () => {
    return orderInfo.value.items.reduce((total, item) => {
        return total + item.quantity * item.product.price;
    }, 0);
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    }).format(date);
};

// Print receipt
const printReceipt = () => {
    window.print();
};
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .btn {
        display: none;
    }
    .printable,
    .printable * {
        visibility: visible;
    }
    .printable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
