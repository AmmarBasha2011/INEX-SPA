# Server-Side Handling

Create route handlers for both the submission and redirect:

```php
// web/saveUserData_request_POST.ahmed.php
<?php
validateCsrfToken();
$_SESSION['user'] = [
    'username' => $_POST['username'],
    'email' => $_POST['email'],
    'id' => $_POST['id'],
    'role' => $_POST['role']
];
?>

// web/getUserData_request_GET.php
<?php
if(isset($_SESSION['user'])) {
    echo json_encode($_SESSION['user']);
}
?>
```
