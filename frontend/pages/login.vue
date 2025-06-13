<template>
  <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
    <div class="py-4 px-6 bg-blue-600 text-white text-center">
      <h2 class="text-2xl font-bold">Login</h2>
    </div>
    <form @submit.prevent="login" class="py-6 px-8">
      <div v-if="errorMessage" class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
        {{ errorMessage }}
      </div>

      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
        <input
          type="email"
          id="email"
          v-model="email"
          class="input"
          required
          autocomplete="email"
        />
      </div>

      <div class="mb-6">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          class="input"
          required
          autocomplete="current-password"
        />
      </div>

      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
          <input
            id="remember-me"
            type="checkbox"
            v-model="rememberMe"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
          />
          <label for="remember-me" class="ml-2 block text-sm text-gray-700">
            Remember me
          </label>
        </div>
      </div>

      <div>
        <button
          type="submit"
          class="btn btn-primary w-full py-2"
          :disabled="isLoading"
        >
          {{ isLoading ? 'Logging in...' : 'Login' }}
        </button>
      </div>

      <div class="mt-4 text-center">
        <p class="text-sm text-gray-600">
          Don't have an account?
          <NuxtLink to="/register" class="text-blue-600 hover:underline">Register</NuxtLink>
        </p>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const email = ref('');
const password = ref('');
const rememberMe = ref(false);
const isLoading = ref(false);
const errorMessage = ref('');

// Get API composable
const { post, setToken } = useApi();

// Login function
const login = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  try {
    // Call login API
    const response = await fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value
      })
    });

    const data = await response.json();

    if (data.success) {
      // Save token and user data
      const token = data.data.access_token;
      const user = data.data.user;

      // Store in localStorage
      localStorage.setItem('token', token);
      localStorage.setItem('user', JSON.stringify(user));

      // Set token in API composable
      setToken(token);

      // Redirect to home page or previous page
      navigateTo('/');
    } else {
      errorMessage.value = data.message || 'Invalid login credentials';
    }
  } catch (error) {
    console.error('Login error:', error);
    errorMessage.value = 'An error occurred during login. Please try again.';
  } finally {
    isLoading.value = false;
  }
};
</script>
