# Automaticly CSRF Security

Now, when you add form to page, add `csrf_token` input automaticly:

* First, create a basic form
* Second, when send to `file.ahmed.php`, add this line for check

```php
<?php
validateCsrfToken();
?>
```

If there any error in token, request will be stop.
