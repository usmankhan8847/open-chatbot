<h1 align="center">
  <br>
  🤖 OpenChatbot
  <br>
</h1>

<p align="center">
  <strong>Open-source AI chatbot platform — create, configure, and embed AI bots on any website.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white" alt="Vue 3">
  <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3+">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License">
</p>

<p align="center">
  <a href="#-features">Features</a> •
  <a href="#-requirements">Requirements</a> •
  <a href="#-installation">Installation</a> •
  <a href="#-configuration">Configuration</a> •
  <a href="#-usage">Usage</a> •
  <a href="#-api-reference">API</a> •
  <a href="#-tech-stack">Tech Stack</a> •
  <a href="#-troubleshooting">Troubleshooting</a>
</p>

---

## ✨ Features

- 🤖 **Multi-Bot Management** — Create unlimited chatbots, each with its own personality and knowledge base
- 🧠 **Multiple AI Providers** — Connect to OpenAI, Anthropic (Claude), Google Gemini, OpenRouter, or any custom OpenAI-compatible API
- 📚 **Knowledge Base (RAG)** — Train your bots by uploading documents (PDF, TXT) or scraping website URLs
- 🌐 **Embeddable Widget** — Copy-paste a single `<script>` tag to add the chat widget to any website
- 📊 **Analytics Dashboard** — Track conversations, messages, and bot activity over time
- 🔒 **Secure Auth** — Single-admin authentication with Laravel Sanctum SPA tokens
- ⚡ **Real-time Chat** — Streaming-ready API with persistent conversation history
- 🔑 **Per-Bot API Keys** — Each bot can use its own AI provider & model, overriding global settings

---

## 📋 Requirements

| Software   | Minimum Version | Download |
|------------|-----------------|----------|
| **PHP**    | 8.3+            | [php.net](https://windows.php.net/download) / [Homebrew](https://formulae.brew.sh/formula/php) |
| **Composer** | 2.x           | [getcomposer.org](https://getcomposer.org/download/) |
| **Node.js** | 18+            | [nodejs.org](https://nodejs.org/) |
| **npm**    | 9+              | Bundled with Node.js |
| **MySQL**  | 8.0+ *(or SQLite/PostgreSQL)* | [mysql.com](https://dev.mysql.com/downloads/) |

> **Note:** If you prefer SQLite for local development (zero config), see [Using SQLite](#using-sqlite) below.

---

## 🚀 Installation

### Option A — Automatic Setup (Recommended)

**Linux / macOS:**
```bash
git clone https://github.com/usmankhan8847/open-chatbot.git
cd open-chatbot
chmod +x setup.sh
./setup.sh
```

**Windows:**
```bat
git clone https://github.com/usmankhan8847/open-chatbot.git
cd open-chatbot
setup.bat
```

The setup script handles everything: dependency installation, environment setup, key generation, and database migration.

---

### Option B — Manual Step-by-Step

#### 1. Clone the Repository

```bash
git clone https://github.com/usmankhan8847/open-chatbot.git
cd open-chatbot
```

#### 2. Install PHP Dependencies

```bash
composer install
```

#### 3. Install JavaScript Dependencies

```bash
npm install
```

#### 4. Create the Environment File

**Linux / macOS:**
```bash
cp .env.example .env
```

**Windows (Command Prompt):**
```bat
copy .env.example .env
```

**Windows (PowerShell):**
```powershell
Copy-Item .env.example .env
```

#### 5. Generate Application Key

```bash
php artisan key:generate
```

#### 6. Configure the Database

Open your `.env` file and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=open_chatbot
DB_USERNAME=root
DB_PASSWORD=your_password
```

> First, create the database in MySQL:
> ```sql
> CREATE DATABASE open_chatbot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
> ```

#### 7. Run Migrations & Seed

```bash
php artisan migrate --seed
```

This creates all tables and seeds a default admin user.

#### 8. Create Storage Symlink

```bash
php artisan storage:link
```

#### 9. Start the Development Servers

Open **two terminal windows**:

**Terminal 1 — Laravel Backend:**
```bash
php artisan serve
```
> Runs at: `http://localhost:8000`

**Terminal 2 — Vite Frontend:**
```bash
npm run dev
```
> Runs at: `http://localhost:5173`

#### 10. Access the Application

Open your browser and go to: **[http://localhost:5173](http://localhost:5173)**

Default login credentials:
| Field    | Value             |
|----------|-------------------|
| Email    | `test@example.com` |
| Password | `password`        |

---

## ⚙️ Configuration

### Environment Variables Reference

```env
# ── Application ──────────────────────────────────────────────────────────────
APP_NAME="OpenChatbot"
APP_ENV=local           # local | production | staging
APP_KEY=                # Auto-generated by: php artisan key:generate
APP_DEBUG=true          # Set to false in production
APP_URL=http://localhost:8000

# ── Database ─────────────────────────────────────────────────────────────────
DB_CONNECTION=mysql     # mysql | sqlite | pgsql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=open_chatbot
DB_USERNAME=root
DB_PASSWORD=

# ── AI Provider (Global Default) ─────────────────────────────────────────────
# Uncomment and fill in ONE provider block below.
# These values can also be set per-bot from the dashboard.

# OpenAI
# AI_PROVIDER=openai
# AI_API_KEY=sk-...
# AI_MODEL=gpt-4o

# Anthropic (Claude)
# AI_PROVIDER=anthropic
# AI_API_KEY=sk-ant-api03-...
# AI_MODEL=claude-sonnet-4-20250514

# Google Gemini
# AI_PROVIDER=gemini
# AI_API_KEY=AIza...
# AI_MODEL=gemini-2.0-flash

# OpenRouter (access 100+ models)
# AI_PROVIDER=openrouter
# AI_API_KEY=sk-or-...
# AI_MODEL=openai/gpt-4o

# Custom / Self-hosted (any OpenAI-compatible endpoint)
# AI_PROVIDER=custom
# AI_API_KEY=your-key
# AI_MODEL=your-model
# AI_BASE_URL=https://your-endpoint.com/v1

# ── Sanctum (SPA Auth) ───────────────────────────────────────────────────────
SANCTUM_STATEFUL_DOMAINS=localhost:5173,localhost:8000

# ── Vite ─────────────────────────────────────────────────────────────────────
VITE_APP_NAME="${APP_NAME}"
VITE_API_BASE_URL="${APP_URL}/api"
```

### Using SQLite

For quick local development with zero database setup:

1. In `.env`, set:
   ```env
   DB_CONNECTION=sqlite
   ```
2. Create the SQLite file:
   ```bash
   touch database/database.sqlite
   ```
3. Run migrations:
   ```bash
   php artisan migrate --seed
   ```

---

## 🎯 Usage

### 1. Add Your AI API Key

1. Log in and click **Settings** in the sidebar
2. Under **Global AI Settings**, choose your provider (OpenAI, Claude, Gemini, etc.)
3. Paste your API key and select a model
4. Click **Save AI Configuration**

> 💡 Free API keys: [OpenAI](https://platform.openai.com/api-keys) · [Google AI Studio](https://aistudio.google.com/app/apikey) · [OpenRouter](https://openrouter.ai/keys) · [Anthropic](https://www.anthropic.com)

---

### 2. Create a Chatbot

1. Click **My Bots** → **+ Create New Bot**
2. Fill in:
   - **Name** — e.g., `Customer Support`
   - **System Prompt** — Instructions that define the bot's personality and behavior
   - **Welcome Message** — The first message visitors see
   - **AI Provider / Model** — Override the global settings per-bot (optional)
3. Click **Save Bot**

---

### 3. Train Your Bot (Knowledge Base)

1. Open a bot and go to the **Knowledge Base** tab
2. **Upload a Document** (PDF or TXT) — the content becomes searchable context for the bot
3. **Add a URL** — paste a webpage URL to scrape and index its content
4. The bot will use this knowledge to answer questions accurately

---

### 4. Embed on Any Website

1. Go to **My Bots** → click **Edit** on your bot
2. Scroll to the **Embed Script** section
3. Copy the generated snippet:
   ```html
   <script src="http://localhost:8000/embed.js" data-bot-id="YOUR_BOT_ID"></script>
   ```
4. Paste it before the closing `</body>` tag of any webpage

The chat widget will appear as a floating button in the bottom-right corner of the site. No other dependencies needed.

---

### 5. View Analytics

Click **Analytics** in the sidebar to see:
- Total conversations and messages
- Active bots
- Message volume over time (30-day chart)
- Recent conversation activity

---

## 🌐 Supported AI Providers

| Provider | Popular Models | Get API Key |
|----------|----------------|-------------|
| **OpenAI** | GPT-4o, GPT-4 Turbo, GPT-3.5 Turbo | [platform.openai.com](https://platform.openai.com/api-keys) |
| **Anthropic** | Claude 4, Claude 3.5 Sonnet, Claude 3 Haiku | [anthropic.com](https://www.anthropic.com) |
| **Google** | Gemini 2.0 Flash, Gemini 1.5 Pro | [aistudio.google.com](https://aistudio.google.com/app/apikey) |
| **OpenRouter** | 100+ models (GPT, Claude, Llama, Mistral…) | [openrouter.ai](https://openrouter.ai/keys) |
| **Custom** | Any OpenAI-compatible endpoint | — |

---

## 🔌 API Reference

The application exposes a REST API under `/api`. All endpoints (except `/api/chat`) require a Sanctum session token.

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/login` | Authenticate and get a session cookie |
| `POST` | `/api/logout` | Invalidate the session |
| `GET`  | `/api/user` | Get the authenticated user |

### Bots

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET`    | `/api/bots` | List all bots |
| `POST`   | `/api/bots` | Create a new bot |
| `GET`    | `/api/bots/{id}` | Get a single bot |
| `PUT`    | `/api/bots/{id}` | Update a bot |
| `DELETE` | `/api/bots/{id}` | Delete a bot |

### Chat (Public — used by the embed widget)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/chat` | Send a message and get an AI response |

**Request body:**
```json
{
  "bot_id": 1,
  "message": "Hello!",
  "session_id": "visitor-uuid-here"
}
```

### Training / Knowledge Base

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET`    | `/api/bots/{bot}/training` | List training items |
| `POST`   | `/api/bots/{bot}/training` | Upload a document or URL |
| `DELETE` | `/api/bots/{bot}/training/{id}` | Remove a training item |

### Settings

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET`  | `/api/settings` | Get current AI settings |
| `POST` | `/api/settings` | Update AI provider/key/model |

### Analytics

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/analytics` | Get stats and message volume chart data |

### Models (Dynamic Discovery)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/models/fetch` | Fetch available models for a given provider + API key |

---

## 🏗️ Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend Framework** | [Laravel 13](https://laravel.com/) (PHP 8.3+) |
| **Frontend Framework** | [Vue.js 3](https://vuejs.org/) (Composition API + `<script setup>`) |
| **State Management** | [Pinia](https://pinia.vuejs.org/) |
| **Routing** | [Vue Router 5](https://router.vuejs.org/) |
| **CSS** | [Tailwind CSS 4](https://tailwindcss.com/) |
| **Build Tool** | [Vite 8](https://vite.dev/) with `laravel-vite-plugin` |
| **Authentication** | [Laravel Sanctum](https://laravel.com/docs/sanctum) (SPA mode) |
| **Database ORM** | [Eloquent](https://laravel.com/docs/eloquent) |
| **PDF Parsing** | [smalot/pdfparser](https://github.com/smalot/pdfparser) |
| **HTTP Client** | [Axios](https://axios-http.com/) |

---

## 📂 Project Structure

```
open-chatbot/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/               # Login / Logout
│   │   ├── BotController.php   # Bot CRUD
│   │   ├── ChatController.php  # AI chat logic
│   │   ├── TrainingController.php # Knowledge base
│   │   ├── SettingsController.php # AI config
│   │   ├── ModelFetchController.php # Dynamic model discovery
│   │   └── AnalyticsController.php
│   ├── Models/
│   │   ├── Bot.php
│   │   ├── Conversation.php
│   │   ├── Message.php
│   │   ├── TrainingData.php
│   │   └── ApiKey.php
│   └── Services/
│       └── AIService.php       # Unified AI provider abstraction
├── database/
│   ├── migrations/             # All DB schema migrations
│   └── seeders/                # Default user seeder
├── public/
│   └── embed.js                # Standalone chat widget script
├── resources/
│   └── js/
│       ├── views/              # Vue page components
│       │   ├── Dashboard.vue
│       │   ├── Analytics.vue
│       │   ├── Settings.vue
│       │   └── bots/
│       │       ├── BotList.vue
│       │       ├── BotCreateView.vue
│       │       └── BotEditView.vue
│       ├── layouts/
│       │   └── AppLayout.vue
│       ├── composables/
│       │   └── useModelFetch.js
│       └── services/
│           └── botService.js
└── routes/
    ├── api.php                 # API route definitions
    └── web.php                 # SPA catch-all route
```

---

## 🔧 Troubleshooting

### `php artisan key:generate` — "No application encryption key"
This just means `.env` is missing the `APP_KEY`. Run:
```bash
php artisan key:generate
```

### "AI API Key not configured" error in chat
Navigate to **Settings** and add your AI provider credentials.

### Database connection refused
- Make sure your MySQL/MariaDB server is running
- Verify the `DB_*` values in `.env` match your actual credentials
- Try connecting manually: `mysql -u root -p open_chatbot`

### Frontend shows a blank page or 404
Make sure **both** servers are running simultaneously:
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```
Then visit `http://localhost:5173` (not `:8000`).

### `CORS` errors in browser console
Ensure `SANCTUM_STATEFUL_DOMAINS` in `.env` includes your frontend origin:
```env
SANCTUM_STATEFUL_DOMAINS=localhost:5173,localhost:8000
```

### Port already in use
```bash
# Change Laravel port
php artisan serve --port=8001

# Change Vite port (in vite.config.js → server.port)
```

### `npm run dev` fails — "Cannot find module"
```bash
rm -rf node_modules package-lock.json
npm install
```

### Composer install fails — PHP version mismatch
This project requires **PHP 8.3+**. Check your version:
```bash
php --version
```

---

## 🚢 Production Deployment

> For production, always use a proper web server (Nginx/Apache) and a process manager.

```bash
# 1. Set environment to production
APP_ENV=production
APP_DEBUG=false

# 2. Install dependencies (no dev)
composer install --optimize-autoloader --no-dev

# 3. Build frontend assets
npm run build

# 4. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Run migrations
php artisan migrate --force
```

Point your web server document root to the `public/` directory.

---

## 🤝 Contributing

Contributions are welcome! Here's how to get started:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Commit your changes: `git commit -m 'Add your feature'`
4. Push the branch: `git push origin feature/your-feature`
5. Open a Pull Request

---

## 📄 License

This project is open-source under the [MIT License](LICENSE). Feel free to use it for personal or commercial projects.

---

<p align="center">Built with ❤️ for the open-source community</p>