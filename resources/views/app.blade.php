<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="BotForge — Build, deploy, and manage AI-powered chatbots for your website in minutes." />
    <meta name="theme-color" content="#070711" />

    <!-- Open Graph -->
    <meta property="og:title" content="BotForge · AI Chatbot Platform" />
    <meta property="og:description" content="Build and deploy AI-powered chatbots for your website in minutes." />
    <meta property="og:type" content="website" />

    <title>BotForge · AI Chatbot Platform</title>

    <!-- Preconnect to Google Fonts CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    @vite(['resources/js/style.css', 'resources/js/main.js'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
