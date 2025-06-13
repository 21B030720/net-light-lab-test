// Composable for cart functionality
export const useCart = () => {
  const cart = useState('cart', () => []);
  const isCartOpen = useState('isCartOpen', () => false);

  // Load cart from localStorage on client side
  onMounted(() => {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
      cart.value = JSON.parse(savedCart);
    }
  });

  // Watch for changes to cart and save to localStorage
  watch(cart, (newCart) => {
    if (process.client) {
      localStorage.setItem('cart', JSON.stringify(newCart));
    }
  }, { deep: true });

  // Add item to cart
  const addToCart = (product, quantity = 1) => {
    const existingItem = cart.value.find(item => item.product.id === product.id);

    if (existingItem) {
      // If item already exists in cart, update quantity
      existingItem.quantity += quantity;
    } else {
      // Otherwise add new item
      cart.value.push({
        product,
        quantity
      });
    }
  };

  // Remove item from cart
  const removeFromCart = (productId) => {
    cart.value = cart.value.filter(item => item.product.id !== productId);
  };

  // Update item quantity
  const updateQuantity = (productId, quantity) => {
    const item = cart.value.find(item => item.product.id === productId);
    if (item) {
      if (quantity <= 0) {
        removeFromCart(productId);
      } else {
        item.quantity = quantity;
      }
    }
  };

  // Clear cart
  const clearCart = () => {
    cart.value = [];
  };

  // Calculate total price
  const totalPrice = computed(() => {
    return cart.value.reduce((total, item) => {
      return total + (item.product.price * item.quantity);
    }, 0);
  });

  // Calculate total items
  const itemCount = computed(() => {
    return cart.value.reduce((count, item) => count + item.quantity, 0);
  });

  // Toggle cart sidebar visibility
  const toggleCart = () => {
    isCartOpen.value = !isCartOpen.value;
  };

  return {
    cart,
    isCartOpen,
    addToCart,
    removeFromCart,
    updateQuantity,
    clearCart,
    totalPrice,
    itemCount,
    toggleCart
  };
};
