# INEX SPA

A high-performance PHP framework similar to NextJS/React, but lighter and faster.

Built with PHP, under 100KB in size, and optimized for standard Apache servers.

## Features

- Minimal footprint and high performance
- Responsive design with animated gradient background
- Quick load time benchmark

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

## Repository

[Get Started on GitHub](https://github.com/AmmarBasha2011/INEX-SPA)
