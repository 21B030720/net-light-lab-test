<template>
  <div>
    <!-- Redirect if not logged in -->
    <div v-if="!isAuthenticated" class="text-center py-12">
      <p class="text-lg mb-4">You need to be logged in to view your profile.</p>
      <div class="flex justify-center space-x-4">
        <NuxtLink to="/login" class="btn btn-primary">Login</NuxtLink>
        <NuxtLink to="/register" class="btn btn-secondary">Register</NuxtLink>
      </div>
    </div>

    <!-- Profile content when logged in -->
    <div v-else class="max-w-3xl mx-auto">
      <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
        <div class="bg-blue-600 text-white py-6 px-8">
          <h1 class="text-2xl font-bold">Your Profile</h1>
        </div>
        <div class="p-8">
          <div class="flex items-center mb-8">
            <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mr-6">
              <span class="text-2xl font-bold text-blue-600">{{ userInitials }}</span>
            </div>
            <div>
              <h2 class="text-xl font-semibold">{{ user.name }}</h2>
              <p class="text-gray-600">{{ user.email }}</p>
            </div>
          </div>
          <div class="border-t pt-6">
            <h3 class="font-semibold text-lg mb-4">Account Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Name</label>
                <p>{{ user.name }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                <p>{{ user.email }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Member Since</label>
                <p>{{ formatDate(user.created_at) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Orders section -->
      <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b">
          <h2 class="font-semibold text-lg">Your Orders</h2>
        </div>
        <div v-if="orders.length === 0" class="p-6 text-center">
          <p class="text-gray-600 mb-4">You haven't placed any orders yet.</p>
          <NuxtLink to="/products" class="btn btn-primary">Start Shopping</NuxtLink>
        </div>
        <div v-else>
          <div v-for="order in orders" :key="order.id" class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-start mb-4">
              <div>
                <h3 class="font-medium">Order #{{ order.id }}</h3>
                <p class="text-sm text-gray-500">{{ formatDate(order.created_at) }}</p>
              </div>
              <div>
                <span
                  class="px-2 py-1 text-xs rounded-full"
                  :class="{
                    'bg-green-100 text-green-800': order.status === 'completed',
                    'bg-yellow-100 text-yellow-800': order.status === 'pending',
                    'bg-blue-100 text-blue-800': order.status === 'processing',
                  }"
                >
                  {{ order.status }}
                </span>
              </div>
            </div>
            <div class="flex justify-between">
              <div>
                <p class="text-sm text-gray-600">Items: {{ order.items_count || 'N/A' }}</p>
              </div>
              <div>
                <p class="font-semibold">${{ order.total_amount }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Logout button -->
      <div class="mt-8 text-center">
        <button @click="logout" class="btn btn-danger">
          Logout
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

// Get composables
const { isAuthenticated, clearToken } = useApi();
const user = ref({});
const orders = ref([]);

// Get user initials for avatar
const userInitials = computed(() => {
  if (!user.value.name) return '';
  return user.value.name
    .split(' ')
    .map(word => word.charAt(0).toUpperCase())
    .slice(0, 2)
    .join('');
});

// Format date
const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date);
};

// Load user data on mount
onMounted(() => {
  if (process.client) {
    // Get user from localStorage
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      user.value = JSON.parse(storedUser);
    }

    // Fetch user orders
    fetchOrders();
  }
});

// Fetch user orders
const fetchOrders = async () => {
  if (!isAuthenticated.value) return;

  try {
    const token = localStorage.getItem('token');
    const response = await fetch('http://localhost:8000/api/orders', {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    });

    const data = await response.json();

    if (data.success) {
      orders.value = data.data;
    }
  } catch (error) {
    console.error('Error fetching orders:', error);
  }
};

// Logout function
const logout = async () => {
  try {
    const token = localStorage.getItem('token');
    await fetch('http://localhost:8000/api/logout', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`
      }
    });

    // Clear local storage and state
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    clearToken();

    // Redirect to home page
    navigateTo('/');
  } catch (error) {
    console.error('Logout failed:', error);
  }
};
</script>
