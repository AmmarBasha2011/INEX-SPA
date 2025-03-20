# INEX SPA


A high-performance PHP framework similar to NextJS/React, but lighter and faster.


Built with PHP, under 1M in size, and optimized for standard Apache servers.


## INEX SPA Helper
INEX SPA Helper: A New Era of Assistance
Introducing the "INEX SPA Helper" — our beta version AI Assistant integrated into the framework. Please note that as it's still in the beta phase, you might encounter errors. We're continuously working on improvements to enhance stability and performance.
Feel free to try it out here: https://udify.app/chat/yPoqBBkpHgJbEfg0


## INEX Blog
tay Updated with INEX Blog
Stay connected with all the latest updates and insights on our products through the "INEX Blog".
Visit the blog here: https://inexteamblog.blogspot.com/


## Installation


### Using Git
Clone the repository using Git:
```bash
git clone https://github.com/AmmarBasha2011/INEX-SPA my-project
cd my-project
```


### Manual Download
1. Download the ZIP file from [GitHub releases](https://github.com/AmmarBasha2011/INEX-SPA/releases)
2. Extract to your project directory
3. Rename the folder if needed


## Getting Started


### Running Locally


#### Using PHP's Built-in Server
```bash
php -S localhost:8000
```
Then visit [http://localhost:8000](http://localhost:8000)


#### Using Apache
1. Place the files in your Apache web root
2. Configure virtual host if needed
3. Access through your domain/localhost


### Initial Configuration
1. Copy `.env.example` to `.env` and configure settings:
```bash
# On Unix/Linux/macOS
cp .env.example .env


# On Windows
copy .env.example .env
```


Note: Required for v3.7.6.5.8.1 and later versions. Contains essential configuration settings for database, API keys, and system features.
2. Update environment variables as needed
3. Set appropriate file permissions
4. Configure database credentials if using MySQL


### Verify Installation
- Homepage should display without errors
- Check error logs if issues occur
- Test basic routing functionality
- Confirm database connection if configured


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


### ask:helper
You can now ask INEX SPA Helper in command line by `php ammar ask:helper` and will ask you:
- 1- What's your question?


### ask:gemini
You can now ask Gemini in command line by `php ammar ask:gemini` and will ask you:
- 1- What's your question?


### serve
You can quickly start a development server using the built-in PHP server:

```bash
php ammar serve
```


This will launch the application at [http://localhost:8000](http://localhost:8000) by default.


To use a custom port:
```bash
php ammar serve -1 9000
```


**Important Note:** When using PHP's built-in server:
- Manual class loading may be required as automatic framework loading only works with Apache
- Some framework features might be limited
- Recommended for development only, not production use
- Apache server provides full framework functionality


Production deployments should use Apache or another production-grade web server.

### make:sitemap
To generate a sitemap.xml file automatically in the public directory, you only need to execute a single, simple command. Open your terminal and enter:
php ammar make:sitemap
This command will efficiently create a sitemap.xml file for you, ensuring it is placed in the correct public folder. This process is streamlined and straightforward, saving you the hassle of manually compiling your site's URLs. The generated sitemap will help enhance your website's search engine optimization by providing a clear structure for web crawlers.

### run:db
You can run the db folder .sql files by php ammar run:db.

### clear:cache
To efficiently manage your project's cache within the INEX SPA PHP Framework, you can remove all cache files stored in the cache folder by executing the following command:
php ammar clear:cache
This command is a helpful utility for freeing up disk space and ensuring that cached files are consistently up-to-date. Regularly clearing the cache can improve performance during development by ensuring that all recent changes are applied effectively without being hindered by outdated or corrupted cache files.


### clear:db
To clear all files in the db folder before migrating or after, you can use a single line command provided by the PHP script. Simply execute php ammar clear:db in your terminal. This command efficiently removes all existing database files, preparing the environment for a fresh migration. Make sure you have the appropriate permissions and have backed up any necessary data.

### clear:db:tables
You can delete all tables in your database, the real tables not files in dbfor migrate. by single line command `php ammar clear:db:tables`.
Before running the command to delete all tables in your database, it's important to emphasize the significance of this action. The tables you're targeting are the actual database tables, not just files in the db directory used for migration purposes. This is a critical distinction to understand to avoid unintentional data loss.
To proceed, you can execute a single line command using PHP to clear all the real tables from your database. The syntax for the command is as follows:
php ammar clear:db:tables
Executing this command will irrevocably delete all existing tables in your database, leading to the loss of all data stored within them. Ensure you have comprehensive backups or are absolutely certain that you no longer need the data before proceeding.

### clear:routes
To delete all routes in your application, you can use the following command:
php ammar clear:routes
Important Note
Please be aware that executing this command will permanently delete all existing routes within the application. This includes:
Normal Routes: Common routes defined within the application.
Dynamic Routes: Routes generated dynamically during application runtime.
RequestTypeRoute: Specific routes handling various request types.
Other Routes: Any additional custom or plugin-based routes not classified above.
Running this command will reset the entire routing configuration, and consequently, all application pages will revert to a default state, effectively showing no content or endpoints.
Precaution
Before proceeding with this command, ensure that you have properly backed up your routing configuration or database, if necessary, to prevent any accidental data loss.
Consider consulting with your development team or reviewing documentation to fully understand the impact and ensure this action


### Inline Commands
The framework provides convenient single-line commands for common operations:


#### Route Management
```bash
# Create a route
php ammar make:route -1 routeName -2 isDynamic -3 requestType
# Example: php ammar make:route -1 blog -2 yes -3 GET


# Delete a route
php ammar delete:route -1 routeName
# Example: php ammar delete:route -1 blog
```


#### Database Operations
```bash
# Create database file
php ammar make:db -1 operationType -2 tableName
# Example: php ammar make:db -1 create -2 users


# Delete database file
php ammar delete:db -1 operationType -2 tableName
# Example: php ammar delete:db -1 create -2 users
```


#### Cache Operations
```bash
# Create cache entry
php ammar make:cache -1 key -2 value -3 expirationSeconds
# Example: php ammar make:cache -1 user_profile -2 "data" -3 3600


# Get cache value
php ammar get:cache -1 key
# Example: php ammar get:cache -1 user_profile


# Update cache
php ammar update:cache -1 key -2 newValue
# Example: php ammar update:cache -1 user_profile -2 "updated_data"


# Delete cache entry
php ammar delete:cache -1 key
# Example: php ammar delete:cache -1 user_profile
```


#### AI Assistance
```bash
# Ask INEX SPA Helper
php ammar ask:helper -1 "your question"
# Example: php ammar ask:helper -1 "How do I create a dynamic route?"


# Ask Gemini AI
php ammar ask:gemini -1 "your question"
# Example: php ammar ask:gemini -1 "What are PHP best practices?"
```


#### Development Server
```bash
# Start server with custom port
php ammar serve -1 portNumber
# Example: php ammar serve -1 9000
```


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
│   │   ├── inexSpaHelper.php
│   │   ├── useGemini.php
│   │   └── validateCsrfToken.php
├── cache/
├── db/
├── public/
├── web/
├── .env.example
├── .htaccess
├── index.php
├── LICENSE.md
├── readme/
├── storage/
├── layouts/
├── ammar
├── SUMMARY.md
├── README.md
├── .gitignore
└── SECURITY.md
```


### FrameWork Folders
```
my-project/
├── cache/
├── errors/
├── functions/
├── readme/
├── storage/
├── ammar
├── README.md
├── SECURITY.md
├── LICENSE.md
├── SUMMARY.md
├── .gitignore
├── index.php
└── .htaccess
```
Don't touch them, if you don't know what are you doing


### User Folders
```
my-project/
├── public/
├── web/
├── layouts/
├── .env.example
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

#### layouts folder
This is folder for put duplicate layouts here.

## Features


- Minimal footprint and high performance
- Responsive design with animated gradient background
- Quick load time benchmark

### Layout Feature
INEX SPA PHP Framework introduces a powerful Layout feature that helps you eliminate code duplication (DRY - Don't Repeat Yourself) and structure your application efficiently. By using layouts, you can define a consistent structure across multiple pages with minimal code repetition.
How to Use Layouts in INEX SPA
Step 1: Create a Layout File
Layouts reside in the layouts/ folder. These files define the reusable structure of your application pages.
Create a new layout file at layouts/main.php:
// layouts/main.php
<title><?= $title; ?></title>
<body>
    <?= Layout::section('content'); ?>
</body>
This file serves as the main template where dynamic content will be injected.

Step 2: Create a Route Using the Layout
To use the layout, create a route file inside the web/ folder:
// web/index.php
<?php
Layout::start('content');
?>
    <h1>Hi, From INEX SPA</h1>
<?php
Layout::end();
Here, Layout::start('content') defines the section where dynamic content will be placed, and Layout::end() marks the end of that section.

Step 3: Render the Page with the Layout
Finally, complete the route file by rendering the layout:
Layout::render('main', 'index', ['title' => 'INEX SPA']);
This renders the index.php content inside layouts/main.php, passing variables like $title dynamically.

Conclusion
That's it! You've successfully implemented layouts in INEX SPA PHP Framework. This feature significantly reduces redundant code and keeps your application organized. Happy coding!

### Run DB Folder SQL files
To run the migration .sql files located within the db folder, you need to execute the following command in any part of your application:
runDB();
By executing this command, all SQL migration files will be processed, thereby updating your database schema as defined in the files within the db directory. This process ensures that your database structure aligns with the current version of your application, facilitating smooth transitions between different development stages.
Make sure that your database server is running and all necessary configurations are in place before executing the migration command to avoid potential errors or complications during

### SiteMap Generator
You can generate your application sitemap automatically, by in any part in application:
SitemapGenerator::generate();
That's all it!!!

### Use Rate Limiter
Rate Limiting Implementation Guide
To prevent API spamming, implement an effective rate limiting strategy. This document outlines steps to use the Rate Limiter feature, ensuring smooth and secure application performance.
Steps to Implement Rate Limiting
1. Update the Environment Configuration
Begin by updating your environment configuration file, usually named .env. Add or update the following configuration settings:
REQUESTS_PER_HOUR: Set this to your desired number of requests per hour. For example, REQUESTS_PER_HOUR=100 limits each client to 100 requests per hour.
USE_RATELIMITER: Enable the rate limiter by specifying USE_RATELIMITER=true.
2. Integrate Rate Limiting in Your Application
Incorporate the rate limiting feature directly into your application's codebase. This ensures that your API is protected from unwanted traffic and potential abuse. Use the following line of code to integrate rate limiting based on the client's IP address:
RateLimiter::check($_SERVER['REMOTE_ADDR']);
This function checks the number of requests from each IP address and restricts any excess within the defined limit.
Benefits of Rate Limiting
Implementing a robust rate limiting system helps in:
Preventing Abuse: Protects your API from excessive use and potential DDoS attacks.
Resource Management: Ensures efficient use of server resources by controlling load.
Enhanced Security: Reduces risk by providing an additional layer of cybersecurity checks.
Conclusion
By following these steps, you ensure that your API is shielded from spam and misuse. Adjusting the REQUESTS_PER_HOUR value allows you to tailor the level of access based on your application's needs. Regularly monitor API usage to fine-tune these settings for


### Application Title System


The framework provides a flexible way to manage page titles with automatic application name prefixing:


1. Configure title settings in `.env`:
```ini
USE_APP_NAME_IN_TITLE=true
APP_NAME="INEX SPA"
```


2. Use standard HTML title tags in your pages:
```html
<title>Welcome</title>
```


The framework will automatically transform titles to include the app name:
```html
<title>INEX SPA - Welcome</title>
```


#### Advanced Usage
- Disable for specific pages by setting `USE_APP_NAME_IN_TITLE=false`
- Custom separators can be configured in .env
- Supports dynamic titles from PHP variables
- Maintains SEO-friendly title structure
- Works with SPA page transitions


Example with dynamic title:
```php
<?php $pageTitle = "User Profile: " . $username; ?>
<title><?php echo $pageTitle; ?></title>
```


### Use INEX SPA Helper in code
Now, you can chat with `INEX SPA Helper` but by code:
- First, update `.env`:
```ini
GEMINI_API_KEY=
GEMINI_MODEL_ID=gemini-2.0-flash
GEMINI_ENDPOINT=https://generativelanguage.googleapis.com/v1beta/models/
```
- Second, in any part in the application:
```php
<?php
$inexspahelper = json_decode(inexSpaHelper(`question`), true);
print_r($inexspahelper);
?>
```
- - if: return success=true, return message. else: return success=false, return error:
```php
<?php
if ($inexspahelper['success'] == true) {
    echo "Response: " . $inexspahelper['message'];
} else {
    echo "Error: " . $inexspahelper['error'];
}
?>
```


### Use Gemini
Now, you can chat with `Gemini` but by code:
- First, update `.env`:
```ini
GEMINI_API_KEY=
GEMINI_MODEL_ID=gemini-2.0-flash
GEMINI_ENDPOINT=https://generativelanguage.googleapis.com/v1beta/models/
```
- Second, in any part in the application:
```php
<?php
$UseGemini = json_decode(useGemini(`question`, `knowledge`="", `instrcutions`="", `temperature`=0.7, `topK`=40, `topP`=0.95, `maxOutPutTokens`=2048), true);
print_r($UseGemini);
?>
```
- - if: return success=true, return message. else: return success=false, return error:
```php
<?php
if ($UseGemini['success'] == true) {
    echo "Response: " . $UseGemini['message'];
} else {
    echo "Error: " . $UseGemini['error'];
}
?>
```


### Cache System
Now, you have a cache system:
- First, update `.env`:
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


### Submit Data without Page Refresh
The framework provides client-side form submission functionality that enables AJAX-style data submission without page reloads.


#### Basic Form Implementation
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


#### Server-Side Handling
Create route handlers for both the submission and redirect:


```php
// web/saveUserData_request_POST.php
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


#### Important Notes:
- CSRF protection is automatically included
- Input validation should be done on both client and server
- Use meaningful route names for better maintainability
- Custom values are sent as POST/GET parameters
- Response handling is managed by the framework
- Supports all standard HTTP methods
- Maintains browser history state correctly


The framework automatically handles:
- Form data serialization
- AJAX request management
- Response processing
- Error handling
- State management
- Browser history


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


### Client-Side Navigation (Redirect without Refresh)
The framework provides smooth client-side navigation without page refreshes using the `redirect()` function.


#### Basic Redirection
```html
<!-- Simple redirect to a route -->
<button onclick="redirect('login')">Login</button>


<!-- Redirect with specific request type -->
<button onclick="redirect('dashboard', 'GET')">Dashboard</button>
```


#### Dynamic Route Navigation
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


#### Common Usage Patterns
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


#### Important Notes
- Always prevent default anchor behavior when using redirect()
- Request type defaults to 'GET' if not specified
- Dynamic parameter is optional
- Navigation history is properly maintained
- Browser back/forward buttons work as expected
- URL updates without page reload


The redirect function syntax:
```javascript
redirect(routeName = '', requestType = 'GET', dynamicParam = '')
```


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
- [400-401-403-404-405-406-407-408-409-410-411-412-413-414-415-500-502-503-504] numbers.php
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
- inexSpaHelper.php
- runDB.php
- useGemini.php
- ClearDBTables.php
- Layout.php
- RateLimiter.php
- SitemapGenerator.php
- addAppNameToHTML.js
- storage.php
- storage.js
- layouts.php
- layouts.js
- readme.php
- readme.js
- ammar.php
- ammar.js

### Critical Framework Components


#### Error Pages Directory
The `public/errors` directory and its `style.css` file are essential framework components:
- Required for proper error handling and display
- Contains styled templates for HTTP error codes
- Ensures consistent error presentation
- Do not modify or delete these files
- Framework functionality depends on their presence


#### Cache Directory
The `cache` directory is a critical system component:
- Required for caching functionality
- Manages temporary data storage
- Improves application performance
- Must maintain proper permissions
- Do not delete or relocate this directory


#### JavaScript Execution Conflicts
Avoid using JavaScript across consecutive page transitions:
```html
<!-- Issue: Scripts won't execute properly -->
Page 1: 
<button onclick='redirect("page2")'>Next</button>
<script>console.log('page1 script');</script>


Page 2:
<script>redirect('page1');</script>
```
Problems this causes:
- Script execution order becomes unpredictable
- Event handlers may not attach properly
- Memory leaks possible from orphaned listeners
- Page transitions can break functionality
- Browser history management issues


Best practices:
- Keep JavaScript isolated to single pages
- Use framework routing methods
- Implement proper state management
- Handle transitions through router events
- Avoid inline scripts when possible


#### Production Database Checks
The `DB_CHECK` setting should be disabled in production:
```ini
# Development only - Enable DB checks
DB_CHECK=true


# Production - Disable DB checks
DB_CHECK=false
```
Reasons to disable in production:
- Significant performance impact
- Unnecessary file system operations
- Increased server load
- Slower response times
- Security considerations


#### Route Type Restrictions
The framework enforces strict route typing:
- Dynamic routes cannot also be request-type routes
- Each route must have one clear purpose
- Mixed route types are not supported
- Maintains routing consistency
- Ensures predictable behavior
- Required for security policy compliance
- Helps prevent routing conflicts


Following these guidelines ensures:
- Better application stability
- Improved security
- Consistent behavior
- Easier maintenance
- Better performance


### Autoloading Framework Components
The framework implements an advanced autoloading system that automatically manages class and function dependencies. This means:


- Don't use `require_once`, `require`, `include_once` or `include` for framework files
- All framework classes and functions are automatically available
- The autoloader handles dependency resolution efficiently
- Proper namespacing ensures no conflicts between components
- Framework core files are loaded in optimal order
- Better performance by avoiding redundant file inclusions


Example of correct usage:
```php
// Correct - Direct usage
$result = executeStatement("SELECT * FROM users");


// Incorrect - Don't do this
require_once 'functions/PHP/classes/Database.php';
$result = executeStatement("SELECT * FROM users");
```


### Framework Core Isolation 
The framework core is designed to be self-contained and automatically managed. To maintain stability:


- Never manually include framework core files
- Don't modify the core loading sequence
- Let the framework handle all core dependencies
- Core functionality is initialized in the correct order
- Framework bootstrapping is handled automatically
- Prevents version conflicts and inconsistencies


This ensures reliable operation and makes upgrades smoother while reducing potential errors from manual file management.



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


### INEX SPA Helper in Command Line
You can use INEX SPA Helper both in code and through the command line interface. This functionality is powered by Google's Gemini AI model. Note that you'll need valid Gemini API credentials to use this feature. While the command line version offers helpful assistance, the quality and capabilities may differ from the online INEX SPA Helper available at udify.app.

### Additional Notes
- Route files should use consistent suffix naming (_dynamic, _request_GET, etc)
- Cache keys should be descriptive and namespaced to avoid conflicts
- Use environment variables for configuration instead of hardcoding values
- Keep framework core files unchanged to ensure smooth updates
- Test thoroughly on development before deploying to production
- Monitor cache usage to prevent memory issues
- Document any custom implementations or modifications
- Follow PHP best practices and coding standards
- Keep backups of critical application data
- Use version control for tracking changes


## Best Practices


### Code Organization
- Keep route files in dedicated folders within `web/` directory
- Follow consistent naming conventions for routes and files
- Group related functionality in subdirectories
- Organize controllers, models and views separately
- Use meaningful file and folder names
- Keep configuration files in root directory


### Security
- Always validate CSRF tokens on forms
- Enable HTTPS in production environments 
- Sanitize user input before database operations
- Never expose sensitive data in public folders
- Use environment variables for sensitive configuration
- Implement proper session management
- Add rate limiting for API endpoints
- Keep dependencies updated
- Follow secure password hashing practices


### Performance
- Set `DB_CHECK=false` in production
- Minimize JavaScript usage between page transitions
- Use the public folder for static assets only
- Keep dynamic route handlers lightweight
- Enable caching where appropriate
- Optimize database queries
- Minify CSS/JS assets
- Use compression for responses
- Implement database indexing


### Development
- Test thoroughly before deploying changes
- Document any custom route patterns
- Follow the naming conventions for routes and files
- Back up database before running migrations
- Use version control effectively
- Write clear comments and documentation
- Follow PSR coding standards
- Use meaningful variable names
- Implement proper error handling
- Keep the codebase DRY (Don't Repeat Yourself)


### Database
- Use prepared statements for queries
- Keep SQL files organized by date
- Test migrations in development first
- Follow table naming conventions
- Implement proper foreign key relationships
- Use appropriate data types
- Keep tables normalized
- Document database schema changes
- Backup data regularly
- Monitor query performance


### Maintenance
- Monitor error logs regularly
- Keep regular backups
- Update dependencies securely
- Document system requirements
- Maintain clear deployment procedures
- Track breaking changes
- Plan for scalability
- Implement proper logging
- Have rollback procedures


## Repository


[Get Started on GitHub](https://github.com/AmmarBasha2011/INEX-SPA)