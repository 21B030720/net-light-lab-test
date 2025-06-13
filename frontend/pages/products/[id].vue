<template>
  <div>
    <!-- Loading state -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
      <p class="text-gray-600">Loading product...</p>
    </div>

    <!-- Error state -->
    <div v-else-if="error" class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
      <p>{{ error }}</p>
      <button @click="fetchProduct" class="underline mt-2">Try again</button>
      <div class="mt-4">
        <NuxtLink to="/products" class="btn btn-primary">Back to Products</NuxtLink>
      </div>
    </div>

    <!-- Product details -->
    <div v-else-if="product" class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Product image -->
      <div class="card overflow-hidden">
        <img
          :src="product.image_url ? `http://localhost:8000/storage/${product.image_url}` : '/placeholder.png'"
          :alt="product.name"
          class="w-full h-auto object-cover"
        />
      </div>

      <!-- Product info -->
      <div>
        <div class="mb-2">
          <NuxtLink
            :to="`/products?category=${product.category_id}`"
            class="text-blue-600 text-sm hover:underline"
          >
            {{ product.category ? product.category.name : 'Uncategorized' }}
          </NuxtLink>
        </div>
        <h1 class="text-3xl font-bold mb-4">{{ product.name }}</h1>
        <div class="text-2xl font-bold text-blue-600 mb-6">${{ product.price.toFixed(2) }}</div>

        <div class="mb-6">
          <p class="text-gray-700 whitespace-pre-line">{{ product.description }}</p>
        </div>

        <!-- Stock status -->
        <div class="mb-6">
          <p v-if="product.stock > 10" class="text-green-600 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            In Stock ({{ product.stock }} available)
          </p>
          <p v-else-if="product.stock > 0" class="text-yellow-600 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Low Stock (Only {{ product.stock }} left)
          </p>
          <p v-else class="text-red-600 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Out of Stock
          </p>
        </div>

        <!-- Quantity selector -->
        <div class="mb-6">
          <label for="quantity" class="block mb-2 font-medium">Quantity</label>
          <div class="flex items-center">
            <button
              @click="decreaseQuantity"
              class="bg-gray-200 px-3 py-1 rounded-l"
              :disabled="quantity <= 1"
              :class="{ 'opacity-50 cursor-not-allowed': quantity <= 1 }"
            >
              -
            </button>
            <input
              type="number"
              id="quantity"
              v-model="quantity"
              min="1"
              :max="product.stock"
              class="w-16 text-center border-y py-1"
            >
            <button
              @click="increaseQuantity"
              class="bg-gray-200 px-3 py-1 rounded-r"
              :disabled="quantity >= product.stock"
              :class="{ 'opacity-50 cursor-not-allowed': quantity >= product.stock }"
            >
              +
            </button>
          </div>
        </div>

        <!-- Add to cart button -->
        <div class="mb-6">
          <button
            @click="addToCartWithQuantity"
            class="btn btn-primary w-full py-3 text-lg"
            :disabled="product.stock <= 0"
            :class="{ 'opacity-50 cursor-not-allowed bg-gray-400': product.stock <= 0 }"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            {{ product.stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Related products -->
    <div v-if="relatedProducts.length > 0" class="mt-12">
      <h2 class="text-2xl font-bold mb-6">Related Products</h2>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div
          v-for="relatedProduct in relatedProducts"
          :key="relatedProduct.id"
          class="card hover:shadow-lg transition-shadow"
        >
          <NuxtLink :to="`/products/${relatedProduct.id}`">
            <img
              :src="relatedProduct.image_url ? `http://localhost:8000/storage/${relatedProduct.image_url}` : '/placeholder.png'"
              :alt="relatedProduct.name"
              class="w-full h-48 object-cover"
            />
            <div class="p-4">
              <h3 class="font-semibold mb-2">{{ relatedProduct.name }}</h3>
              <div class="flex justify-between items-center">
                <span class="font-bold text-blue-600">${{ relatedProduct.price.toFixed(2) }}</span>
                <button
                  @click.prevent="addToCart(relatedProduct)"
                  class="btn btn-primary text-sm py-1"
                >
                  Add to Cart
                </button>
              </div>
            </div>
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

// Use our composables
const route = useRoute();
const { addToCart } = useCart();

// Data
const product = ref(null);
const relatedProducts = ref([]);
const loading = ref(true);
const error = ref(null);
const quantity = ref(1);

// Get product ID from route
const productId = computed(() => route.params.id);

// Fetch product on mount
onMounted(() => {
  fetchProduct();
});

// Fetch product details
const fetchProduct = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await fetch(`http://localhost:8000/api/products/${productId.value}`);
    const data = await response.json();

    if (data.success) {
      product.value = data.data;
      // Default quantity to 1 or the max available stock if less than 1
      quantity.value = Math.min(1, product.value.stock);
      fetchRelatedProducts();
    } else {
      error.value = 'Failed to load product';
    }
  } catch (err) {
    error.value = 'An error occurred while loading the product';
    console.error('Error fetching product:', err);
  } finally {
    loading.value = false;
  }
};

// Fetch related products
const fetchRelatedProducts = async () => {
  if (!product.value || !product.value.category_id) return;

  try {
    const response = await fetch(`http://localhost:8000/api/products?category_id=${product.value.category_id}&per_page=4`);
    const data = await response.json();

    if (data.success) {
      // Filter out the current product
      relatedProducts.value = data.data.data.filter(p => p.id !== product.value.id).slice(0, 4);
    }
  } catch (err) {
    console.error('Error fetching related products:', err);
  }
};

// Quantity handlers
const increaseQuantity = () => {
  if (product.value && quantity.value < product.value.stock) {
    quantity.value++;
  }
};

const decreaseQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--;
  }
};

// Add to cart with selected quantity
const addToCartWithQuantity = () => {
  if (product.value && product.value.stock > 0) {
    addToCart(product.value, quantity.value);
    // Show toast notification
    alert(`${quantity.value} ${product.value.name}${quantity.value > 1 ? 's' : ''} added to cart!`);
  }
};
</script>
