# OpenChatbot - Open Source AI Chatbot Platform

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/Vue-3-4FC08D?style=flat&logo=vue.js" alt="Vue 3">
  <img src="https://img.shields.io/badge/License-MIT-007ACC" alt="License">
</p>

Create custom AI chatbots and embed them on any website in minutes. No coding required.

## What is OpenChatbot?

OpenChatbot is a self-hosted platform that lets you:
- **Create chatbots** with custom instructions (system prompts)
- **Connect to AI providers** like OpenAI, Anthropic (Claude), Google Gemini, or any custom API
- **Embed on websites** with a simple copy-paste script tag
- **Track conversations** and see analytics
- **Train your bot** with custom knowledge base

## Quick Start (5 Minutes)

> **🚀 One-Command Setup (Mac/Linux):**
> ```bash
> ./setup.sh
> ```
>
> **🚀 One-Command Setup (Windows):**
> ```bat
> setup.bat
> ```

### Step 1: Clone the Project
```bash
git clone https://github.com/yourusername/open-chatbot.git
cd open-chatbot
```

### Step 2: Install Dependencies

**On Windows:**
```bash
composer install
npm install
```

**On Mac/Linux:**
```bash
composer install
npm install
```

### Step 3: Setup Database

1. Open MySQL and create a database named `open_chatbot`
2. Copy the environment file:
   ```
   copy .env.example .env
   ```
3. Open `.env` and update these lines with your database info:
   ```
   DB_DATABASE=open_chatbot
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

### Step 4: Initialize
```bash
php artisan key:generate
php artisan migrate --seed
```

### Step 5: Start the App
```bash
# Terminal 1 - Start the backend
php artisan serve

# Terminal 2 - Start the frontend
npm run dev
```

### Step 6: Open in Browser
Go to: **http://localhost:5173**

Log in with:
- **Email:** `test@example.com`
- **Password:** `password`

---

## How to Add Your AI Key

1. After logging in, click **Settings** in the sidebar
2. Under "Global AI Settings", choose your provider (e.g., OpenAI)
3. Paste your API key
4. Pick your preferred model
5. Click **Save AI Configuration**

> **Tip:** Get free API keys from [OpenAI](https://platform.openai.com), [Anthropic](https://www.anthropic.com), or [Google AI](https://aistudio.google.com/app/apikey)

---

## How to Create a Chatbot

1. Click **My Bots** in the sidebar
2. Click **+ Create New Bot**
3. Fill in:
   - **Name:** Your bot's name (e.g., "Customer Support")
   - **System Prompt:** Instructions for the AI (e.g., "You are a helpful customer support agent...")
   - **Welcome Message:** First message visitors see
4. Click **Save Bot**

---

## How to Embed on Your Website

1. Go to **My Bots**
2. Click **Edit** on your bot
3. Scroll to **Embed Script**
4. Copy the script tag (looks like this):
   ```html
   <script src="http://localhost:8000/embed.js" data-bot-id="1"></script>
   ```
5. Paste it into your website's `<head>` or `<body>` tag

**That's it!** Your chatbot will appear in the bottom-right corner of your site.

---

## Supported AI Providers

| Provider | Models | Website |
|----------|--------|---------|
| OpenAI | GPT-4o, GPT-4, GPT-3.5 Turbo | [openai.com](https://openai.com) |
| Anthropic | Claude 4, Claude 3 | [anthropic.com](https://www.anthropic.com) |
| Google | Gemini 2.0, Gemini 1.5 | [aistudio.google.com](https://aistudio.google.com) |
| OpenRouter | 100+ models | [openrouter.ai](https://openrouter.ai) |
| Custom | Any OpenAI-compatible API | - |

---

## Troubleshooting

### "AI API Key not configured" error
Go to **Settings** and add your AI provider API key.

### Can't connect to database
Make sure MySQL is running and your `.env` credentials are correct.

### Frontend not loading
Make sure both `php artisan serve` and `npm run dev` are running.

### Need help?
- Check the [Wiki](https://github.com/yourusername/open-chatbot/wiki)
- Open an [Issue](https://github.com/yourusername/open-chatbot/issues)

---

## Requirements

| Software | Version | Where to get |
|----------|---------|--------------|
| PHP | 8.2+ | [php.net](https://windows.php.net/download) |
| Node.js | 18+ | [nodejs.org](https://nodejs.org) |
| Composer | Latest | [getcomposer.org](https://getcomposer.org) |
| MySQL | 8.0+ | [mysql.com](https://mysql.com) |

---

## Tech Stack

- **Backend:** Laravel 13 (PHP)
- **Frontend:** Vue 3 + Tailwind CSS
- **Database:** MySQL (or SQLite/PostgreSQL)
- **Auth:** Laravel Sanctum

---

## License

MIT License - feel free to use this for any project.

---

## Screenshots

*The dashboard showing your bots and analytics*
*The embed widget on a website*
*The bot creation form*

---

<p align="center">Built with ❤️ for the open source community</p>