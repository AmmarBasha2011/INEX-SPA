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
