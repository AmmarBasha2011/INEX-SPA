# INEX SPA Documentation

### Introduction

INEX SPA is a high-performance, lightweight PHP framework designed for modern web applications. It offers rapid execution (1ms response time) and seamless integration with Apache and PHP environments.

### Features

* **Ultra-Fast Performance:** Executes within 1ms on any Apache/PHP server.
* **Built-in CLI (Ammar CLI):** Provides a powerful command-line interface.
* **Routing System:** Supports dynamic and static routes.
* **Database Management:** Easily create, delete, and manage database tables.
* **Session Management:** Similar to PHP's `$_SESSION` but optimized for speed.
* **Localization Support:** JSON-based multi-language support.
* **File-Based Caching:** Efficient caching for optimized performance.
* **Rate Limiter:** Protects against excessive requests.
* **Layouts System:** Reduces code duplication.
* **Live Data Submission:** Send data without reloading the page.
* **Sitemap Generator:** Automatically generates sitemaps for SEO.
* **Import System:** Install third-party libraries easily.

### Installation

To install INEX SPA, clone the repository and set up your server:

```bash
# Clone the repository
git clone https://github.com/AmmarBasha2011/INEX-SPA.git my-project
cd my-project

# Set correct permissions
chmod -R 755 .
```

Ensure your Apache server has mod\_rewrite enabled.

### Usage

#### Running the CLI

INEX SPA provides a command-line tool `Ammar CLI` to automate tasks.

```bash
php ammar list:routes  # List all routes
php ammar make:route -1 home -2 no/yes -3 GET -4 no/yes  # Create a route
php ammar run:db  # Execute all SQL files
```

#### Creating a Route

Routes are defined in the `web` folder:

```php
// web/home.ahmed.php
<?php
return function() {
    return "Welcome to INEX SPA!";
};
```

#### Database Management

Creating a database migration:

```bash
php ammar make:db -1 create -2 Users
```

Executing all database migrations:

```bash
php ammar run:db
```

#### Managing Cron Jobs

INEX SPA allows you to define and manage scheduled tasks or cron jobs using Ammar CLI. These jobs are PHP classes stored in the `core/cronjobs/` directory. Each job file should be named after the class it contains (e.g., `MyTask.php` for class `MyTask`). Each class must define a public `execute()` method, which contains the logic for the job.

Available commands:

*   **Create a new cron job:**
    ```bash
    php ammar make:cronjob -1 YourJobClassName
    ```
    This will create a new file `core/cronjobs/YourJobClassName.php` with the following basic structure:
    ```php
    <?php

    class YourJobClassName {
        /**
         * The main logic for the cron job.
         */
        public function execute() {
            // Your cron job logic here
            echo "Cron job 'YourJobClassName' executed at " . date('Y-m-d H:i:s') . "\n";
        }
    }
    ```

*   **List all cron jobs:**
    ```bash
    php ammar list:cronjobs
    ```
    This command displays all available cron jobs.

*   **Run a cron job manually:**
    ```bash
    php ammar run:cronjob -1 YourJobClassName
    ```
    This is useful for testing your cron job logic.

*   **Delete a cron job:**
    ```bash
    php ammar delete:cronjob -1 YourJobClassName
    ```
    This will remove the specified cron job file.

**Note:** The actual scheduling of these cron jobs (e.g., running them at specific times) needs to be configured separately using your server's cron facilities (like `crontab` on Linux) to call the `php ammar run:cronjob -1 YourJobClassName` command.

### INEX SPA Cloud

INEX SPA Cloud is a free hosting service for INEX applications. It supports:

* **Subdomains & Subdirectories** for deployment.
* **File & Database Management** via IW Panel.

### Contributing

Contributions are welcome! Fork the repository, make changes, and submit a pull request.

### License

INEX SPA is open-source and available under the MIT License.
