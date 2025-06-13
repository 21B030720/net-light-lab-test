<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <nuxt-link to="/" class="text-xl font-bold text-blue-600">NetLab E-Shop</nuxt-link>
        <nav class="flex space-x-6">
          <nuxt-link to="/" class="text-gray-700 hover:text-blue-600">Home</nuxt-link>
          <nuxt-link to="/products" class="text-gray-700 hover:text-blue-600">Products</nuxt-link>
          <nuxt-link to="/cart" class="text-gray-700 hover:text-blue-600 relative">
            Cart
            <span v-if="cartItemCount > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
              {{ cartItemCount }}
            </span>
          </nuxt-link>
          <template v-if="isLoggedIn">
            <nuxt-link to="/profile" class="text-gray-700 hover:text-blue-600">Profile</nuxt-link>
            <button @click="logout" class="text-gray-700 hover:text-blue-600">Logout</button>
          </template>
          <template v-else>
            <nuxt-link to="/login" class="text-gray-700 hover:text-blue-600">Login</nuxt-link>
            <nuxt-link to="/register" class="text-gray-700 hover:text-blue-600">Register</nuxt-link>
          </template>
        </nav>
      </div>
    </header>

    <main class="container mx-auto px-4 py-8">
      <slot />
    </main>

    <footer class="bg-gray-800 text-white py-8">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div>
            <h3 class="text-lg font-bold mb-4">NetLab E-Shop</h3>
            <p class="text-gray-300">Your one-stop shop for all products.</p>
          </div>
          <div>
            <h3 class="text-lg font-bold mb-4">Quick Links</h3>
            <ul class="space-y-2">
              <li><nuxt-link to="/" class="text-gray-300 hover:text-white">Home</nuxt-link></li>
              <li><nuxt-link to="/products" class="text-gray-300 hover:text-white">Products</nuxt-link></li>
              <li><nuxt-link to="/cart" class="text-gray-300 hover:text-white">Cart</nuxt-link></li>
            </ul>
          </div>
          <div>
            <h3 class="text-lg font-bold mb-4">Contact</h3>
            <p class="text-gray-300">Email: info@netlab-eshop.com</p>
            <p class="text-gray-300">Phone: +123 456 7890</p>
          </div>
        </div>
        <div class="mt-8 pt-6 border-t border-gray-700 text-center">
          <p class="text-gray-300">&copy; {{ new Date().getFullYear() }} NetLab E-Shop. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';

// Cart state
const cartItems = ref([]);
const isLoggedIn = ref(false);

// Computed properties
const cartItemCount = computed(() => {
  return cartItems.value.reduce((total, item) => total + item.quantity, 0);
});

// Load cart from localStorage on component mount
onMounted(() => {
  // Load cart items from localStorage
  const savedCart = localStorage.getItem('cart');
  if (savedCart) {
    cartItems.value = JSON.parse(savedCart);
  }

  // Check if user is logged in
  const token = localStorage.getItem('token');
  isLoggedIn.value = !!token;
});

// Logout function
const logout = async () => {
  try {
    // Call logout API
    await fetch('http://localhost:8000/api/logout', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });

    // Clear local storage and reset state
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    isLoggedIn.value = false;

    // Redirect to home page
    navigateTo('/');
  } catch (error) {
    console.error('Logout failed:', error);
  }
};
</script>

<style>
/* Add any global styles here */
body {
  font-family: 'Inter', sans-serif;
}
</style>
