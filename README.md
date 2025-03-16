# INEX SPA

A high-performance PHP framework similar to NextJS/React, but lighter and faster.

Built with PHP, under 1M in size, and optimized for standard Apache servers.

## Our AI Assistant
Now, our Framework have an AI Assistant called "INEX SPA Helper". but this is Beta Version. and may cause a ton of errors.

For try:
[https://udify.app/chat/yPoqBBkpHgJbEfg0](https://udify.app/chat/yPoqBBkpHgJbEfg0)

## Our Blog
Now, our Team have a Blog for all new news called "INEX Blog".

For try:
[https://inexteamblog.blogspot.com/](https://inexteamblog.blogspot.com/)


## Ammar Helper
Now, our Framework have a CLI called `Ammar`, likely to `artisan` in `laravel`:

### make:route
You can create a route by `php ammar make:route` and will ask you:
- 1- What's route name?
- 2- Is this route dynamic? (yes/no):
- 3- What's available Type of request (GET, POST, PUT, PATCH, DELETE):

then will be create a file

### make:db
You can create a db file by `php ammar make:db` and will ask you:
- 1- What's the DB file for (create, delete, addFieldTo):
- 2- What's table name?

### delete:route
You can delete a route by `php ammar delete:route` and will ask you:
- 1- What's route name?

### delete:db
You can delete a db file by `php ammar delete:db` and will ask you:
- 1- What's the DB file for (create, delete, addFieldTo):
- 2- What's table name?

### list
You can list all commands by `php ammar list`.

### list:routes
You can list all routes by `php ammar list:routes`.

### list:db
You can list all `db` folder files by `php ammar list:db`.

### run:db
You can run the `db` folder `.sql` files by `php ammar run:db`.

### make:cache
You can create a new cache by `php ammar make:cache` and will ask you:
- 1- Enter cache key:
- 2- Enter cache value:
- 3- Enter expiration time (in seconds):

### get:cache
You can get a cache value by `php ammar get:cache` and will ask you:
- 1- Enter cache key:

### update:cache
You can update a cache value by `php ammar update:cache` and will ask you:
- 1- Enter cache key:
- 2- Enter new cache value:

### delete:cache
You can delete a cache by `php ammar delete:cache` and will ask you:
- 1- Enter cache key:

### Inline Command
You can create or delete a file by one command like
- php ammar make:route -1 `routeName` -2 `yes/no` -3 `RequestType`
- php ammar make:db -1 `dbFileFor` -2 `tableName`
- php ammar delete:route -1 `routeName`
- php ammar delete:db -1 `dbFileFor` -2 `tableName`
- php ammar make:cache -1 `cacheKey` -2 `cacheValue` -3 `expirationTime`
- php ammar get:cache -1 `cacheKey`
- php ammar update:cache -1 `cacheKey` -2 `cacheValue`
- php ammar delete:cache -1 `cacheKey`

## FrameWork Structure

### Full Structure
```
my-project/
├── errors/
│   ├── 400.php
│   ├── 401.php
│   ├── 403.php
│   ├── 404.php
│   ├── 405.php
│   ├── 406.php
│   ├── 407.php
│   ├── 408.php
│   ├── 409.php
│   ├── 410.php
│   ├── 411.php
│   ├── 412.php
│   ├── 413.php
│   ├── 414.php
│   ├── 415.php
│   ├── 500.php
│   ├── 502.php
│   └── 503.php
│   └── 504.php
├── functions/
│   ├── JS/
│   │   ├── csrfToken.js
│   │   ├── popstate.js
│   │   ├── redirect.js
│   │   └── submitData.js
│   ├── PHP/
│   │   ├── classes/
│   │   │   └── Database.php
│   │   ├── executeSQLFilePDO.php
│   │   ├── generateCsrfToken.php
│   │   ├── getEnvValue.php
│   │   ├── getPage.php
│   │   ├── getSlashData.php
│   │   ├── getWebsiteUrl.php
│   │   ├── getWEBSITEURLValue.php
│   │   ├── redirect.php
│   │   └── validateCsrfToken.php
├── cache/
├── db/
├── public/
├── web/
├── .env
├── .htaccess
├── index.php
├── LICENSE.md
├── README.md
└── SECURITY.md
```

### FrameWork Folders
```
my-project/
├── cache/
├── errors/
├── functions/
├── index.php
└── .htaccess
```
Don't touch them, if you don't know what are you doing

### User Folders
```
my-project/
├── public/
├── web/
└── db/
```

#### Public Folder
This is folder for put public files like `style/index.css` or `style/home.css` and you can access from:
- http://localhost/style/index.css
- http://localhost/style/home.css

##### Why i use this?
You can use this for put your style or script or anything else in a folder in public and can access as a normal file on server.

#### Web Folder
This is folder for put your server code and define routes like `index.php` or `createPost_request_POST.php` or `post_dynamic.php` or any something else.

##### Why i use this?
You can use this for put your application in own place and use all functions from our.

#### db folder
This is folder for put your mysql files with .sql extension by update `.env`:
```ini
DB_CHECK=true
```
then add all mysql (.sql) files in this folder and will be run on every open website event do.
and this is Names of these .sql files you can use as an example:
- createPostTable_2025_9_1_3_6_15.sql
- deletePostTable_2025_10_2_4_6_20.sql
- addUserToPostTable_2025_9_5_22_52_23.sql

You can name like this [create/delete/addFieldTo][table_name]Table_[Year]_[Month]_[Day]_[Hour]_[Minute]_[Second].sql.

## Features

- Minimal footprint and high performance
- Responsive design with animated gradient background
- Quick load time benchmark

### Cache System
Now, you have a cache system:
- First update `.env`:
```ini
USE_CACHE=true
```

#### Create a new Cache
You can create a new Cache by:
```php
<?php
setCache(`key`, `value`, `expiration`[In Seconds]=3600);
?>
```
like:
```php
<?php
setCache('username', 'INEX SPA', 3600);
?>
```

#### Get a Cache Value
You can get a value of cache key (if still found):
```php
<?php
getCache(`key`);
?>
```
like:
```php
<?php
getCache('username'); // INEX SPA
?>
```

#### Update Cache Value
You can update a cache value of cache key (if still found):
```php
<?php
updateCache(`key`, `newValue`);
?>
```
like:
```php
<?php
updateCache('username', 'INEX SPA Framework'); // INEX SPA Framework
?>
```

#### Delete Cache
You can delete a cache (if still found):
```php
deleteCache(`key`);
```
like:
```php
deleteCache('username');
```

### Run MySQL Commands
Now, you can check Database by files:
- Update `.env`
```ini
DB_CHECK=true
```
- Create a MySQL files in `db` folder with `.sql` extension

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
- [400-504] numbers.php
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
- db.php
- executeSQLFilePDO.php
- Cache.php
- setCache.php
- updateCache.php
- getCache.php
- deleteCache.php

### Don't delete public/errors
Don't delete public/errors folder or [public/errors/style.css](public/errors/style.css)

### Don't delete cache
Don't delete [cache](cache) folder

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

### Don't use DB_CHECK at production
Don't use `DB_CHECK` in `.env` and set to `false` (Not Recommended) beacuse this is may take a long time for respond.

### Not Found route as dynamic and requestType
Not found a route will be dynamic and check request type at own file. this is unSupported for our privacy policy.

### You can use any class or function without require_once
Don't use require_once to load any framework code (Not Recommended) beacuse our framework load this automaticly. You only use a function and we will do everything for it.

### Naming of routes
This is Naming of routes:

#### Create form Route
if you build a create form route, name like this `create[Name].php`
then in second file, name like this `[Name]/create_request_POST.php`
like:
- web/createBlog.php
- web/Blog/create_request_POST.php

#### Edit Form Route
if you build a edit form route, name like this `edit[Name]_dynamic.php`
then in second file, name like this `[Name]/edit_request_POST.php`
like:
- web/editBlog_dynamic.php
- web/Blog/edit_request_POST.php

#### Delete Form Route
if you build a delete form route, name like this `delete[Name]_dynamic.php`
then in second file, name like this `[Name]/delete_request_POST.php`
like:
- web/deleteBlog_dynamic.php
- web/Blog/delete_request_POST.php

#### Show Forms Route
if you build a show forms route, name like this `[Name].php` or `index.php`
like:
- web/index.php
- web/Blog

## Best Practices

### Code Organization
- Keep route files in dedicated folders within `web/` directory
- Follow consistent naming conventions for routes and files
- Group related functionality in subdirectories

### Security
- Always validate CSRF tokens on forms
- Enable HTTPS in production environments
- Sanitize user input before database operations
- Never expose sensitive data in public folders

### Performance
- Set `DB_CHECK=false` in production
- Minimize JavaScript usage between page transitions 
- Use the public folder for static assets only
- Keep dynamic route handlers lightweight

### Development
- Test thoroughly before deploying changes
- Document any custom route patterns
- Follow the naming conventions for routes and files
- Back up database before running migrations

### Database
- Use prepared statements for queries
- Keep SQL files organized by date
- Test migrations in development first
- Follow table naming conventions

## Repository

[Get Started on GitHub](https://github.com/AmmarBasha2011/INEX-SPA)
