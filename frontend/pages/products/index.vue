<template>
  <div>
    <div class="mb-8">
      <h1 class="text-3xl font-bold mb-4">Products</h1>
      <div class="flex flex-wrap gap-4">
        <!-- Category filter -->
        <div class="relative w-full md:w-auto mb-4">
          <select
            v-model="selectedCategory"
            @change="filterProducts"
            class="input pr-8 appearance-none"
          >
            <option value="">All Categories</option>
            <option
              v-for="category in categories"
              :key="category.id"
              :value="category.id"
            >
              {{ category.name }}
            </option>
          </select>
          <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </div>
        </div>

        <!-- Search input -->
        <div class="relative w-full md:w-auto flex-grow mb-4">
          <input
            v-model="searchQuery"
            @input="filterProducts"
            type="text"
            placeholder="Search products..."
            class="input pl-10"
          />
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading state -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
      <p class="text-gray-600">Loading products...</p>
    </div>

    <!-- Error state -->
    <div v-else-if="error" class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
      <p>{{ error }}</p>
      <button @click="fetchProducts" class="underline mt-2">Try again</button>
    </div>

    <!-- Empty state -->
    <div v-else-if="filteredProducts.length === 0" class="text-center py-12">
      <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <h3 class="text-lg font-semibold mb-2">No products found</h3>
      <p class="text-gray-600 mb-4">Try adjusting your search or filter to find what you're looking for.</p>
      <button @click="clearFilters" class="btn btn-primary">Clear Filters</button>
    </div>

    <!-- Products grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <div
        v-for="product in filteredProducts"
        :key="product.id"
        class="card hover:shadow-lg transition-shadow"
      >
        <NuxtLink :to="`/products/${product.id}`">
          <img
            :src="product.image_url ? `http://localhost:8000/storage/${product.image_url}` : '/placeholder.png'"
            :alt="product.name"
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <div class="flex justify-between items-start mb-2">
              <h3 class="font-semibold">{{ product.name }}</h3>
              <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                {{ product.category ? product.category.name : 'Uncategorized' }}
              </span>
            </div>
            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ product.description }}</p>
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

    <!-- Pagination -->
    <div v-if="pagination && filteredProducts.length > 0" class="mt-8 flex justify-center">
      <div class="flex space-x-1">
        <button
          @click="changePage(pagination.current_page - 1)"
          :disabled="pagination.current_page === 1"
          class="px-4 py-2 border rounded"
          :class="pagination.current_page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-blue-600 hover:bg-blue-50'"
        >
          Previous
        </button>

        <button
          v-for="page in getPageNumbers()"
          :key="page"
          @click="changePage(page)"
          class="px-4 py-2 border rounded"
          :class="pagination.current_page === page ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 hover:bg-blue-50'"
        >
          {{ page }}
        </button>

        <button
          @click="changePage(pagination.current_page + 1)"
          :disabled="pagination.current_page === pagination.last_page"
          class="px-4 py-2 border rounded"
          :class="pagination.current_page === pagination.last_page ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-blue-600 hover:bg-blue-50'"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';

// Use our composables
const { addToCart } = useCart();
const route = useRoute();

// Data
const products = ref([]);
const filteredProducts = ref([]);
const categories = ref([]);
const loading = ref(true);
const error = ref(null);
const searchQuery = ref('');
const selectedCategory = ref('');
const pagination = ref(null);
const currentPage = ref(1);

// Watch for query params
onMounted(() => {
  // Check for category in query params
  if (route.query.category) {
    selectedCategory.value = route.query.category;
  }

  // Check for search in query params
  if (route.query.search) {
    searchQuery.value = route.query.search;
  }

  // Check for page in query params
  if (route.query.page) {
    currentPage.value = parseInt(route.query.page);
  }

  // Fetch data
  fetchCategories();
  fetchProducts();
});

// Watch for filter changes to update URL
watch([searchQuery, selectedCategory, currentPage], () => {
  updateQueryParams();
});

// Fetch categories
const fetchCategories = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/categories');
    const data = await response.json();
    categories.value = data.data;
  } catch (err) {
    console.error('Error fetching categories:', err);
  }
};

// Fetch products
const fetchProducts = async () => {
  loading.value = true;
  error.value = null;

  try {
    let url = `http://localhost:8000/api/products?page=${currentPage.value}`;

    if (selectedCategory.value) {
      url += `&category_id=${selectedCategory.value}`;
    }

    if (searchQuery.value) {
      url += `&search=${encodeURIComponent(searchQuery.value)}`;
    }

    const response = await fetch(url);
    const data = await response.json();

    if (data.success) {
      products.value = data.data.data;
      filteredProducts.value = data.data.data;
      pagination.value = {
        current_page: data.data.current_page,
        last_page: data.data.last_page,
        per_page: data.data.per_page,
        total: data.data.total
      };
    } else {
      error.value = 'Failed to load products';
    }
  } catch (err) {
    error.value = 'An error occurred while loading products';
    console.error('Error fetching products:', err);
  } finally {
    loading.value = false;
  }
};

// Filter products (client-side filtering for demo purposes)
const filterProducts = () => {
  currentPage.value = 1; // Reset to first page when filtering
  fetchProducts(); // In a real app, we'd send these filters to the API
};

// Clear all filters
const clearFilters = () => {
  searchQuery.value = '';
  selectedCategory.value = '';
  currentPage.value = 1;
  fetchProducts();
};

// Change page
const changePage = (page) => {
  if (page < 1 || (pagination.value && page > pagination.value.last_page)) {
    return;
  }

  currentPage.value = page;
  fetchProducts();
};

// Get page numbers to display
const getPageNumbers = () => {
  if (!pagination.value) return [];

  const totalPages = pagination.value.last_page;
  const currentPageValue = pagination.value.current_page;

  // If 5 or fewer pages, show all
  if (totalPages <= 5) {
    return Array.from({ length: totalPages }, (_, i) => i + 1);
  }

  // Otherwise show current page, 2 before and 2 after when possible
  const pages = [];

  // Always include first page
  pages.push(1);

  // Start 2 before current page or after first page
  const startPage = Math.max(2, currentPageValue - 1);

  // End 2 after current page or before last page
  const endPage = Math.min(totalPages - 1, currentPageValue + 1);

  // Add ellipsis after first page if needed
  if (startPage > 2) {
    pages.push('...');
  }

  // Add pages between start and end
  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }

  // Add ellipsis before last page if needed
  if (endPage < totalPages - 1) {
    pages.push('...');
  }

  // Always include last page
  if (totalPages > 1) {
    pages.push(totalPages);
  }

  return pages;
};

// Update query params in URL
const updateQueryParams = () => {
  const query = {};

  if (selectedCategory.value) {
    query.category = selectedCategory.value;
  }

  if (searchQuery.value) {
    query.search = searchQuery.value;
  }

  if (currentPage.value > 1) {
    query.page = currentPage.value;
  }

  navigateTo({
    query
  });
};
</script>
