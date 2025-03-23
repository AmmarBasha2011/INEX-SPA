# Basic Form Implementation

```html
<form id="userForm">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" class="form-control" required>
    </div>
    <button type="button" onclick="submitData('saveUserData', ['username', 'email'])">
        Save Data
    </button>
</form>
```

#### Advanced Usage

The `submitData()` function supports multiple parameters:

```javascript
submitData(
    routeName = '',           // Route to send data to
    inputIds = [],       // Array of input IDs to collect
    requestType = 'POST',// HTTP method (POST/GET/PUT/DELETE)
    redirectRoute = '',  // Optional route to redirect after success
    customValues = []    // Optional additional data to send
)
```

#### Example with Custom Values and Redirect

```html
<button onclick="submitData(
    'saveUserData',
    ['username', 'email'],
    'POST',
    'getUserData',
    [
        {'id': 123},
        {'role': 'admin'}
    ]
)">Save & View</button>
```
