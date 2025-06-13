// Composable for API functionality
export const useApi = () => {
  const config = useRuntimeConfig();
  const baseUrl = config.public.apiUrl;
  const token = ref('');

  // Initialize token from localStorage on client side
  onMounted(() => {
    if (process.client) {
      token.value = localStorage.getItem('token') || '';
    }
  });

  // Set authentication token
  const setToken = (newToken) => {
    token.value = newToken;
    if (process.client) {
      localStorage.setItem('token', newToken);
    }
  };

  // Clear authentication token
  const clearToken = () => {
    token.value = '';
    if (process.client) {
      localStorage.removeItem('token');
    }
  };

  // Base fetch function with authentication
  const apiFetch = async (endpoint, options = {}) => {
    const url = `${baseUrl}${endpoint}`;

    // Setup headers
    const headers = {
      'Content-Type': 'application/json',
      ...(options.headers || {})
    };

    // Add auth token if available
    if (token.value) {
      headers['Authorization'] = `Bearer ${token.value}`;
    }

    try {
      const response = await fetch(url, {
        ...options,
        headers
      });

      // Parse response
      const data = await response.json();

      // Handle error responses
      if (!response.ok) {
        throw {
          status: response.status,
          message: data.message || 'API request failed',
          errors: data.errors || {}
        };
      }

      return data;
    } catch (error) {
      console.error('API error:', error);
      throw error;
    }
  };

  // GET request
  const get = (endpoint, params = {}) => {
    const queryString = new URLSearchParams(params).toString();
    const url = queryString ? `${endpoint}?${queryString}` : endpoint;
    return apiFetch(url, { method: 'GET' });
  };

  // POST request
  const post = (endpoint, data = {}) => {
    return apiFetch(endpoint, {
      method: 'POST',
      body: JSON.stringify(data)
    });
  };

  // PUT request
  const put = (endpoint, data = {}) => {
    return apiFetch(endpoint, {
      method: 'PUT',
      body: JSON.stringify(data)
    });
  };

  // DELETE request
  const del = (endpoint) => {
    return apiFetch(endpoint, { method: 'DELETE' });
  };

  // Check if user is authenticated
  const isAuthenticated = computed(() => !!token.value);

  return {
    get,
    post,
    put,
    del,
    setToken,
    clearToken,
    isAuthenticated
  };
};
