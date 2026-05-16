<template>
  <div class="max-w-3xl space-y-8">

    <!-- ─── Breadcrumb / Header ─── -->
    <div>
      <router-link
        to="/dashboard"
        class="inline-flex items-center space-x-2 text-xs font-semibold text-gray-600 hover:text-primary-light transition-colors mb-4"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Back to Dashboard</span>
      </router-link>
      <h1 class="text-2xl font-black text-white tracking-tight">Create New Chatbot</h1>
      <p class="text-sm text-gray-500 mt-1">Configure your AI assistant step by step.</p>
    </div>

    <!-- ─── Progress Indicator ─── -->
    <div class="flex items-center space-x-2">
      <div v-for="(step, idx) in steps" :key="step" class="flex items-center space-x-2">
        <div class="flex items-center space-x-2">
          <div
            class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
            :style="idx === 0
              ? 'background: linear-gradient(135deg,#6366f1,#4f46e5); color: #fff;'
              : 'background: rgba(255,255,255,0.05); color: #6b7280; border: 1px solid rgba(255,255,255,0.08);'"
          >{{ idx + 1 }}</div>
          <span class="text-xs font-semibold" :class="idx === 0 ? 'text-primary-light' : 'text-gray-600'">{{ step }}</span>
        </div>
        <svg v-if="idx < steps.length - 1" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-700 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </div>
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-6">

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
            <p class="text-xs text-gray-500 mt-0.5">Give your bot a name and personality.</p>
          </div>
        </div>

        <div class="p-6 space-y-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-1.5">
              <label for="name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Bot Name <span class="text-red-500">*</span></label>
              <input v-model="form.name" type="text" id="name" required placeholder="e.g. Customer Support AI" class="input-field text-sm" />
            </div>
            <div class="space-y-1.5">
              <label for="avatar_url" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Avatar URL</label>
              <input v-model="form.avatar_url" type="url" id="avatar_url" placeholder="https://example.com/bot.png" class="input-field text-sm" />
            </div>
          </div>
          <div class="space-y-1.5">
            <label for="welcome_message" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Welcome Message</label>
            <input v-model="form.welcome_message" type="text" id="welcome_message" placeholder="Hello! How can I help you today?" class="input-field text-sm" />
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
              <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-white text-sm">AI Model & Intelligence</h2>
            <p class="text-xs text-gray-500 mt-0.5">Select your AI provider and model configuration.</p>
          </div>
        </div>

        <div class="p-6 space-y-5">
          <!-- Provider -->
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

          <!-- API Key + Fetch button -->
          <div class="space-y-1.5">
            <label for="api_key" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">API Key</label>
            <div class="flex gap-2">
              <div class="relative flex-1">
                <input v-model="form.api_key" :type="showKey ? 'text' : 'password'" id="api_key"
                       placeholder="Enter your provider API key" class="input-field text-sm pr-11 w-full"
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
              <!-- Fetch Models button -->
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

          <!-- Model selector: dropdown when models loaded, text input otherwise -->
          <div class="space-y-1.5">
            <div class="flex items-center justify-between">
              <label for="ai_model" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Model <span class="text-red-500">*</span>
              </label>
              <span v-if="models.length" class="text-[10px] text-emerald-500 flex items-center space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ models.length }} models loaded</span>
              </span>
            </div>

            <!-- Dropdown (models loaded) -->
            <select
              v-if="models.length > 0"
              v-model="form.ai_model"
              id="ai_model"
              class="input-field text-sm"
            >
              <option value="" disabled>— Select a model —</option>
              <option v-for="m in models" :key="m" :value="m">{{ m }}</option>
            </select>

            <!-- Text input (no models yet) -->
            <div v-else class="relative">
              <input
                v-model="form.ai_model"
                type="text"
                id="ai_model_text"
                required
                :placeholder="fetchingModels ? 'Fetching models…' : 'e.g. gpt-4o — or load models above'"
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

          <!-- Temperature Slider -->
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Temperature
                <span class="ml-2 px-2 py-0.5 rounded-lg text-primary-light font-bold"
                      style="background: rgba(99,102,241,0.12);">{{ form.temperature }}</span>
              </label>
              <span class="text-xs text-gray-700">{{ form.temperature < 0.4 ? 'Focused' : form.temperature > 0.7 ? 'Creative' : 'Balanced' }}</span>
            </div>
            <div class="relative">
              <input v-model.number="form.temperature" type="range" id="temperature"
                     min="0" max="1" step="0.1" class="w-full h-2 rounded-lg appearance-none cursor-pointer"
                     style="background: linear-gradient(90deg, #6366f1 0%, #6366f1 calc(var(--pct, 70%) ), rgba(255,255,255,0.08) calc(var(--pct, 70%)));
                            --pct: calc({{ form.temperature * 100 }}%); accent-color: #6366f1;" />
            </div>
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
            <p class="text-xs text-gray-700">Maximum tokens per response (128 – 32,000).</p>
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
            <p class="text-xs text-gray-500 mt-0.5">Define how your bot behaves and responds.</p>
          </div>
        </div>

        <div class="p-6 space-y-3">
          <label for="system_prompt" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">System Prompt</label>
          <textarea
            v-model="form.system_prompt"
            id="system_prompt"
            rows="6"
            placeholder="You are a helpful customer support assistant for Acme Corp. You help users with billing, technical issues, and product inquiries. Always be polite and professional."
            class="input-field text-sm resize-none leading-relaxed"
          ></textarea>
          <div class="flex items-center justify-between">
            <p class="text-xs text-gray-700">Tip: Be specific about tone, scope, and limitations.</p>
            <span class="text-xs text-gray-700">{{ form.system_prompt.length }} chars</span>
          </div>
        </div>
      </section>

      <!-- ─── Section 4: Deployment ─── -->
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
            <h2 class="font-bold text-white text-sm">Deployment & Security</h2>
            <p class="text-xs text-gray-500 mt-0.5">Control where your bot can be embedded.</p>
          </div>
        </div>

        <div class="p-6 space-y-3">
          <label for="allowed_domains" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Allowed Domains</label>
          <input
            v-model="form.allowed_domains"
            type="text"
            id="allowed_domains"
            placeholder="example.com, app.mysite.io"
            class="input-field text-sm"
          />
          <p class="text-xs text-gray-700">Comma-separated. Leave empty to allow all domains.</p>
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
          Cancel
        </router-link>
        <button type="submit" :disabled="loading" class="btn-primary text-sm px-7 py-2.5 flex items-center space-x-2">
          <svg v-if="loading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          <span>{{ loading ? 'Creating…' : 'Create Chatbot' }}</span>
        </button>
      </div>

    </form>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { botService } from '../../services/botService';
import { useModelFetch } from '../../composables/useModelFetch';

const router  = useRouter();
const loading = ref(false);
const showKey = ref(false);
const error   = ref('');

const { models, fetchingModels, fetchError, fetchModels, clearModels } = useModelFetch();

const steps = ['Identity', 'AI Model', 'Instructions', 'Deployment'];

const form = reactive({
  name:            '',
  avatar_url:      '',
  system_prompt:   '',
  ai_provider:     'openai',
  ai_model:        'gpt-4o',
  api_key:         '',
  temperature:     0.7,
  max_tokens:      2048,
  welcome_message: 'Hello! How can I help you today?',
  allowed_domains: '',
});

/* ── Model fetch helpers ── */
const triggerFetchModels = () => {
  fetchModels(form.ai_provider, form.api_key);
};

const handleApiKeyInput = () => {
  // Clear existing model list when key changes so user re-fetches
  clearModels();
};

const handleProviderChange = () => {
  clearModels();
  form.ai_model = '';
  // Auto-fetch for Anthropic (no key needed)
  if (form.ai_provider === 'anthropic' && form.api_key) {
    fetchModels('anthropic', form.api_key);
  }
};

const handleSubmit = async () => {
  error.value   = '';
  loading.value = true;
  try {
    const { data } = await botService.createBot(form);
    router.push(`/bots/${data.id}/edit`);
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Failed to create chatbot. Please check your inputs.';
  } finally {
    loading.value = false;
  }
};
</script>
