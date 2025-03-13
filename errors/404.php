<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <style>
        body {
            background: linear-gradient(135deg, #1e1e1e 0%, #2d2d2d 100%);
            color: #f0f0f0;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
            overflow: hidden;
        }
        h1 {
            font-size: 8em;
            margin-bottom: 0.2em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: float 6s ease-in-out infinite;
        }
        p {
            font-size: 1.4em;
            margin-bottom: 2em;
            color: #b0b0b0;
            max-width: 600px;
            line-height: 1.6;
        }
        button {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: #fff;
            padding: 1.2em 2.4em;
            text-decoration: none;
            border-radius: 50px;
            border: none;
            font-size: 1.2em;
            cursor: pointer;
            box-shadow: 0 6px 12px rgba(76, 175, 80, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        button:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 15px rgba(76, 175, 80, 0.4);
        }
        button:active {
            transform: translateY(1px);
            box-shadow: 0 4px 8px rgba(76, 175, 80, 0.2);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Oops! The page you're looking for could not be found.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>
