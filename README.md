# INEX SPA Framework

**INEX SPA** is a lightweight and ultra-fast PHP framework developed by the INEX Team. It is designed for building high-performance, scalable, and secure web applications with ease.

## Table of Contents

- [About INEX](#-about-inex-)
- [Getting Started](#-getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Configuration](#configuration)
- [Features](#-features-)
  - [Cronjob Management](#cronjob-management)
  - [Built-in Motion Engine](#built-in-motion-engine)
  - [INEX DevTools](#-inex-devtools)
  - [Built-in Fetch Layer](#-built-in-fetch-layer)
  - [SecurityV2 Class Reference](#-securityv2-class-reference)
  - [Component System](#-component-system)
  - [Mail Service](#-mail-service)
  - [Testing Tools](#-testing-tools)
- [Projects](#-projects-)
- [Contact](#-contact-)
- [License](#-license-)

## 🚀 INEX Team Documentation 💡🌍

### 🔧 About INEX ✨🌟

INEX is a professional tech team specializing in high-performance software solutions. The team is renowned for developing **INEX SPA**, a lightweight and ultra-fast PHP framework. INEX is dedicated to innovation, efficiency, and open-source contributions. The team believes in the power of technology to transform industries, enhance efficiency, and provide sustainable solutions. Our projects reflect our commitment to pushing the boundaries of software development and making robust, scalable, and high-performance applications accessible to everyone. 🚀🔥🌐

We continuously explore new frontiers in web development, artificial intelligence, cloud hosting, and software automation. Our team is composed of passionate developers, engineers, and researchers who work together to create tools that improve the lives of developers and businesses alike. Our collaborative and open-source approach ensures that we are always at the forefront of technology, delivering practical and efficient solutions to real-world challenges. 💡💻🌍

## 🏁 Getting Started

This guide will help you get a local copy of the INEX SPA framework up and running on your local machine for development and testing purposes.

### Prerequisites

Before you begin, ensure you have the following installed on your system:

-   **PHP**: Version 8.0 or higher.
-   **Composer**: For managing PHP dependencies.
-   **MySQL**: Or any other compatible database.
-   **Git**: For version control.

### Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/AmmarBasha2011/INEX.git
    cd INEX
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

3.  **Set up the database:**
    -   Create a new database in MySQL.
    -   Run the SQL files in the `db/` directory to create the necessary tables.

### Configuration

1.  **Create a `.env` file:**
    -   Copy the `.env.example` file to a new file named `.env`.
    -   Update the `.env` file with your database credentials and other environment-specific settings.

2.  **Set up the web server:**
    -   Configure your web server (e.g., Apache, Nginx) to point to the `public/` directory.
    -   Ensure that URL rewriting is enabled to handle clean URLs.

## 🌟 Features 🌟

### Cronjob Management

This application includes a cronjob management system to schedule and run background tasks. Tasks are defined as PHP classes and can be managed using the `ammar` CLI.

#### Cron Runner

The core script for executing cron tasks is `core/cron/cron_runner.php`. This script is designed to be called by your system's cron daemon (e.g., crontab).

**Usage:**

```bash
/usr/bin/php /path/to/your/project/core/cron/cron_runner.php <TaskName> >> /path/to/your/project/core/logs/cron.log 2>&1
```

- Replace `/usr/bin/php` with the actual path to your PHP interpreter if different.
- Replace `/path/to/your/project/` with the absolute path to your application's root directory.
- `<TaskName>` is the name of the cron task class you want to run (e.g., `ExampleTask`).
- It's recommended to redirect output to the `core/logs/cron.log` file for monitoring.

#### `ammar` CLI Commands for Cronjobs

The `ammar` CLI tool provides several commands to help manage your cron tasks:

-   `list:cron`: Lists all available cron tasks.
-   `make:cron <TaskName>`: Creates a new cron task file.
-   `run:cron <TaskName>`: Manually triggers a specific cron task.
-   `delete:cron <TaskName>`: Deletes a specific cron task file.
-   `clear:cron`: Deletes ALL cron task files.

### Built-in Motion Engine

This framework includes a lightweight, dependency-free JavaScript motion engine to apply CSS animations to HTML elements.

#### Enabling the Engine

The Motion Engine is controlled via an environment variable in your `.env` file:

-   `USE_ANIMATE`: Set to `true` to enable the engine (default) or `false` to disable it.

#### Using the `animate` function

The core of the engine is the `animate()` JavaScript function.

**Syntax:**
`animate(elementOrSelector, animationName, durationMs)`

### ✨ INEX DevTools

The INEX SPA framework includes a built-in debug bar for development environments, providing valuable insights into your application's performance and structure.

#### Enabling and Using DevTools

The DevTools are controlled by an environment variable in your `.env` file:

-   `DEV_MODE`: Set to `true` to enable the debug bar.

Once enabled, you can use `Ctrl + D` to toggle the visibility of the DevTools bar.

### 🌐 Built-in Fetch Layer

The INEX SPA framework includes a powerful and convenient fetch layer for making HTTP requests to external APIs.

#### Enabling the Fetch Layer

To enable the fetch layer, set the `USE_FETCH` variable to `true` in your `.env` file.

#### Using the `useFetch` function

Once enabled, you can use the `useFetch` function anywhere in your application.

**Syntax:**
`useFetch(string $url, array $options = []): array`

### 🛡️ SecurityV2 Class Reference

The `SecurityV2` class provides a comprehensive set of static methods to enhance the security of your INEX SPA application. To use it, ensure `USE_SECURITY=true` is set in your `.env` file.

### 🧱 Component System

INEX SPA includes a simple yet powerful component system that allows you to create reusable UI elements.

#### Creating a Component

To create a component, simply create a new PHP file in the `components/` directory.

#### Using a Component

To use a component in your views, you can use the `component()` function:

```php
<?= component('Button', ['text' => 'Click Me!']) ?>
```

### 📧 Mail Service

INEX SPA includes a built-in mail service that allows you to send emails from your application.

#### Configuration

To use the mail service, you first need to configure it in your `.env` file.

#### Sending Emails

To send an email, you can use the `Mail` class.

### 🧪 Testing Tools

INEX SPA now includes a set of testing tools powered by PHPUnit to help you write robust and reliable applications.

#### Running Tests

You can run tests using the `ammar` CLI:

-   `php ammar test`: Run all tests.
-   `php ammar test:unit`: Run only the unit tests.
-   `php ammar test:component`: Run only the component tests.
-   `php ammar test:snapshot`: Run only the snapshot tests.

## 🛠️ Projects 💻🌍

#### ⚡ INEX SPA 🖥️🚀

A powerful and lightweight PHP framework built for speed and efficiency.

#### ☁️ INEX SPA Cloud 🛜🔧

A free hosting platform exclusively for INEX SPA applications.

#### 🏗️ IW Panel 📊🔑

An advanced web panel developed to manage web applications efficiently.

#### 🧠 Atiyat Project 🤖🚀

The first Artificial General Intelligence (AGI) and Extra Language Model (ELM) developed by the INEX Team.

## 📧 Contact 📱🌐

For inquiries, contributions, or collaborations, reach out to the INEX team via:

-   **GitHub:** [github.com/AmmarBasha2011](https://github.com/AmmarBasha2011)
-   **Email:** [inex.own@gmail.com](mailto:inex.own@gmail.com)
-   **Phone & WhatsApp:** +201096730619

## 📜 License ✅🔓

All INEX projects are open-source and distributed under the **MIT License**.
