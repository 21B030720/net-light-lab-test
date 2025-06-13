<template>
  <div>
    <section class="bg-blue-600 text-white py-16">
      <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
          <h1 class="text-4xl md:text-5xl font-bold mb-6">Welcome to NetLab E-Shop</h1>
          <p class="text-xl mb-8">Discover amazing products at affordable prices</p>
          <NuxtLink to="/products" class="btn btn-primary bg-white text-blue-600 hover:bg-gray-100 py-3 px-8 text-lg">
            Shop Now
          </NuxtLink>
        </div>
      </div>
    </section>

    <section class="py-12">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Featured Categories</h2>
        <div v-if="categories.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="category in categories" :key="category.id" class="card hover:shadow-lg transition-shadow">
            <NuxtLink :to="`/products?category=${category.id}`" class="block p-6 text-center">
              <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                <span class="text-2xl font-bold text-blue-600">{{ category.name.charAt(0) }}</span>
              </div>
              <h3 class="font-semibold text-lg mb-2">{{ category.name }}</h3>
              <p class="text-gray-600 text-sm">{{ category.description }}</p>
            </NuxtLink>
          </div>
        </div>
        <div v-else class="text-center">
          <p class="text-gray-600">Loading categories...</p>
        </div>
      </div>
    </section>

    <section class="py-12 bg-gray-50">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Featured Products</h2>
        <div v-if="products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="product in products" :key="product.id" class="card hover:shadow-lg transition-shadow">
            <NuxtLink :to="`/products/${product.id}`">
              <img
                :src="product.image_url ? `http://localhost:8000/storage/${product.image_url}` : '/placeholder.png'"
                :alt="product.name"
                class="w-full h-48 object-cover"
              />
              <div class="p-4">
                <h3 class="font-semibold mb-2">{{ product.name }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ product.description.substring(0, 60) }}...</p>
                <div class="flex justify-between items-center">
                  <span class="font-bold text-blue-600">${{ product.price.toFixed(2) }}</span>
                  <button
                    @click.prevent="addToCart(product)"
                    class="btn btn-primary text-sm py-1"
                  >
                    Add to Cart
                  </button>
                </div>
              </div>
            </NuxtLink>
          </div>
        </div>
        <div v-else class="text-center">
          <p class="text-gray-600">Loading products...</p>
        </div>
        <div class="text-center mt-8">
          <NuxtLink to="/products" class="btn btn-primary">View All Products</NuxtLink>
        </div>
      </div>
    </section>

    <section class="py-12">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Why Choose Us</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <h3 class="font-semibold text-lg mb-2">Quality Products</h3>
            <p class="text-gray-600">We ensure that all our products meet the highest quality standards.</p>
          </div>
          <div class="text-center">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="font-semibold text-lg mb-2">Fast Delivery</h3>
            <p class="text-gray-600">Quick delivery to your doorstep with real-time tracking.</p>
          </div>
          <div class="text-center">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
            </div>
            <h3 class="font-semibold text-lg mb-2">Secure Payments</h3>
            <p class="text-gray-600">Your payments are secure with our trusted payment gateways.</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// Use our composables
const { get } = useApi();
const { addToCart } = useCart();

// Data
const categories = ref([]);
const products = ref([]);

// Fetch data on mount
onMounted(async () => {
  try {
    // Fetch categories
    const categoriesResponse = await fetch('http://localhost:8000/api/categories');
    const categoriesData = await categoriesResponse.json();
    categories.value = categoriesData.data.slice(0, 4); // Just display 4 categories
  } catch (error) {
    console.error('Error fetching categories:', error);
  }

  try {
    // Fetch featured products
    const productsResponse = await fetch('http://localhost:8000/api/products?per_page=4');
    const productsData = await productsResponse.json();
    products.value = productsData.data.data; // First 4 products
  } catch (error) {
    console.error('Error fetching products:', error);
  }
});

// Handle add to cart
const handleAddToCart = (product) => {
  addToCart(product);
  // Show toast notification
  alert(`${product.name} added to cart!`);
};
</script>
