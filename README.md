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

### INEX SPA Cloud

INEX SPA Cloud is a free hosting service for INEX applications. It supports:

* **Subdomains & Subdirectories** for deployment.
* **File & Database Management** via IW Panel.

### Contributing

Contributions are welcome! Fork the repository, make changes, and submit a pull request.

### License

INEX SPA is open-source and available under the MIT License.
