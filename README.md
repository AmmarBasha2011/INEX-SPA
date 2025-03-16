# INEX SPA

A high-performance PHP framework similar to NextJS/React, but lighter and faster.

Built with PHP, under 1M in size, and optimized for standard Apache servers.

## Features

- Minimal footprint and high performance
- Responsive design with animated gradient background
- Quick load time benchmark

### Automaticly CSRF Security
Now, when you add form to page, add `csrf_token` input automaticly:
- First, create a basic form
- Second, when send to `file.php`, add this line for check
```php
<?php
validateCsrfToken();
?>
```
If there any error in token, request will be stop.

### Get Website URL Without `getEnvValue` function
Now, you can get your website url without `getEnvValue` function:
- In PHP
```php
<?php
echo getWebsiteUrl();
?>
```
- In JS
```javascript
console.log(window.WEBSITE_URL);
```

### Enable HTTPS as required
Now, you can enable HTTPS as required:
- Update .env
```ini
REQUIRED_HTTPS=true
```

### Use Bootstrap
Now, you can use `bootstrap`:
- Update .env
```ini
USE_BOOTSTRAP=true
```

### Database Connection
Now, you can connect to database.
- First, update .env
```ini
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=inexspa
DB_USE=true
```
- Second, use in any part in application
```php
<?php
print_r(executeStatement("SELECT * FROM users WHERE id = ?", [1], true)) // SQL, Params=[], Is Return=true
?>
```

### Submit Data without Refresh
Now, you can submit data to php file without refresh.
- First, do the form
```html
<form>
    <label for="username">UserName:</label>
    <input type="text" id="username" />
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" />
    <br>
    <button type="button" onclick="submitData('saveUserData', ['username', 'email'], 'POST', 'getUserData')">Save Data</button> <!-- submitData(sendDataToRoute, InputIds=[], RequestType='POST', redirectedRouteAfterEnd='') -->
</form>
```
- Second, create web/saveUserData_request_POST.php
```php
<?php
session_start();
$_SESSION['username'] = $_POST['username'];
$_SESSION['email'] = $_POST['email'];
?>
```
- Third, create web/getUserData_request_GET.php
```php
session_start();
echo "Username: " . $_SESSION['username'];
echo "Email: " . $_SESSION['email'];
?>
```

### Check Request Type
Now, you can check request type from
- GET
- POST
- PUT
- DELETE
- PATCH

by create a file named like this [RouteName]_request_[RequestType].php like  
- getUserData_request_GET.php
- saveUserData_request_POST.php

and user can access by ```[URL]/[RouteName] without request_[RequestType]``` like
- http://localhost/getUserData
- http://localhost/saveUserData

### Create Dynamic Routes
Now, you can create dynamic routes by:

create a file named like this [RouteName]_dynamic.php like
- post_dynamic.php
- blog_dynamic.php

You can get a data by this
```php
echo $_GET['data']; // 1
```

and user can access by ```[URL]/[FileName] without _dynamin.php/[data]``` like
- http://localhost/post/1
- http://localhost/blog/2

### Redirect without refreshing
Now, you can go to another page without refresh like this
- Create web/index.php
```html
<button onclick="redirect('login')">Login</button> <!-- redirect(RouteName) -->
```
- Create web/login.php

### Get Values From .env file
Now, you can get values from .env file with comments (if found on same line) like
```php
<?php
echo getEnvValue("WEBSITE_URL"); // http://localhost
?>
```

### Get Data from Twice Slash
Now, you can get data from twice slash like this:
- If there link like this http://localhost/post/1
- Get a slash data
```php
<?php
echo getSlashData("post/1"); // {"before": "post", "after": "1"}
?>
```

## Usage

Edit the [web/index.php](web/index.php) to start customizing the application.

## Notes

### Don't use these names in files
Don't use these names (Not Recommend):
- web.php
- public.php
- functions.php
- errors.php
- js.php
- php.php
- [400-504].php
- generateCsrfToken.php
- getEnvValue.php
- getPage.php
- getSlashData.php
- getWebsiteUrl.php
- getWEBSITEURLValue.php
- redirect.php
- validateCsrfToken.php
- classes.php
- Database.php
- csrfToken.js
- popstate.js
- redirect.js
- submitData.js

### Use 1 form in 1 Page
Don't add more than 1 form in page (Not Recommend) like:
- web/index.php
```html
<form>
    <label> Name: </label>
    <input type="text" id="name" />
</form>
<form>
    <label> Email: </label>
    <input type="text" id="email" />
</form>
```
You have two solutions to solve this
- First, put all forms in own form:
```html
<form>
    <label> Name: </label>
    <input type="text" id="name" />
    <br>
    <label> Email: </label>
    <input type="text" id="email" />
</form>
```
- Second, put two forms in two pages:
- - Create web/index.php:
```html
<button type="button" onclick="redirect('email')" >Enter Email </button>
<form>
    <label> Name: </label>
    <input type="text" id="name" />
</form>
```
- - Create web/email.php
```html
<button type="button" onclick="redirect('')" >Enter Name </button>
<form>
    <label> Email: </label>
    <input type="text" id="email" />
</form>
```

### Don't delete public/errors
Don't delete public/errors folder or [public/errors/style.css](public/errors/style.css)

### Don't use 2 scripts in next 2 pages
Don't use 2 javascripts in next 2 pages (Not Recommend) like:
- Create web/index.php
```html
<button type='button' onclick='redirect("test")'>Go</button>
<script>console.log('runned');</script>
```
- Create web/test.php
```html
<script> redirect(''); </script>
```
Now, if you refresh page will be see `runned` in `console`, But try to click on button, you will be redirect to `web/test.php` then to `web/index.php`, in this case, the js code in `web/index.php` will be not work.


## Repository

[Get Started on GitHub](https://github.com/AmmarBasha2011/INEX-SPA)
