# 🚀 INEX Team Documentation 💡🌍

### 🔧 About INEX ✨🌟

INEX is a professional tech team specializing in high-performance software solutions. The team is renowned for developing **INEX SPA**, a lightweight and ultra-fast PHP framework. INEX is dedicated to innovation, efficiency, and open-source contributions. The team believes in the power of technology to transform industries, enhance efficiency, and provide sustainable solutions. Our projects reflect our commitment to pushing the boundaries of software development and making robust, scalable, and high-performance applications accessible to everyone. 🚀🔥🌐

We continuously explore new frontiers in web development, artificial intelligence, cloud hosting, and software automation. Our team is composed of passionate developers, engineers, and researchers who work together to create tools that improve the lives of developers and businesses alike. Our collaborative and open-source approach ensures that we are always at the forefront of technology, delivering practical and efficient solutions to real-world challenges. 💡💻🌍

### 🎯 Mission 🚀📌

INEX is driven by a passion for technological advancement. Our core mission includes:

* Developing cutting-edge web technologies that set new industry standards.
* Providing optimized and scalable solutions for developers and businesses.
* Fostering an open-source community that encourages knowledge-sharing and collaboration.
* Enhancing accessibility to powerful web technologies for developers worldwide.
* Innovating in artificial intelligence and automation to create intelligent and adaptive software solutions.
* Creating a sustainable ecosystem where developers can contribute, learn, and grow together.
* Expanding our technology to reach new fields such as **cybersecurity**, **data science**, and **cloud computing**.

### 🛠️ Projects 💻🌍

#### ⚡ INEX SPA 🖥️🚀

A powerful and lightweight PHP framework built for speed and efficiency, offering features such as:

* **Ultra-fast execution (1ms response time).**
* **Built-in CLI (Ammar CLI)** for managing routes, databases, configurations, and more. See the "Command Line Interface" section below for details.
* **Flexible file-based routing system (see Routing section below) and database management**, making backend development streamlined and efficient.
* **Built-in CSRF protection** to enhance security against web vulnerabilities.
* **Support for modular development**, allowing developers to expand their projects with plugins and additional functionalities.
* **Integrated caching and session management**, reducing server load and enhancing application performance.
* **Extensive documentation and community support**, making development easier and more accessible.

##### Key Configuration (.env)

INEX SPA behavior can be customized using variables in your `.env` file (create one by copying `.env.example`).

*   **`USE_NEW_ROUTES`**:
    *   Controls which routing system is active.
    *   Set to `true` (default): Uses the new, flexible routing system defined in `routes.php` (see details in the "INEX SPA: Routing" section below).
    *   Set to `false`: Uses the legacy routing system. In this mode, pages are typically loaded based on a `page` query parameter (e.g., `index.php?page=about`), which corresponds to files like `web/about.ahmed.php` or handles special predefined cases.
    *   The `.env.example` file includes this variable set to `true`.

*   **`USE_ANIMATE`**: (Already documented, but listed here for completeness of a "Key Configuration" idea)
    *   Set to `true` to enable the built-in JavaScript Motion Engine for CSS animations.
    *   Set to `false` to disable it. (Default: `true`)
    *   *(Details for the Motion Engine are documented further down.)*

##### Command Line Interface (Ammar CLI)

INEX SPA includes a command-line tool named `ammar` (run as `php ammar <command>`) to help with common development tasks. The behavior of some route management commands depends on the `USE_NEW_ROUTES` setting in your `.env` file.

**General Usage:**
`php ammar [command] [options]`

To see a list of all available commands:
`php ammar list` or `php ammar`

**Route Management Commands:**

The following route management commands adapt their behavior based on the `USE_NEW_ROUTES` setting in your `.env` file:

*   **`php ammar make:route`**
    *   **If `USE_NEW_ROUTES=true` (New Routing System):**
        *   Prompts for: route path (e.g., `/users/{id}`), HTTP method, type (`preview` or `command`), handler (template path for `preview`, function name or 'closure' for `command`), and API status.
        *   Adds the route definition to the `$routes` array in `routes.php`.
        *   If `type` is `preview`, it also creates a placeholder `.ahmed.php` template file in the `web/` directory (respecting subdirectories like `web/pages/`).
        *   Example: `php ammar make:route` (then follow prompts)
    *   **If `USE_NEW_ROUTES=false` (Legacy Routing System):**
        *   Prompts for: route name (e.g., `myPage`, `products/view`), dynamic status, HTTP method (if not dynamic), and API status.
        *   Creates the appropriate `.ahmed.php` file(s) in the `web/` directory (e.g., `web/myPage_request_GET.ahmed.php`, `web/products/products_view_dynamic.ahmed.php`).
        *   Example: `php ammar make:route` (then follow prompts)

*   **`php ammar list:routes`**
    *   **If `USE_NEW_ROUTES=true`:**
        *   Reads `routes.php` and lists all defined routes with their properties (path, method, type, handler, etc.).
    *   **If `USE_NEW_ROUTES=false`:**
        *   Lists all `*.ahmed.php` files found in the `web/` directory and its subdirectories, representing potential legacy routes.

*   **`php ammar delete:route`**
    *   **If `USE_NEW_ROUTES=true`:**
        *   Prompts for the exact route path to remove.
        *   Removes the corresponding entry from the `$routes` array in `routes.php`.
        *   If the deleted route was a `preview` type, it will ask if you also want to delete the associated `.ahmed.php` template file from `web/`.
    *   **If `USE_NEW_ROUTES=false`:**
        *   Prompts for the base route name.
        *   Deletes associated `*.ahmed.php` files from the `web/` directory based on common naming patterns for that route name.

*   **`php ammar clear:routes`**
    *   **If `USE_NEW_ROUTES=true`:**
        *   Asks for confirmation to clear `routes.php` (resets `$routes = [];` while attempting to preserve comments before it).
        *   Asks for separate confirmation to delete all `*.ahmed.php` files from the `web/` directory and its subdirectories.
    *   **If `USE_NEW_ROUTES=false`:**
        *   Asks for confirmation to delete all `*.ahmed.php` files from the `web/` directory and its subdirectories.

**Other Commands:**
The `ammar` CLI also includes commands for database management (`make:db`, `list:db`, `delete:db`, `run:db`), cache management (`make:cache`, `get:cache`, `clear:cache`), session management, language file creation, sitemap generation, and more. Use `php ammar list` to see all available commands and their descriptions.

## 🚀 INEX SPA: Routing

**Note:** This routing system is active when the `USE_NEW_ROUTES` environment variable is set to `true` in your `.env` file (or if the variable is not set, as `true` is the default). If `USE_NEW_ROUTES=false`, the framework uses the legacy page routing system (based on `index.php?page=...`). See the "Key Configuration" section under "INEX SPA" for more details on this flag.

INEX SPA uses a simple yet powerful file-based routing system. All your web routes are defined in the `routes.php` file located in the root directory of your project. The system allows you to map URL patterns to specific handlers, which can either render a view template or execute custom PHP logic.

### Defining Routes in `routes.php`

The `routes.php` file should contain a PHP array named `\$routes`. Each element in this array is an associative array that defines a single route.

Here's the basic structure of a route definition:

```php
\$routes[] = [
    'path'          => '/your-url-path', // The URL path this route responds to.
    'method'        => 'GET',            // HTTP method (GET, POST, PUT, DELETE, ANY).
    'type'          => 'preview',        // 'preview' (render template) or 'command' (execute PHP).
    'handler'       => 'template.ahmed.php', // For 'preview': path to template in web/
                                         // For 'command': function name or callable.
    'is_api'        => false,            // Optional: true if this is an API endpoint (skips full layout/scripts). Defaults to false.
    'dynamic_segment' => null,           // Optional: Name(s) of dynamic segment(s) if path contains placeholders like /product/{id}.
];
```

**Key Parameters:**

*   `path`: The URI pattern for the route. You can define dynamic segments using curly braces (e.g., `/users/{userId}`).
*   `method`: The HTTP method the route responds to. Common values are `GET`, `POST`, `PUT`, `DELETE`. Use `ANY` to match any method.
*   `type`:
    *   `preview`: Renders a PHP template file (using the AhmedTemplate engine). The `handler` should be the path to your template file relative to the `web/` directory (e.g., `pages/about.ahmed.php`).
    *   `command`: Executes PHP code. The `handler` can be a string with the name of a globally available function (e.g., defined in `functions.php`) or an anonymous function (Closure).
*   `handler`: Specifies what code to execute or what template to render.
    *   For `preview` routes: The filename of the template in the `web/` directory (e.g., `index.ahmed.php`, `products/list.ahmed.php`).
    *   For `command` routes: A string with the function name (e.g., `'handleFormSubmission'`) or an anonymous function `function() { ... }`.
*   `is_api` (optional): Set to `true` if the route is an API endpoint. This typically means that global assets like Bootstrap CSS/JS and common site scripts might not be loaded by the router for this route. Defaults to `false`.
*   `dynamic_segment` (optional): If your `path` contains dynamic segments (e.g., `/product/{id}`), this field can specify the name of the segment.
    *   For a single segment like `/product/{id}`, you can use `'dynamic_segment' => 'id'`. The matched value will be available in `\$_GET['id']` (for preview templates) and passed as a parameter to command handlers.
    *   For multiple segments like `/orders/{orderId}/items/{itemId}`, you can use an array: `'dynamic_segment' => ['orderId', 'itemId']`. Values will be in `\$_GET['orderId']`, `\$_GET['itemId']` and passed as multiple parameters to command handlers in the order they appear.

### Examples

**1. Basic Preview Route (Homepage):**

```php
// In routes.php
\$routes[] = [
    'path' => '/',
    'method' => 'GET',
    'type' => 'preview',
    'handler' => 'index.ahmed.php',
];
```
This will render the `web/index.ahmed.php` file when a user visits the root of your website.

**2. Preview Route with Dynamic Segment:**

```php
// In routes.php
\$routes[] = [
    'path' => '/users/{userId}/profile',
    'method' => 'GET',
    'type' => 'preview',
    'handler' => 'user_profile.ahmed.php',
    'dynamic_segment' => 'userId',
];
```
If a user visits `/users/123/profile`, the `web/user_profile.ahmed.php` template will be rendered. Inside this template, you can access the value `123` via `\$_GET['userId']`.

**3. Command Route (API Endpoint or Form Handling):**

Let's say you have a function in `functions.php`:
```php
// In functions.php
function process_contact_form() {
    if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
        \$name = htmlspecialchars(\$_POST['name'] ?? 'Anonymous');
        // ... process data ...
        echo json_encode(['message' => "Thank you for your message, " . \$name . "!"]);
    }
}
```
You can route to it like this:
```php
// In routes.php
\$routes[] = [
    'path' => '/api/contact-submit',
    'method' => 'POST',
    'type' => 'command',
    'handler' => 'process_contact_form',
    'is_api' => true, // Important for API endpoints
];
```

**4. Command Route with Anonymous Function:**

```php
// In routes.php
\$routes[] = [
    'path' => '/say-hello/{name}',
    'method' => 'GET',
    'type' => 'command',
    'handler' => function(\$name) {
        echo "Hello, " . htmlspecialchars(\$name) . "!";
    },
    'dynamic_segment' => 'name',
    'is_api' => true,
];
```
Visiting `/say-hello/Jules` would output "Hello, Jules!".

### Route Matching Order

Routes are matched in the order they are defined in the `\$routes` array. The first route that matches the requested URI and method will be used. Therefore, more specific routes should generally be defined before more generic ones.

### Special Migrated Routes

The following utility routes, previously part of the core, are now defined in `routes.php`. You can modify them if needed, but they provide essential framework functionalities:

*   `/fetchCsrfToken` (GET): Retrieves a CSRF token.
*   `/blocked` (GET): Displays a "403 Forbidden" error page.
*   `/JS/getWEBSITEURLValue.js` (GET): Outputs JavaScript to provide `WEBSITE_URL` to client-side scripts.
*   `/setLanguage` (POST): Sets the user's preferred language via a cookie. Expects a `lang` parameter in the POST request.

This new routing system provides a clear and flexible way to manage all your application's entry points.

#### ☁️ INEX SPA Cloud 🛜🔧

A free hosting platform exclusively for INEX SPA applications, offering:

* **Subdomain & subdirectory hosting**, allowing users to deploy their applications effortlessly.
* **Integrated file and database management via IW Panel**, enabling easy control over hosted applications.
* **Automated backup system** to ensure project safety and easy restoration.
* **Pre-installed support for INEX SPA**, removing setup complexities and accelerating deployment.
* **Scalability features** that allow seamless upgrades as projects grow.
* **Server optimization tools** to ensure maximum performance and uptime.

#### 🏗️ IW Panel 📊🔑

An advanced web panel developed to manage web applications efficiently. It includes:

* **File management** (upload, edit, delete) to handle application resources.
* **Database management** (create, modify, delete) with an intuitive interface.
* **Traffic analytics and site monitoring**, providing insights into website performance and visitor activity.
* **User-friendly UI** designed with efficiency and accessibility in mind.
* **Security integrations**, such as authentication layers and access control mechanisms.
* **Error logging and debugging tools** to facilitate troubleshooting and maintenance.

#### 🧠 Atiyat Project 🤖🚀

Atiyat is the first **Artificial General Intelligence (AGI) and Extra Language Model (ELM)** developed by the INEX Team. It is designed to:

* **Understand and generate human-like responses** in multiple languages with remarkable accuracy.
* **Solve complex problems** by utilizing deep reasoning and contextual understanding.
* **Enhance automation and AI-driven decision-making**, enabling businesses and developers to integrate AI solutions seamlessly.
* **Adapt dynamically** to new challenges, improving its responses over time.
* **Integrate with various software systems**, making it a powerful tool for AI-driven applications.
* **Support real-time processing and conversational AI**, making it ideal for chatbots, virtual assistants, and customer support automation.

### 🏗️ INEX Models 🧠⚙️

INEX has developed multiple AI models under different projects:

#### 🗨️ **Atiyat Chat Model** 🤖💬

A conversational AI model based on AGI principles, delivering **highly contextual, human-like responses** across various domains. It is designed for:

* **Real-time conversation handling** with deep learning adaptability.
* **Multilingual support**, allowing seamless interaction across different languages.
* **Advanced natural language processing (NLP)** to improve engagement and understanding.
* **Sentiment analysis and intent detection** for improved user experience.

#### 🧩 **YWPAI** 🔍

A specialized AI model focused on **deep reasoning and problem-solving**, designed for applications requiring logical analysis, decision-making, and **complex query resolution**. It is used in:

* **Scientific research and data analysis** to extract meaningful insights.
* **Business intelligence and forecasting** to improve decision-making processes.
* **Cybersecurity and threat detection**, enhancing digital safety measures.

#### 📊 **YWP AI 1.0** 🔎📡

An earlier-generation AI model that laid the foundation for our modern AI systems, specializing in **pattern recognition, contextual understanding, and predictive analytics**. It is particularly useful for:

* **Market trend analysis** to predict industry shifts.
* **Automated recommendation systems** for personalized user experiences.
* **Data-driven decision-making tools** to improve business strategies.

### 📝 INEX Blog 🌎📢

The INEX Blog is a platform where we share insights, tutorials, project updates, and research breakthroughs related to our work. Topics covered include:

* **INEX SPA framework updates and tutorials**, ensuring developers stay up to date with the latest enhancements.
* **AI and AGI research insights**, providing deep dives into machine learning advancements and use cases.
* **Web development best practices**, guiding developers on efficient and modern coding methodologies.
* **Open-source contributions and community news**, highlighting significant contributions and upcoming projects.
* **Exclusive interviews and case studies**, showcasing how INEX technologies are being used in real-world scenarios.
* **Security and ethical AI discussions**, focusing on responsible AI development and data privacy.

### 🔮 Our Plan for the Future 🚀

At INEX, we are constantly evolving and pushing the boundaries of technology. Our future plans focus on expanding our capabilities, improving our existing projects, and pioneering new innovations. Here’s what we are working towards:

#### 🌐 Expanding INEX SPA & INEX Cloud

* Enhancing **INEX SPA** with more built-in features, improved security, and even faster performance.
* Expanding **INEX SPA Cloud** to support more users, better scalability, and potential domain customization.
* Introducing **advanced server optimization techniques** to improve response time and reduce resource consumption.

#### 🤖 Advancing Artificial Intelligence

* Improving **Atiyat** to make it more human-like, efficient, and adaptable in various real-world applications.
* Enhancing the **YWPAI and YWP AI models** with better contextual understanding, problem-solving abilities, and multi-domain expertise.
* Exploring **AI-driven automation solutions** to revolutionize customer service, cybersecurity, and data analysis.

#### 📊 Developing INEX Web Panel (IW Panel)

* Adding **new management tools** for databases, files, and hosting solutions.
* Implementing **AI-driven analytics** to provide deeper insights into web traffic and application performance.
* Expanding **security measures**, including built-in firewalls and automated threat detection.

#### 🚀 Introducing New Open-Source Projects

* Launching **INEX SPA Coder**, a platform that simplifies website and application development using INEX SPA.
* Supporting **third-party plugin development**, allowing developers to extend INEX SPA with custom features.
* Developing **a decentralized version control system** to enhance collaboration and security for open-source projects.

#### 🌍 Growing Our Community & Impact

* Expanding our open-source contributions and encouraging more developers to participate.
* Partnering with tech communities to **offer educational resources and mentorship programs**.
* Hosting **hackathons and developer challenges** to push the limits of innovation in web development and AI.

The future of INEX is bright, and we are excited to take these next steps with our growing community of developers and innovators! 🚀✨🌎

### 📧 Contact 📱🌐

For inquiries, contributions, or collaborations, reach out to the INEX team via:

* **GitHub:** [github.com/AmmarBasha2011](https://github.com/AmmarBasha2011)
* **Email:** [inex.own@gmail.com](mailto:inex.own@gmail.com)
* **Phone & WhatsApp:** +201096730619

We welcome new ideas, feedback, and contributions from developers passionate about innovation and efficiency. Feel free to connect with us and be part of the INEX ecosystem! 🚀🌍💡

### 📜 License ✅🔓

All INEX projects are open-source and distributed under the **MIT License**. This allows developers the freedom to use, modify, and distribute our software while ensuring proper attribution. 🎯📖🔗

## Built-in Motion Engine

**Note:** This feature is currently in Beta. While functional, it may undergo changes, and we appreciate any feedback!

This framework includes a lightweight, dependency-free JavaScript motion engine to apply CSS animations to HTML elements.

### Enabling the Engine

The Motion Engine is controlled via an environment variable in your `.env` file:

- `USE_ANIMATE`: Set to `true` to enable the engine (default) or `false` to disable it.
  When enabled, the necessary JavaScript (`public/JS/motion_engine.js`) and CSS (`public/css/motion-animations.css`) files are automatically included in your pages.

Example `.env` entry:
```
USE_ANIMATE=true
```

### Using the `animate` function

The core of the engine is the `animate()` JavaScript function.

**Syntax:**
`animate(elementOrSelector, animationName, durationMs)`

- `elementOrSelector`: Can be either an HTMLElement object or a CSS selector string (e.g., `'#myElement'`, `'.my-class'`).
- `animationName`: A string representing the name of the animation to apply (e.g., `'fade-in'`). This corresponds to a CSS class `motion-{animationName}` defined in `public/css/motion-animations.css`.
- `durationMs`: An integer specifying the animation duration in milliseconds (e.g., `300`).

**Example Usage:**

```html
<div id="myBox">Hello!</div>

<script>
  // Get the element
  const box = document.getElementById('myBox');

  // Animate it with 'fade-in' over 300ms
  animate(box, 'fade-in', 300);

  // Or using a selector
  // animate('#myBox', 'fade-in', 300);
</script>
```
The function handles adding and removing the necessary CSS classes and setting the animation duration.

### Adding New Animations

To add new animations:

1.  **Define Keyframes**: Open `public/css/motion-animations.css` and define your `@keyframes`. It's recommended to prefix them with `motion-` for clarity.
    ```css
    @keyframes motion-slide-up {
      from {
        transform: translateY(20px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
    ```

2.  **Create Animation Class**: Add a corresponding CSS class that applies these keyframes.
    ```css
    .motion-slide-up {
      animation-name: motion-slide-up;
    }
    ```
    The base class `.motion-animate` (which includes `animation-fill-mode: forwards;`) will be automatically applied by the `animate` function.

3.  **Use in JavaScript**: You can now use `'slide-up'` as the `animationName` in the `animate` function:
    ```javascript
    animate('#anotherElement', 'slide-up', 500);
    ```
