# Common Usage Patterns

```html
<!-- Navigation menu example -->
<nav>
    <a href="#" onclick="redirect('home'); return false;">Home</a>
    <a href="#" onclick="redirect('about'); return false;">About</a>
    <a href="#" onclick="redirect('contact'); return false;">Contact</a>
</nav>

<!-- Form submission success redirect -->
<script>
    function submitForm() {
        // Process form...
        redirect('success');
    }
</script>

<!-- Conditional navigation -->
<script>
    function checkAndRedirect() {
        if(userLoggedIn) {
            redirect('dashboard');
        } else {
            redirect('login');
        }
    }
</script>
```
