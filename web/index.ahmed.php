<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEX SPA v5 - High-Performance PHP Framework</title>
    <link rel="stylesheet" href='@getEnvValue("WEBSITE_URL")style.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href='@getEnvValue("WEBSITE_URL")public/css/motion-animations.css'>
</head>
<body>
    <header>
        <nav>
            <div id="title">INEX SPA</div>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#installation">Installation</a>
                <a href="#docs">Documentation</a>
                <a href="#repo">Repository</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1 class="glow-text">Powering the Future with <span style="color: var(--primary);">Innovative</span> Technology</h1>
            <p>INEX SPA is a high-performance, lightweight PHP framework designed for modern web applications. It offers rapid execution and seamless integration with Apache and PHP environments.</p>
            <div>
                <a href="#installation" class="btn">Get Started <i class="fas fa-arrow-right"></i></a>
                <a href="#docs" class="btn btn-outline">Documentation</a>
            </div>
        </section>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">1ms</div>
                <div class="stat-label">Response Time</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">v5</div>
                <div class="stat-label">Latest Version</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">42+</div>
                <div class="stat-label">Core Features</div>
            </div>
        </div>

        <div id="introduction">
            <label>Introduction</label>
            <p>INEX SPA is a high-performance, lightweight PHP framework designed for modern web applications. It offers rapid execution (1ms response time) and seamless integration with Apache and PHP environments. Our framework is built to deliver exceptional performance without compromising on features or flexibility.</p>
            <div class="loading-bar"></div>
        </div>
        
        <div id="features">
            <label>Features</label>
            <ul>
                <li style="--item-index: 1">Ultra-Fast Performance: Executes within 1ms on any Apache/PHP server.</li>
                <li style="--item-index: 2">Built-in CLI (Ammar CLI): Provides a powerful command-line interface.</li>
                <li style="--item-index: 3">Routing System: Supports dynamic and static routes.</li>
                <li style="--item-index: 4">Database Management: Easily create, delete, and manage database tables.</li>
                <li style="--item-index: 5">Session Management: Similar to PHP's $_SESSION but optimized for speed.</li>
                <li style="--item-index: 6">Localization Support: JSON-based multi-language support.</li>
                <li style="--item-index: 7">File-Based Caching: Efficient caching for optimized performance.</li>
                <li style="--item-index: 8">Rate Limiter: Protects against excessive requests.</li>
                <li style="--item-index: 9">Layouts System: Reduces code duplication.</li>
                <li style="--item-index: 10">Live Data Submission: Send data without reloading the page.</li>
                <li style="--item-index: 11">Sitemap Generator: Automatically generates sitemaps for SEO.</li>
                <li style="--item-index: 12">Import System: Install third-party libraries easily.</li>
            </ul>
        </div>
        
        <div id="installation">
            <label>Installation</label>
            <h3>To install INEX SPA, clone the repository and set up your server:</h3>
            <code id="bash">
# Clone the repository
git clone https://github.com/AmmarBasha2011/INEX-SPA.git my-project
cd my-project

# Set correct permissions
chmod -R 755 .
            </code>
            <p id="note">Ensure your Apache server has mod_rewrite enabled.</p>
        </div>
        
        <div id="docs">
            <label>Documentation</label>
            <p>Comprehensive documentation is available to help you get started with INEX SPA. Learn about all the features, configuration options, and best practices.</p>
            <a href="https://inex-1.gitbook.io/inex-docs/inex-spa" target="_blank">View Full Documentation</a>
        </div>
        
        <div id="repo">
            <label>Repository</label>
            <p>The full source code is available on GitHub. Star the repository to show your support or fork it to contribute your own improvements.</p>
            <a href="https://github.com/AmmarBasha2011/INEX-SPA" target="_blank">GitHub Repository</a>
        </div>
        
        <div id="contributing">
            <label>Contributing</label>
            <p>Contributions are welcome! Fork the repository, make changes, and submit a pull request. Help us make INEX SPA even better by contributing code, reporting bugs, or suggesting new features.</p>
        </div>
        
        <div id="license">
            <label>License</label>
            <p>INEX SPA is open-source and available under the MIT License. You are free to use, modify, and distribute the framework in accordance with the terms of the license.</p>
        </div>
    </main>

    <footer>
        <div class="logo" id="title">INEX SPA</div>
        <p>INEX SPA v5 - A high-performance PHP framework</p>
        <p>&copy; 2025 INEX Team. All rights reserved.</p>
    </footer>

    <script src='@getEnvValue("WEBSITE_URL")public/JS/motion_engine.js'></script>
    <script>
        // Animation for list items
        document.querySelectorAll('li').forEach((item, index) => {
            item.style.setProperty('--item-index', index);
        });

        // Smooth scroll for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
