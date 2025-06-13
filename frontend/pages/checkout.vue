<template>
    <div>
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>

        <!-- Empty cart redirector -->
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
                You need to add products to your cart before checkout.
            </p>
            <NuxtLink to="/products" class="btn btn-primary py-2 px-6">
                Browse Products
            </NuxtLink>
        </div>

        <!-- Checkout form -->
        <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Customer information form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="font-semibold">Customer Information</h2>
                    </div>

                    <form @submit.prevent="submitOrder" class="p-6 space-y-6">
                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">
                                Contact Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        for="name"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Full Name*</label
                                    >
                                    <input
                                        type="text"
                                        id="name"
                                        v-model="checkoutForm.name"
                                        class="input"
                                        required
                                    />
                                    <p
                                        v-if="errors.name"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors.name }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        for="email"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Email Address*</label
                                    >
                                    <input
                                        type="email"
                                        id="email"
                                        v-model="checkoutForm.email"
                                        class="input"
                                        required
                                    />
                                    <p
                                        v-if="errors.email"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors.email }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        for="phone"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Phone Number</label
                                    >
                                    <input
                                        type="tel"
                                        id="phone"
                                        v-model="checkoutForm.phone"
                                        class="input"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">
                                Shipping Address
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label
                                        for="address"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Street Address*</label
                                    >
                                    <input
                                        type="text"
                                        id="address"
                                        v-model="checkoutForm.address"
                                        class="input"
                                        required
                                    />
                                </div>
                                <div
                                    class="grid grid-cols-1 md:grid-cols-3 gap-4"
                                >
                                    <div>
                                        <label
                                            for="city"
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                            >City*</label
                                        >
                                        <input
                                            type="text"
                                            id="city"
                                            v-model="checkoutForm.city"
                                            class="input"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="state"
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                            >State/Province*</label
                                        >
                                        <input
                                            type="text"
                                            id="state"
                                            v-model="checkoutForm.state"
                                            class="input"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="zip"
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                            >ZIP/Postal Code*</label
                                        >
                                        <input
                                            type="text"
                                            id="zip"
                                            v-model="checkoutForm.zip"
                                            class="input"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information (Simulated) -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">
                                Payment Method
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-2"
                                        >Select a payment method*</label
                                    >
                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center space-x-3"
                                        >
                                            <input
                                                type="radio"
                                                name="paymentMethod"
                                                value="creditCard"
                                                v-model="
                                                    checkoutForm.paymentMethod
                                                "
                                                class="h-4 w-4 text-blue-600"
                                                required
                                            />
                                            <span>Credit Card (Simulated)</span>
                                        </label>
                                        <label
                                            class="flex items-center space-x-3"
                                        >
                                            <input
                                                type="radio"
                                                name="paymentMethod"
                                                value="paypal"
                                                v-model="
                                                    checkoutForm.paymentMethod
                                                "
                                                class="h-4 w-4 text-blue-600"
                                            />
                                            <span>PayPal (Simulated)</span>
                                        </label>
                                        <label
                                            class="flex items-center space-x-3"
                                        >
                                            <input
                                                type="radio"
                                                name="paymentMethod"
                                                value="cashOnDelivery"
                                                v-model="
                                                    checkoutForm.paymentMethod
                                                "
                                                class="h-4 w-4 text-blue-600"
                                            />
                                            <span>Cash on Delivery</span>
                                        </label>
                                    </div>
                                    <p
                                        v-if="errors.paymentMethod"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors.paymentMethod }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Order notes -->
                        <div>
                            <label
                                for="notes"
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Order Notes</label
                            >
                            <textarea
                                id="notes"
                                v-model="checkoutForm.notes"
                                rows="3"
                                class="input w-full"
                                placeholder="Additional information about your order"
                            ></textarea>
                        </div>

                        <!-- Form actions -->
                        <div class="flex justify-between pt-4 mt-6 border-t">
                            <NuxtLink to="/cart" class="btn btn-secondary">
                                Back to Cart
                            </NuxtLink>
                            <button
                                type="submit"
                                class="btn btn-primary px-8"
                                :disabled="submitting"
                            >
                                {{
                                    submitting ? "Processing..." : "Place Order"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order summary -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-4 sticky top-4">
                    <h2 class="font-semibold mb-4 pb-4 border-b">
                        Order Summary
                    </h2>

                    <!-- Cart items (collapsed) -->
                    <div class="mb-6">
                        <div
                            v-for="item in cart"
                            :key="item.product.id"
                            class="flex justify-between py-2 border-b border-gray-100 last:border-b-0"
                        >
                            <div class="flex items-center">
                                <span
                                    class="bg-gray-200 text-gray-800 rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2"
                                >
                                    {{ item.quantity }}
                                </span>
                                <span class="text-sm truncate max-w-[150px]">{{
                                    item.product.name
                                }}</span>
                            </div>
                            <span class="font-medium"
                                >${{
                                    (
                                        item.product.price * item.quantity
                                    ).toFixed(2)
                                }}</span
                            >
                        </div>
                    </div>

                    <!-- Order totals -->
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
                        class="flex justify-between font-semibold text-lg border-t pt-4"
                    >
                        <span>Total</span>
                        <span
                            >${{
                                (totalPrice + totalPrice * 0.1).toFixed(2)
                            }}</span
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

// Use our composables
const { cart, itemCount, totalPrice, clearCart } = useCart();

// Form state
const checkoutForm = ref({
    name: "",
    email: "",
    phone: "",
    address: "",
    city: "",
    state: "",
    zip: "",
    paymentMethod: "creditCard",
    notes: "",
});

const submitting = ref(false);
const errors = ref({});

// Submit order
const submitOrder = async () => {
    submitting.value = true;
    errors.value = {};

    // Basic validation
    if (!checkoutForm.value.name) {
        errors.value.name = "Name is required";
    }

    if (!checkoutForm.value.email) {
        errors.value.email = "Email is required";
    } else if (!isValidEmail(checkoutForm.value.email)) {
        errors.value.email = "Please enter a valid email address";
    }

    if (!checkoutForm.value.paymentMethod) {
        errors.value.paymentMethod = "Please select a payment method";
    }

    // Stop if there are validation errors
    if (Object.keys(errors.value).length > 0) {
        submitting.value = false;
        return;
    }

    try {
        const orderData = {
            customer_name: checkoutForm.value.name,
            customer_email: checkoutForm.value.email,
            items: cart.value.map((item) => ({
                product_id: item.product.id,
                quantity: item.quantity,
            })),
            // Additional data that won't be stored in the backend but useful for demonstration
            shipping_address: {
                address: checkoutForm.value.address,
                city: checkoutForm.value.city,
                state: checkoutForm.value.state,
                zip: checkoutForm.value.zip,
            },
            payment_method: checkoutForm.value.paymentMethod,
            notes: checkoutForm.value.notes,
        };

        const response = await fetch("http://localhost:8000/api/orders", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(orderData),
        });

        const data = await response.json();

        if (data.success) {
            // Store order ID for confirmation page
            localStorage.setItem("lastOrderId", data.data.id);
            localStorage.setItem(
                "lastOrder",
                JSON.stringify({
                    ...data.data,
                    customer_name: checkoutForm.value.name,
                    items: cart.value,
                }),
            );

            // Clear cart and redirect to confirmation page
            clearCart();
            navigateTo("/order-confirmation");
        } else {
            alert(
                "There was an error processing your order. Please try again.",
            );
        }
    } catch (error) {
        console.error("Error submitting order:", error);
        alert("There was an error processing your order. Please try again.");
    } finally {
        submitting.value = false;
    }
};

// Email validation
const isValidEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};
</script>
