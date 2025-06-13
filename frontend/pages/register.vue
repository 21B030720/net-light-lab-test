<template>
  <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
    <div class="py-4 px-6 bg-blue-600 text-white text-center">
      <h2 class="text-2xl font-bold">Register</h2>
    </div>
    <form @submit.prevent="register" class="py-6 px-8">
      <div v-if="errorMessage" class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
        {{ errorMessage }}
      </div>

      <div v-if="validationErrors.length > 0" class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
          <li v-for="error in validationErrors" :key="error">{{ error }}</li>
        </ul>
      </div>

      <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
        <input
          type="text"
          id="name"
          v-model="name"
          class="input"
          required
          autocomplete="name"
        />
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

      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          class="input"
          required
          autocomplete="new-password"
        />
        <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long</p>
      </div>

      <div class="mb-6">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <input
          type="password"
          id="password_confirmation"
          v-model="passwordConfirmation"
          class="input"
          required
          autocomplete="new-password"
        />
      </div>

      <div>
        <button
          type="submit"
          class="btn btn-primary w-full py-2"
          :disabled="isLoading"
        >
          {{ isLoading ? 'Registering...' : 'Register' }}
        </button>
      </div>

      <div class="mt-4 text-center">
        <p class="text-sm text-gray-600">
          Already have an account?
          <NuxtLink to="/login" class="text-blue-600 hover:underline">Login</NuxtLink>
        </p>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const isLoading = ref(false);
const errorMessage = ref('');
const validationErrors = ref([]);

// Get API composable
const { post, setToken } = useApi();

// Register function
const register = async () => {
  isLoading.value = true;
  errorMessage.value = '';
  validationErrors.value = [];

  // Basic validation
  if (password.value.length < 8) {
    validationErrors.value.push('Password must be at least 8 characters long');
  }

  if (password.value !== passwordConfirmation.value) {
    validationErrors.value.push('Passwords do not match');
  }

  if (validationErrors.value.length > 0) {
    isLoading.value = false;
    return;
  }

  try {
    // Call register API
    const response = await fetch('http://localhost:8000/api/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value
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

      // Redirect to home page
      navigateTo('/');
    } else {
      // Handle validation errors from the API
      if (data.errors) {
        Object.values(data.errors).forEach(error => {
          validationErrors.value.push(error[0]);
        });
      } else {
        errorMessage.value = data.message || 'Registration failed. Please try again.';
      }
    }
  } catch (error) {
    console.error('Registration error:', error);
    errorMessage.value = 'An error occurred during registration. Please try again.';
  } finally {
    isLoading.value = false;
  }
};
</script>
