# Dynamic Route Navigation

```html
<!-- Navigate to dynamic routes -->
<button onclick="redirect('post', 'GET', '123')">View Post</button>
<button onclick="redirect('profile', 'GET', userId)">User Profile</button>

<!-- With variables -->
<script>
    const productId = "456";
    function viewProduct() {
        redirect('product', 'GET', productId);
    }
</script>
<button onclick="viewProduct()">View Product</button>
```
