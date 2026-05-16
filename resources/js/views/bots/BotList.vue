<template>
  <div class="space-y-8">

    <!-- ─── Header ─── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-white tracking-tight">My Chatbots</h1>
        <p class="text-sm text-gray-500 mt-1">Manage and deploy your AI assistants.</p>
      </div>
      <router-link to="/bots/create" class="btn-primary self-start sm:self-auto text-sm px-5 py-2.5 flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        <span>Create New Bot</span>
      </router-link>
    </div>

    <!-- ─── Search / Filter Bar ─── -->
    <div class="flex flex-col sm:flex-row gap-3">
      <div class="relative flex-1">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input
          v-model="search"
          type="text"
          placeholder="Search bots by name or model…"
          class="input-field pl-11 text-sm w-full"
        />
      </div>
      <select v-model="filterStatus" class="input-field text-sm w-full sm:w-auto sm:min-w-[160px]">
        <option value="all">All Status</option>
        <option value="active">Active Only</option>
        <option value="inactive">Inactive Only</option>
      </select>
    </div>

    <!-- ─── Skeletons ─── -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      <div v-for="i in 6" :key="i" class="card p-5 space-y-4">
        <div class="flex items-center space-x-3">
          <div class="w-11 h-11 rounded-xl shimmer"></div>
          <div class="flex-1 space-y-2">
            <div class="h-4 shimmer rounded w-3/4"></div>
            <div class="h-3 shimmer rounded w-1/2"></div>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div class="h-14 shimmer rounded-xl"></div>
          <div class="h-14 shimmer rounded-xl"></div>
        </div>
        <div class="h-9 shimmer rounded-xl"></div>
      </div>
    </div>

    <!-- ─── Empty State ─── -->
    <div
      v-else-if="filteredBots.length === 0"
      class="card border-dashed p-16 text-center"
      style="border-color: rgba(99,102,241,0.2); background: rgba(99,102,241,0.03);"
    >
      <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5"
           style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2);">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
      </div>
      <h3 class="text-lg font-bold text-white mb-2">
        {{ search ? 'No bots found' : 'No chatbots yet' }}
      </h3>
      <p class="text-sm text-gray-500 mb-6 max-w-xs mx-auto">
        {{ search ? `No bots matching "${search}"` : 'Get started by creating your first AI assistant.' }}
      </p>
      <router-link v-if="!search" to="/bots/create" class="btn-primary text-sm px-6 py-2.5 inline-flex">
        Create Your First Bot
      </router-link>
      <button v-else @click="search = ''" class="btn-ghost text-sm px-5 py-2.5">
        Clear Search
      </button>
    </div>

    <!-- ─── Bot Grid ─── -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      <div
        v-for="bot in filteredBots"
        :key="bot.id"
        class="card p-5 flex flex-col justify-between group"
      >
        <!-- Bot header -->
        <div>
          <div class="flex items-start justify-between mb-5">
            <div class="flex items-center space-x-3">
              <div
                class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-black text-base flex-shrink-0 shadow-lg"
                style="background: linear-gradient(135deg, #6366f1, #06b6d4); box-shadow: 0 4px 15px rgba(99,102,241,0.3);"
              >
                {{ bot.name.charAt(0).toUpperCase() }}
              </div>
              <div class="min-w-0">
                <h3 class="font-bold text-white text-sm leading-tight group-hover:text-primary-light transition-colors truncate max-w-[150px]">
                  {{ bot.name }}
                </h3>
                <p class="text-xs text-gray-600 mt-0.5 truncate max-w-[150px]">{{ bot.ai_model }}</p>
              </div>
            </div>
            <span class="badge flex-shrink-0" :class="bot.is_active ? 'badge-active' : 'badge-inactive'">
              {{ bot.is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>

          <!-- Stats mini -->
          <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="p-3 rounded-xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
              <p class="text-[10px] text-gray-600 uppercase font-bold tracking-widest mb-1">Conversations</p>
              <p class="text-xl font-black text-white">{{ bot.conversations_count || 0 }}</p>
            </div>
            <div class="p-3 rounded-xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
              <p class="text-[10px] text-gray-600 uppercase font-bold tracking-widest mb-1">Status</p>
              <p class="text-xl font-black" :class="bot.is_active ? 'text-emerald-400' : 'text-gray-500'">
                {{ bot.is_active ? 'Ready' : 'Paused' }}
              </p>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center space-x-2 pt-4 border-t border-border/50">
          <button
            @click="editBot(bot)"
            class="flex-1 py-2 text-xs font-semibold text-gray-400 hover:text-white rounded-xl transition-all flex items-center justify-center space-x-1.5"
            style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span>Edit</span>
          </button>

          <button
            @click="copyEmbedCode(bot.id)"
            class="flex-1 py-2 text-xs font-semibold rounded-xl transition-all flex items-center justify-center space-x-1.5"
            :style="copiedId === bot.id
              ? 'background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.2);'
              : 'background: rgba(99,102,241,0.1); color: #818cf8; border: 1px solid rgba(99,102,241,0.2);'"
          >
            <svg v-if="copiedId !== bot.id" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ copiedId === bot.id ? 'Copied!' : 'Embed Code' }}</span>
          </button>

          <button
            @click="deleteBot(bot.id)"
            title="Delete Bot"
            class="p-2 rounded-xl transition-all flex-shrink-0"
            style="background: rgba(239,68,68,0.08); color: #f87171; border: 1px solid rgba(239,68,68,0.15);"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Count footer -->
    <p v-if="!loading && filteredBots.length > 0" class="text-xs text-gray-700 text-center">
      Showing {{ filteredBots.length }} of {{ bots.length }} bot{{ bots.length !== 1 ? 's' : '' }}
    </p>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../services/api';

const router      = useRouter();
const loading     = ref(true);
const bots        = ref([]);
const copiedId    = ref(null);
const search      = ref('');
const filterStatus = ref('all');

const filteredBots = computed(() => {
  return bots.value.filter(bot => {
    const matchSearch = !search.value ||
      bot.name.toLowerCase().includes(search.value.toLowerCase()) ||
      (bot.ai_model || '').toLowerCase().includes(search.value.toLowerCase());
    const matchStatus =
      filterStatus.value === 'all' ||
      (filterStatus.value === 'active'   &&  bot.is_active) ||
      (filterStatus.value === 'inactive' && !bot.is_active);
    return matchSearch && matchStatus;
  });
});

const fetchData = async () => {
  loading.value = true;
  try {
    const { data } = await api.get('/api/bots');
    bots.value = data;
  } catch (e) {
    console.error('Failed to fetch bots:', e);
  } finally {
    loading.value = false;
  }
};

const copyEmbedCode = (botId) => {
  const origin = window.location.origin;
  const code   = `<script src="${origin}/embed.js" data-bot-id="${botId}"><\/script>`;
  navigator.clipboard.writeText(code);
  copiedId.value = botId;
  setTimeout(() => (copiedId.value = null), 2000);
};

const editBot   = (bot) => router.push(`/bots/${bot.id}/edit`);

const deleteBot = async (id) => {
  if (!confirm('Delete this bot? This action cannot be undone.')) return;
  try {
    await api.delete(`/api/bots/${id}`);
    bots.value = bots.value.filter(b => b.id !== id);
  } catch (e) {
    console.error('Failed to delete bot:', e);
  }
};

onMounted(fetchData);
</script>
