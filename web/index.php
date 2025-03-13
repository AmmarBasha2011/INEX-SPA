    <!DOCTYPE html>
    <html lang="en">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome to INEX SPA</title>
        <style>
            body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            background: linear-gradient(-45deg, #4a00e0, #8e2de2, #2b4bb3, #4834d4);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            color: #fff;
            }

            @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
            }

            .container {
            text-align: center;
            padding: 20px;
            transition: all 0.5s ease;
            }

            .container:hover {
            transform: scale(1.02);
            }

            .glowing-text h1 {
            font-size: 4em;
            margin: 0;
            color: #fff;
            text-shadow: 0 0 10px #8e2de2,
                     0 0 20px #8e2de2,
                     0 0 30px #8e2de2;
            animation: glow 2s ease-in-out infinite alternate;
            transition: all 0.3s ease;
            }

            .glowing-text h1:hover {
            transform: scale(1.1);
            }

            @keyframes glow {
            from {
                text-shadow: 0 0 10px #8e2de2,
                    0 0 20px #8e2de2,
                    0 0 30px #8e2de2;
            }
            to {
                text-shadow: 0 0 20px #8e2de2,
                    0 0 30px #8e2de2,
                    0 0 40px #8e2de2;
            }
            }

            .subtitle {
            color: #e0e0e0;
            font-size: 1.2em;
            margin: 20px 0;
            max-width: 600px;
            text-align: center;
            line-height: 1.5;
            transition: all 0.3s ease;
            }

            .subtitle:hover {
            color: #fff;
            }

            .version {
            color: #ccc;
            font-size: 0.9em;
            margin-top: 10px;
            transition: color 0.3s ease;
            }

            .edit-prompt {
            color: #ffd700;
            font-size: 1.1em;
            margin-top: 15px;
            font-weight: bold;
            text-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
            }

            .version:hover {
            color: #fff;
            }

            .cta-button {
            margin-top: 30px;
            }

            .cta-button a {
            background-color: #8e2de2;
            color: #fff;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
            }

            .cta-button a:hover {
            background-color: #4a00e0;
            box-shadow: 0 0 20px #8e2de2;
            transform: translateY(-3px);
            }

            .performance-info {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: #e0e0e0;
            font-size: 0.8em;
            transition: all 0.3s ease;
            }

            .performance-info:hover {
            color: #fff;
            transform: translateX(-50%) scale(1.1);
            }
        </style>
        </head>
        <body>
            <div class="container">
            <div class="glowing-text">
                <h1>INEX SPA</h1>
            </div>
            <p class="subtitle">
                A High-Performance PHP Framework similar to NextJS/React, but lighter and faster. 
                Built with PHP, under 100KB, optimized for performance, and compatible with standard Apache servers.
            </p>
            <p class="version">Version 1.0 Beta</p>
            <p class="edit-prompt">Let's Get Started by edit web/index.php!!!</p>
            <div class="cta-button">
                <a href="https://github.com/AmmarBasha2011/INEX-SPA">Get Started</a>
            </div>
            </div>
            <div class="performance-info" id="performanceInfo">
            Loading...
            </div>
            <script>
            window.onload = function() {
                const loadTime = performance.now();
                document.getElementById('performanceInfo').textContent = 
                `Page loaded in ${loadTime.toFixed(2)}ms`;
            };
            </script>
        </body>
        </html>