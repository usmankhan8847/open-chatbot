<template>
  <div class="space-y-8 max-w-3xl">

    <!-- ─── Header ─── -->
    <div>
      <h1 class="text-2xl font-black text-white tracking-tight">Settings</h1>
      <p class="text-sm text-gray-500 mt-1">Manage your global AI configuration and account security.</p>
    </div>

    <!-- ─── Toast ─── -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="toast.show"
        class="fixed top-6 right-6 z-50 flex items-center space-x-3 px-5 py-3.5 rounded-2xl shadow-2xl text-sm font-semibold"
        :style="toast.success
          ? 'background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #34d399;'
          : 'background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #f87171;'"
      >
        <svg v-if="toast.success" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ toast.message }}</span>
      </div>
    </transition>

    <!-- ─── AI Settings ─── -->
    <section class="card overflow-hidden">
      <!-- Section header -->
      <div class="flex items-center space-x-4 p-6 border-b border-border/50"
           style="background: linear-gradient(90deg, rgba(99,102,241,0.06), transparent);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background: linear-gradient(135deg,#6366f1,#4f46e5);">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
          </svg>
        </div>
        <div>
          <h2 class="font-bold text-white text-base">Global AI Settings</h2>
          <p class="text-xs text-gray-500 mt-0.5">Default provider and API keys for new chatbots.</p>
        </div>
      </div>

      <form @submit.prevent="saveAiSettings" class="p-6 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <!-- Provider -->
          <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">AI Provider</label>
            <select v-model="aiForm.provider" class="input-field text-sm">
              <option value="openai">OpenAI</option>
              <option value="anthropic">Anthropic</option>
              <option value="gemini">Google Gemini</option>
              <option value="openrouter">OpenRouter</option>
            </select>
          </div>

          <!-- Model -->
          <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Default Model</label>
            <input
              v-model="aiForm.model"
              type="text"
              placeholder="e.g. gpt-4o"
              class="input-field text-sm"
            />
          </div>
        </div>

        <!-- API Key -->
        <div class="space-y-1.5">
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">API Key</label>
          <div class="relative">
            <input
              v-model="aiForm.api_key"
              :type="showKey ? 'text' : 'password'"
              placeholder="sk-••••••••••••••••••••"
              class="input-field text-sm pr-11"
            />
            <button
              type="button"
              @click="showKey = !showKey"
              class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-600 hover:text-gray-400 transition-colors"
            >
              <svg v-if="!showKey" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
              </svg>
            </button>
          </div>
          <p class="text-xs text-gray-700">Your key is encrypted at rest and never exposed in responses.</p>
        </div>

        <div class="flex justify-end pt-1">
          <button type="submit" :disabled="aiLoading" class="btn-primary text-sm px-6 py-2.5 flex items-center space-x-2">
            <svg v-if="aiLoading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ aiLoading ? 'Saving…' : 'Save Configuration' }}</span>
          </button>
        </div>
      </form>
    </section>

    <!-- ─── Security ─── -->
    <section class="card overflow-hidden">
      <div class="flex items-center space-x-4 p-6 border-b border-border/50"
           style="background: linear-gradient(90deg, rgba(139,92,246,0.06), transparent);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background: linear-gradient(135deg,#8b5cf6,#7c3aed);">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
          </svg>
        </div>
        <div>
          <h2 class="font-bold text-white text-base">Security Settings</h2>
          <p class="text-xs text-gray-500 mt-0.5">Update your administrative password.</p>
        </div>
      </div>

      <form @submit.prevent="changePassword" class="p-6 space-y-5">
        <!-- Current Password -->
        <div class="space-y-1.5">
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Current Password</label>
          <input
            v-model="passwordForm.current_password"
            type="password"
            placeholder="Enter your current password"
            class="input-field text-sm"
          />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">New Password</label>
            <input
              v-model="passwordForm.new_password"
              type="password"
              placeholder="Min. 8 characters"
              class="input-field text-sm"
            />
          </div>
          <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Confirm New Password</label>
            <input
              v-model="passwordForm.new_password_confirmation"
              type="password"
              placeholder="Repeat new password"
              class="input-field text-sm"
            />
          </div>
        </div>

        <!-- Password strength hint -->
        <div v-if="passwordForm.new_password" class="flex items-center space-x-2">
          <div class="flex space-x-1 flex-1">
            <div v-for="i in 4" :key="i" class="h-1 flex-1 rounded-full transition-all duration-300"
                 :style="`background: ${i <= passwordStrength ? strengthColor : 'rgba(255,255,255,0.08)'}`"></div>
          </div>
          <span class="text-xs font-semibold" :style="`color: ${strengthColor}`">{{ strengthLabel }}</span>
        </div>

        <div class="flex justify-end pt-1">
          <button
            type="submit"
            :disabled="passLoading"
            class="flex items-center space-x-2 text-sm px-6 py-2.5 rounded-xl font-semibold text-white transition-all duration-200"
            style="background: linear-gradient(135deg,#8b5cf6,#7c3aed); box-shadow: 0 4px 15px rgba(139,92,246,0.3);"
          >
            <svg v-if="passLoading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span>{{ passLoading ? 'Updating…' : 'Update Password' }}</span>
          </button>
        </div>
      </form>
    </section>

    <!-- ─── Danger Zone ─── -->
    <section class="card overflow-hidden border border-red-500/10">
      <div class="flex items-center space-x-4 p-6 border-b border-red-500/10"
           style="background: rgba(239,68,68,0.04);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.2);">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <div>
          <h2 class="font-bold text-red-400 text-base">Danger Zone</h2>
          <p class="text-xs text-gray-500 mt-0.5">Irreversible and destructive actions.</p>
        </div>
      </div>

      <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <p class="text-sm font-semibold text-gray-300">Delete All Bot Data</p>
          <p class="text-xs text-gray-600 mt-0.5">Permanently remove all bots, conversations, and training data.</p>
        </div>
        <button
          type="button"
          class="flex-shrink-0 px-5 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 flex items-center space-x-2"
          style="background: rgba(239,68,68,0.08); color: #f87171; border: 1px solid rgba(239,68,68,0.2);"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
          <span>Delete All Data</span>
        </button>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import api from '../services/api';

const aiLoading  = ref(false);
const passLoading = ref(false);
const showKey    = ref(false);

const toast = reactive({ show: false, message: '', success: true });

const aiForm = reactive({ provider: 'openai', api_key: '', model: 'gpt-4o' });

const passwordForm = reactive({
  current_password:       '',
  new_password:           '',
  new_password_confirmation: '',
});

/* ── Password strength ── */
const passwordStrength = computed(() => {
  const p = passwordForm.new_password;
  if (!p) return 0;
  let s = 0;
  if (p.length >= 8)   s++;
  if (/[A-Z]/.test(p)) s++;
  if (/[0-9]/.test(p)) s++;
  if (/[^A-Za-z0-9]/.test(p)) s++;
  return s;
});
const strengthColor = computed(() => {
  return ['#ef4444','#f59e0b','#6366f1','#10b981'][passwordStrength.value - 1] ?? '#6b7280';
});
const strengthLabel = computed(() => {
  return ['Weak','Fair','Good','Strong'][passwordStrength.value - 1] ?? '';
});

/* ── Toast helper ── */
const showToast = (message, success = true) => {
  toast.message = message;
  toast.success = success;
  toast.show    = true;
  setTimeout(() => (toast.show = false), 3500);
};

/* ── Fetch settings ── */
const fetchSettings = async () => {
  try {
    const { data } = await api.get('/settings');
    if (data.ai_settings?.length) {
      const def = data.ai_settings.find(s => s.provider === 'openai') ?? data.ai_settings[0];
      aiForm.provider = def.provider;
      aiForm.model    = def.model;
    }
  } catch (e) {
    console.error('Failed to fetch settings:', e);
  }
};

/* ── Save AI ── */
const saveAiSettings = async () => {
  aiLoading.value = true;
  try {
    await api.put('/settings/ai', aiForm);
    showToast('AI configuration saved successfully!');
  } catch (e) {
    showToast('Failed to save settings. Please try again.', false);
  } finally {
    aiLoading.value = false;
  }
};

/* ── Change Password ── */
const changePassword = async () => {
  if (passwordForm.new_password !== passwordForm.new_password_confirmation) {
    showToast('New passwords do not match.', false);
    return;
  }
  passLoading.value = true;
  try {
    await api.put('/settings/password', {
      current_password:            passwordForm.current_password,
      new_password:                passwordForm.new_password,
      new_password_confirmation:   passwordForm.new_password_confirmation,
    });
    showToast('Password updated successfully!');
    passwordForm.current_password = '';
    passwordForm.new_password = '';
    passwordForm.new_password_confirmation = '';
  } catch (e) {
    const msg = e.response?.data?.errors?.current_password?.[0] ?? 'Error updating password.';
    showToast(msg, false);
  } finally {
    passLoading.value = false;
  }
};

onMounted(fetchSettings);
</script>
