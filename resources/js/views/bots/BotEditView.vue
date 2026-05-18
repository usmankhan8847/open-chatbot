<template>
  <div class="max-w-3xl space-y-8">

    <!-- ─── Breadcrumb / Header ─── -->
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
      <div>
        <router-link
          to="/dashboard"
          class="inline-flex items-center space-x-2 text-xs font-semibold text-gray-600 hover:text-primary-light transition-colors mb-3"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          <span>Back to Dashboard</span>
        </router-link>
        <h1 class="text-2xl font-black text-white tracking-tight">Edit Chatbot</h1>
        <p class="text-sm text-gray-500 mt-1">Modify your bot's configuration and AI behavior.</p>
      </div>

      <!-- Status toggle pill (informational) -->
      <div v-if="!initialLoading" class="flex-shrink-0">
        <div class="flex items-center space-x-2 px-4 py-2 rounded-xl"
             style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.15);">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
          <span class="text-xs font-semibold text-emerald-400">Bot Active</span>
        </div>
      </div>
    </div>

    <!-- ─── Loading State ─── -->
    <div v-if="initialLoading" class="card p-12 flex flex-col items-center justify-center space-y-4">
      <div class="w-12 h-12 rounded-2xl flex items-center justify-center gradient-brand animate-pulse">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
      </div>
      <p class="text-sm font-semibold text-gray-500">Loading bot settings…</p>
      <div class="flex space-x-1.5">
        <div class="w-2 h-2 rounded-full bg-primary animate-bounce" style="animation-delay: 0ms;"></div>
        <div class="w-2 h-2 rounded-full bg-primary animate-bounce" style="animation-delay: 150ms;"></div>
        <div class="w-2 h-2 rounded-full bg-primary animate-bounce" style="animation-delay: 300ms;"></div>
      </div>
    </div>

    <form v-else @submit.prevent="handleSubmit" class="space-y-6">

      <!-- ─── Section 1: Identity ─── -->
      <section class="card overflow-hidden">
        <div class="flex items-center space-x-4 p-6 border-b border-border/50"
             style="background: linear-gradient(90deg, rgba(99,102,241,0.06), transparent);">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white flex-shrink-0"
               style="background: linear-gradient(135deg,#6366f1,#4f46e5);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-white text-sm">Bot Identity</h2>
            <p class="text-xs text-gray-500 mt-0.5">Name, avatar and opening message.</p>
          </div>
        </div>

        <div class="p-6 space-y-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-1.5">
              <label for="name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Bot Name <span class="text-red-500">*</span></label>
              <input v-model="form.name" type="text" id="name" required class="input-field text-sm" />
            </div>
            <div class="space-y-1.5">
              <label for="avatar_url" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Avatar URL</label>
              <input v-model="form.avatar_url" type="url" id="avatar_url" placeholder="https://…" class="input-field text-sm" />
            </div>
          </div>
          <div class="space-y-1.5">
            <label for="welcome_message" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Welcome Message</label>
            <input v-model="form.welcome_message" type="text" id="welcome_message" class="input-field text-sm" />
          </div>
        </div>
      </section>

      <!-- ─── Section 2: AI Model ─── -->
      <section class="card overflow-hidden">
        <div class="flex items-center space-x-4 p-6 border-b border-border/50"
             style="background: linear-gradient(90deg, rgba(139,92,246,0.06), transparent);">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white flex-shrink-0"
               style="background: linear-gradient(135deg,#8b5cf6,#7c3aed);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.477.859h4z" />
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-white text-sm">AI Model & Intelligence</h2>
            <p class="text-xs text-gray-500 mt-0.5">Provider, model and generation parameters.</p>
          </div>
        </div>

        <div class="p-6 space-y-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-1.5">
              <label for="ai_provider" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">AI Provider</label>
              <select v-model="form.ai_provider" id="ai_provider" class="input-field text-sm" @change="handleProviderChange">
                <option value="openai">OpenAI</option>
                <option value="anthropic">Anthropic</option>
                <option value="gemini">Google Gemini</option>
                <option value="openrouter">OpenRouter</option>
                <option value="custom">Custom Provider</option>
              </select>
            </div>
          </div>

          <!-- API Key + Fetch Models -->
          <div class="space-y-1.5">
            <div class="flex items-center justify-between">
              <label for="api_key" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">API Key</label>
              <span class="text-[10px] text-gray-700 italic">Leave blank to keep existing key</span>
            </div>
            <div class="flex gap-2">
              <div class="relative flex-1">
                <input v-model="form.api_key" :type="showKey ? 'text' : 'password'"
                       id="api_key" placeholder="sk-••••••••••••••••"
                       class="input-field text-sm pr-11 w-full"
                       @input="handleApiKeyInput" />
                <button type="button" @click="showKey = !showKey"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-600 hover:text-gray-400 transition-colors">
                  <svg v-if="!showKey" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                  </svg>
                </button>
              </div>
              <!-- Load Models button -->
              <button
                type="button"
                @click="triggerFetchModels"
                :disabled="fetchingModels || !form.api_key || form.ai_provider === 'custom'"
                class="flex-shrink-0 flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200"
                :style="fetchingModels
                  ? 'background: rgba(99,102,241,0.08); color: #818cf8; border: 1px solid rgba(99,102,241,0.2); cursor: not-allowed;'
                  : !form.api_key || form.ai_provider === 'custom'
                    ? 'background: rgba(255,255,255,0.03); color: #4b5563; border: 1px solid rgba(255,255,255,0.05); cursor: not-allowed;'
                    : 'background: rgba(99,102,241,0.12); color: #818cf8; border: 1px solid rgba(99,102,241,0.25); cursor: pointer;'"
              >
                <svg v-if="fetchingModels" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span class="hidden sm:inline">{{ fetchingModels ? 'Loading…' : 'Load Models' }}</span>
              </button>
            </div>
            <p v-if="form.ai_provider === 'anthropic'" class="text-xs text-amber-500/80 flex items-center space-x-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Anthropic provides a curated model list (no live API required).</span>
            </p>
          </div>

          <!-- Model: dropdown or text input -->
          <div class="space-y-1.5">
            <div class="flex items-center justify-between">
              <label for="ai_model" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Model <span class="text-red-500">*</span>
              </label>
              <span v-if="models?.length" class="text-[10px] text-emerald-500 flex items-center space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ models?.length }} models loaded</span>
              </span>
            </div>

            <!-- Dropdown (models loaded) -->
            <select
              v-if="models?.length > 0"
              v-model="form.ai_model"
              id="ai_model"
              class="input-field text-sm"
            >
              <option value="" disabled>— Select a model —</option>
              <option v-for="m in models" :key="m" :value="m">{{ m }}</option>
            </select>

            <!-- Text input (no models loaded) -->
            <div v-else class="relative">
              <input
                v-model="form.ai_model"
                type="text"
                id="ai_model_text"
                required
                :placeholder="fetchingModels ? 'Fetching models…' : 'Current: ' + (form.ai_model || 'e.g. gpt-4o') + ' — or load models'"
                class="input-field text-sm w-full"
                :disabled="fetchingModels"
              />
              <div v-if="fetchingModels" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                <svg class="animate-spin h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
            </div>

            <!-- Fetch error -->
            <p v-if="fetchError" class="text-xs text-red-400 flex items-center space-x-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>{{ fetchError }}</span>
            </p>
          </div>

          <!-- Temperature -->
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Temperature
                <span class="ml-2 px-2 py-0.5 rounded-lg text-primary-light font-bold"
                      style="background: rgba(99,102,241,0.12);">{{ form.temperature }}</span>
              </label>
              <span class="text-xs text-gray-700">{{ form.temperature < 0.4 ? 'Focused' : form.temperature > 0.7 ? 'Creative' : 'Balanced' }}</span>
            </div>
            <input v-model.number="form.temperature" type="range" id="temperature"
                   min="0" max="1" step="0.1"
                   class="w-full h-2 rounded-lg appearance-none cursor-pointer"
                   style="accent-color: #6366f1;" />
            <div class="flex justify-between text-[10px] text-gray-700 font-medium">
              <span>0.0 — Deterministic</span>
              <span>1.0 — Creative</span>
            </div>
          </div>

          <!-- Max Tokens -->
          <div class="space-y-1.5">
            <label for="max_tokens" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Max Tokens</label>
            <input v-model.number="form.max_tokens" type="number" id="max_tokens"
                   min="128" max="32000" class="input-field text-sm" />
          </div>
        </div>
      </section>

      <!-- ─── Section 3: System Prompt ─── -->
      <section class="card overflow-hidden">
        <div class="flex items-center space-x-4 p-6 border-b border-border/50"
             style="background: linear-gradient(90deg, rgba(245,158,11,0.06), transparent);">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white flex-shrink-0"
               style="background: linear-gradient(135deg,#f59e0b,#f97316);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-white text-sm">System Instructions</h2>
            <p class="text-xs text-gray-500 mt-0.5">Define how the bot behaves.</p>
          </div>
        </div>
        <div class="p-6 space-y-3">
          <label for="system_prompt" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">System Prompt</label>
          <textarea v-model="form.system_prompt" id="system_prompt" rows="7"
                    class="input-field text-sm resize-none leading-relaxed"></textarea>
          <div class="flex items-center justify-between">
            <p class="text-xs text-gray-700">Be specific about tone, scope, and limitations.</p>
            <span class="text-xs text-gray-700">{{ form.system_prompt?.length || 0 }} chars</span>
          </div>
        </div>
      </section>

      <!-- ─── Section 4: Knowledge Base ─── -->
      <section class="card overflow-hidden">
        <div class="flex items-center space-x-4 p-6 border-b border-border/50"
             style="background: linear-gradient(90deg, rgba(236,72,153,0.06), transparent);">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white flex-shrink-0"
               style="background: linear-gradient(135deg,#ec4899,#db2777);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path d="M9 4.804A7.993 7.993 0 0113 4a7.993 7.993 0 015 1.804v10A7.993 7.993 0 0013 14a7.993 7.993 0 00-4 .804V4.804zM2 14.804A7.993 7.993 0 017 14a7.993 7.993 0 014 .804V4.804A7.993 7.993 0 007 4a7.993 7.993 0 00-5 1.804v10z" />
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-white text-sm">Knowledge Base</h2>
            <p class="text-xs text-gray-500 mt-0.5">Train your bot with custom documents and websites.</p>
          </div>
        </div>

        <div class="p-6 space-y-6">
          <!-- Website Scraper -->
          <div class="space-y-3">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Scrape Website</label>
            <div class="flex gap-2">
              <input v-model="urlToScrape" type="url" placeholder="https://example.com/about"
                     class="input-field text-sm flex-1" />
              <button type="button" @click="handleScrapeUrl" :disabled="scrapingUrl || !urlToScrape"
                      class="btn-primary text-xs px-4 py-2 flex items-center space-x-2">
                <svg v-if="scrapingUrl" class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>{{ scrapingUrl ? 'Fetching…' : 'Fetch URL' }}</span>
              </button>
            </div>
            <p class="text-[10px] text-gray-700">The bot will visit the URL and learn its content.</p>
          </div>

          <!-- File Upload -->
          <div class="space-y-3">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Upload Documents</label>
            <div
              class="relative border-2 border-dashed border-border/40 rounded-2xl p-8 flex flex-col items-center justify-center transition-all duration-300 hover:border-primary/40 hover:bg-primary/5 group"
              @dragover.prevent
              @drop.prevent="handleFileDrop"
            >
              <input type="file" ref="fileInput" class="hidden" accept=".txt,.pdf" @change="handleFileSelect" />
              <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
              </div>
              <p class="text-sm font-bold text-white">Click or drag to upload</p>
              <p class="text-xs text-gray-500 mt-1">Supports PDF and TXT (Max 10MB)</p>
              <button type="button" @click="$refs.fileInput.click()" class="absolute inset-0 w-full h-full cursor-pointer"></button>
            </div>
          </div>

          <!-- Training Data List -->
          <div v-if="trainingItems?.length > 0" class="space-y-3">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Trained Sources</label>
            <div class="space-y-2">
              <div v-for="item in trainingItems" :key="item.id"
                   class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/10">
                <div class="flex items-center space-x-3 overflow-hidden">
                  <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/5 text-gray-400">
                    <svg v-if="item.file_type === 'pdf'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <svg v-else-if="item.file_type === 'url'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826L10.172 13.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.102 1.101" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <p class="text-xs font-bold text-white truncate">{{ item.file_name }}</p>
                    <p class="text-[10px] text-gray-500 uppercase tracking-tighter">{{ item.file_type }} • {{ new Date(item.created_at).toLocaleDateString() }}</p>
                  </div>
                </div>
                <button type="button" @click="handleDeleteTraining(item.id)"
                        class="p-2 text-gray-600 hover:text-red-400 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ─── Section 4: Deployment & Embed ─── -->
      <section class="card overflow-hidden">
        <div class="flex items-center space-x-4 p-6 border-b border-border/50"
             style="background: linear-gradient(90deg, rgba(16,185,129,0.06), transparent);">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white flex-shrink-0"
               style="background: linear-gradient(135deg,#10b981,#059669);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 4.946-2.597 9.181-6.5 11.573a11.954 11.954 0 01-6.5-11.572c0-.68.057-1.35.166-2.001zm9.447 2.13a1 1 0 10-1.225 1.578L12.447 10l-2.059 1.293a1 1 0 101.225 1.578l3-1.884a1 1 0 000-1.578l-3-1.883z" clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-white text-sm">Deployment & Embed</h2>
            <p class="text-xs text-gray-500 mt-0.5">Control where this bot can be embedded.</p>
          </div>
        </div>

        <div class="p-6 space-y-5">
          <div class="space-y-1.5">
            <label for="allowed_domains" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Allowed Domains</label>
            <input v-model="form.allowed_domains" type="text" id="allowed_domains"
                   placeholder="example.com, portal.site.io" class="input-field text-sm" />
            <p class="text-xs text-gray-700">Comma-separated. Leave empty to allow all domains.</p>
          </div>

          <!-- Embed Code Box -->
          <div class="rounded-2xl overflow-hidden" style="border: 1px solid rgba(99,102,241,0.2);">
            <div class="flex items-center justify-between px-5 py-3"
                 style="background: rgba(99,102,241,0.08);">
              <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                <span class="text-xs font-bold text-primary-light uppercase tracking-wider">Embed Script</span>
              </div>
              <button type="button" @click="copyEmbedCode"
                      class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200"
                      :style="copied
                        ? 'background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.2);'
                        : 'background: rgba(99,102,241,0.12); color: #818cf8; border: 1px solid rgba(99,102,241,0.2);'">
                <svg v-if="!copied" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ copied ? 'Copied!' : 'Copy' }}</span>
              </button>
            </div>
            <pre class="p-5 text-xs font-mono leading-relaxed overflow-x-auto"
                 style="background: #080812; color: #818cf8;"><code>{{ embedCode }}</code></pre>
            <div class="px-5 py-3 border-t border-border/30">
              <p class="text-xs text-gray-700">
                Paste this into your website's <code class="text-primary-light">&lt;head&gt;</code> or <code class="text-primary-light">&lt;body&gt;</code> tag.
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- ─── Error ─── -->
      <div v-if="error"
           class="flex items-start space-x-3 p-4 rounded-xl text-sm text-red-300"
           style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-red-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ error }}</span>
      </div>

      <!-- ─── Actions ─── -->
      <div class="flex items-center justify-between pt-2">
        <router-link to="/dashboard" class="btn-ghost text-sm px-5 py-2.5">
          Discard Changes
        </router-link>
        <button type="submit" :disabled="loading" class="btn-primary text-sm px-7 py-2.5 flex items-center space-x-2">
          <svg v-if="loading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span>{{ loading ? 'Saving…' : 'Save Changes' }}</span>
        </button>
      </div>

    </form>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { botService } from '../../services/botService';
import { useModelFetch } from '../../composables/useModelFetch';

const router         = useRouter();
const route          = useRoute();
const loading        = ref(false);
const initialLoading = ref(true);
const showKey        = ref(false);
const copied         = ref(false);
const error          = ref('');

const urlToScrape    = ref('');
const scrapingUrl    = ref(false);
const trainingItems  = ref([]);
const fileInput      = ref(null);

const { models, fetchingModels, fetchError, fetchModels, clearModels } = useModelFetch();

const embedCode = computed(() => {
  const origin = window.location.origin;
  return `<script src="${origin}/embed.js" data-bot-id="${route.params.id}"><\/script>`;
});

const copyEmbedCode = () => {
  navigator.clipboard.writeText(embedCode.value);
  copied.value = true;
  setTimeout(() => (copied.value = false), 2000);
};

const form = reactive({
  name:            '',
  avatar_url:      '',
  system_prompt:   '',
  ai_provider:     'openai',
  ai_model:        'gpt-4o',
  api_key:         '',
  temperature:     0.7,
  max_tokens:      2048,
  welcome_message: '',
  allowed_domains: '',
});

const fetchBotData = async () => {
  initialLoading.value = true;
  try {
    const { data } = await botService.getBot(route.params.id);
    form.name            = data.name            || '';
    form.avatar_url      = data.avatar_url      || '';
    form.system_prompt   = data.system_prompt   || '';
    form.ai_provider     = data.ai_provider     || 'openai';
    form.ai_model        = data.ai_model        || 'gpt-4o';
    form.api_key         = '';
    form.temperature     = data.temperature     ?? 0.7;
    form.max_tokens      = data.max_tokens      ?? 2048;
    form.welcome_message = data.welcome_message || '';
    form.allowed_domains = data.allowed_domains || '';
    
    await fetchTrainingData();
  } catch (e) {
    console.error('Failed to fetch bot:', e);
    error.value = 'Failed to load chatbot data.';
    setTimeout(() => router.push('/dashboard'), 2000);
  } finally {
    initialLoading.value = false;
  }
};

/* ── Model fetch helpers ── */
const triggerFetchModels = () => {
  fetchModels(form.ai_provider, form.api_key);
};

const handleApiKeyInput = () => {
  clearModels();
};

const handleProviderChange = () => {
  clearModels();
  // Keep current model text; user can still load new list
};

const fetchTrainingData = async () => {
  try {
    const { data } = await botService.getTrainingData(route.params.id);
    trainingItems.value = Array.isArray(data) ? data : [];
  } catch (e) {
    console.error('Failed to load training data:', e);
  }
};

const handleScrapeUrl = async () => {
  let url = urlToScrape.value.trim();
  if (!url) return;

  // Pre-check protocol
  if (!/^https?:\/\//i.test(url)) {
    url = 'https://' + url;
  }

  scrapingUrl.value = true;
  error.value = '';
  try {
    await botService.scrapeWebsite(route.params.id, url);
    urlToScrape.value = '';
    await fetchTrainingData();
  } catch (e) {
    console.error('Failed to scrape URL:', e);
    // Show validation error if available, else general message
    const validationErrors = e.response?.data?.errors;
    if (validationErrors && validationErrors.url) {
      error.value = validationErrors.url[0];
    } else {
      error.value = e.response?.data?.message || 'Failed to scrape URL. Please ensure the website is accessible.';
    }
  } finally {
    scrapingUrl.value = false;
  }
};

const uploadFile = async (file) => {
  if (!file) return;
  const formData = new FormData();
  formData.append('file', file);
  try {
    await botService.uploadTrainingFile(route.params.id, formData);
    await fetchTrainingData();
  } catch (e) {
    console.error('Failed to upload file:', e);
    error.value = e.response?.data?.message || 'Failed to upload file.';
  }
};

const handleFileDrop = (e) => {
  const file = e.dataTransfer?.files?.[0];
  if (file) uploadFile(file);
};

const handleFileSelect = (e) => {
  const file = e.target.files?.[0];
  if (file) uploadFile(file);
  if (fileInput.value) fileInput.value.value = '';
};

const handleDeleteTraining = async (id) => {
  if (!confirm('Are you sure you want to delete this knowledge source?')) return;
  try {
    await botService.deleteTrainingData(id);
    await fetchTrainingData();
  } catch (e) {
    console.error('Failed to delete training data:', e);
  }
};

const handleSubmit = async () => {
  error.value   = '';
  loading.value = true;
  try {
    const payload = { ...form };
    if (!payload.api_key) delete payload.api_key;
    await botService.updateBot(route.params.id, payload);
    router.push('/dashboard');
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Failed to update chatbot. Please check your inputs.';
  } finally {
    loading.value = false;
  }
};

onMounted(fetchBotData);
</script>
