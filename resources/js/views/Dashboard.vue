<template>
  <div class="space-y-8">

    <!-- ─── Page Header ─── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-white tracking-tight">
          Welcome back<span class="gradient-brand-text">, {{ auth.user?.name?.split(' ')[0] ?? 'Admin' }}</span>
        </h1>
        <p class="text-sm text-gray-500 mt-1">Here's what's happening with your AI bots today.</p>
      </div>
      <button
        @click="$router.push('/bots/create')"
        class="btn-primary self-start sm:self-auto text-sm px-5 py-2.5 flex items-center space-x-2"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        <span>New Bot</span>
      </button>
    </div>

    <!-- ─── Stat Cards ─── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div v-for="stat in stats" :key="stat.label"
           class="card p-5 flex flex-col space-y-3 relative overflow-hidden group cursor-default">
        <!-- Gradient accent blob -->
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-10 blur-2xl transition-opacity group-hover:opacity-20"
             :style="`background: ${stat.glow}`"></div>

        <div class="flex items-center justify-between">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white flex-shrink-0"
               :style="`background: ${stat.gradient}`">
            <span v-html="stat.icon" class="w-4 h-4 block"></span>
          </div>
          <span v-if="loading" class="w-12 h-4 shimmer rounded-lg"></span>
          <span v-else class="text-xs font-bold px-2 py-0.5 rounded-lg"
                :style="`background: ${stat.badgeBg}; color: ${stat.badgeColor};`">
            {{ stat.badge }}
          </span>
        </div>

        <div>
          <p class="text-[11px] font-semibold text-gray-600 uppercase tracking-widest">{{ stat.label }}</p>
          <p v-if="loading" class="mt-1 w-20 h-7 shimmer rounded-lg"></p>
          <p v-else class="text-2xl font-black text-white mt-0.5">{{ stat.value }}</p>
        </div>
      </div>
    </div>

    <!-- ─── Bots Section ─── -->
    <div class="space-y-5">
      <!-- Section header -->
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <h2 class="text-lg font-bold text-white">Your Chatbots</h2>
          <span class="badge" style="background: rgba(99,102,241,0.12); color: #818cf8; border: 1px solid rgba(99,102,241,0.2);">
            {{ bots.length }}
          </span>
        </div>
        <router-link to="/bots" class="text-xs font-semibold text-gray-500 hover:text-primary-light transition-colors flex items-center space-x-1">
          <span>View all</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </router-link>
      </div>

      <!-- Skeleton loaders -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <div v-for="i in 3" :key="i" class="card p-5 space-y-4">
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

      <!-- Empty state -->
      <div v-else-if="bots.length === 0"
           class="card border-dashed p-16 text-center"
           style="border-color: rgba(99,102,241,0.2); background: rgba(99,102,241,0.03);">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5"
             style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2);">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
          </svg>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">No chatbots yet</h3>
        <p class="text-sm text-gray-500 mb-6 max-w-xs mx-auto">Create your first AI assistant and embed it anywhere in minutes.</p>
        <button @click="$router.push('/bots/create')" class="btn-primary text-sm px-6 py-2.5 mx-auto">
          Create Your First Bot
        </button>
      </div>

      <!-- Bot Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <div
          v-for="bot in bots"
          :key="bot.id"
          class="card p-5 flex flex-col justify-between group"
        >
          <!-- Bot header -->
          <div>
            <div class="flex items-start justify-between mb-5">
              <div class="flex items-center space-x-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-black text-base flex-shrink-0 gradient-brand shadow-lg"
                     style="box-shadow: 0 4px 15px rgba(99,102,241,0.3);">
                  {{ bot.name.charAt(0).toUpperCase() }}
                </div>
                <div class="min-w-0">
                  <h3 class="font-bold text-white text-sm leading-tight group-hover:text-primary-light transition-colors truncate max-w-[140px]">
                    {{ bot.name }}
                  </h3>
                  <p class="text-xs text-gray-600 mt-0.5 truncate max-w-[140px]">{{ bot.ai_model }}</p>
                </div>
              </div>
              <span class="badge flex-shrink-0" :class="bot.is_active ? 'badge-active' : 'badge-inactive'">
                {{ bot.is_active ? 'Live' : 'Off' }}
              </span>
            </div>

            <!-- Stats mini grid -->
            <div class="grid grid-cols-2 gap-3 mb-5">
              <div class="p-3 rounded-xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                <p class="text-[10px] text-gray-600 uppercase font-bold tracking-widest mb-1">Chats</p>
                <p class="text-lg font-black text-white">{{ bot.conversations_count || 0 }}</p>
              </div>
              <div class="p-3 rounded-xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                <p class="text-[10px] text-gray-600 uppercase font-bold tracking-widest mb-1">Status</p>
                <p class="text-lg font-black" :class="bot.is_active ? 'text-emerald-400' : 'text-gray-500'">
                  {{ bot.is_active ? 'Online' : 'Paused' }}
                </p>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center space-x-2 pt-4 border-t border-border/50">
            <button
              @click="editBot(bot)"
              class="flex-1 py-2 text-xs font-semibold text-gray-400 hover:text-white rounded-xl transition-all duration-200 flex items-center justify-center space-x-1.5"
              style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);"
              onmouseover="this.style.background='rgba(255,255,255,0.08)'"
              onmouseout="this.style.background='rgba(255,255,255,0.04)'"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>Configure</span>
            </button>

            <button
              @click="copyEmbedCode(bot.id)"
              class="flex-1 py-2 text-xs font-semibold rounded-xl transition-all duration-200 flex items-center justify-center space-x-1.5"
              :style="copiedId === bot.id
                ? 'background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.2);'
                : 'background: rgba(99,102,241,0.1); color: #818cf8; border: 1px solid rgba(99,102,241,0.2);'"
            >
              <svg v-if="copiedId !== bot.id" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span>{{ copiedId === bot.id ? 'Copied!' : 'Embed' }}</span>
            </button>

            <button
              @click="deleteBot(bot.id)"
              title="Delete Bot"
              class="p-2 rounded-xl transition-all duration-200 flex-shrink-0"
              style="background: rgba(239,68,68,0.08); color: #f87171; border: 1px solid rgba(239,68,68,0.15);"
              onmouseover="this.style.background='rgba(239,68,68,0.15)'"
              onmouseout="this.style.background='rgba(239,68,68,0.08)'"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import api from '../services/api';

const router = useRouter();
const auth   = useAuthStore();
const loading  = ref(true);
const copiedId = ref(null);

const overview = ref({ total_bots: 0, total_conversations: 0, total_messages: 0, total_tokens_used: 0 });
const bots     = ref([]);

/* ── Stat card config ── */
const botIcon   = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>`;
const chatIcon  = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>`;
const msgIcon   = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>`;
const tokenIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>`;

const stats = computed(() => [
  {
    label: 'Total Bots', value: overview.value.total_bots, badge: 'Fleet',
    icon: botIcon,
    gradient: 'linear-gradient(135deg, #6366f1, #4f46e5)',
    glow: '#6366f1',
    badgeBg: 'rgba(99,102,241,0.12)', badgeColor: '#818cf8',
  },
  {
    label: 'Conversations', value: overview.value.total_conversations, badge: 'Total',
    icon: chatIcon,
    gradient: 'linear-gradient(135deg, #06b6d4, #0891b2)',
    glow: '#06b6d4',
    badgeBg: 'rgba(6,182,212,0.12)', badgeColor: '#22d3ee',
  },
  {
    label: 'Messages', value: overview.value.total_messages, badge: 'All time',
    icon: msgIcon,
    gradient: 'linear-gradient(135deg, #8b5cf6, #7c3aed)',
    glow: '#8b5cf6',
    badgeBg: 'rgba(139,92,246,0.12)', badgeColor: '#a78bfa',
  },
  {
    label: 'Tokens Used', value: overview.value.total_tokens_used.toLocaleString(), badge: 'API',
    icon: tokenIcon,
    gradient: 'linear-gradient(135deg, #f59e0b, #f97316)',
    glow: '#f59e0b',
    badgeBg: 'rgba(245,158,11,0.12)', badgeColor: '#fbbf24',
  },
]);

/* ── Actions ── */
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
    await api.delete(`/bots/${id}`);
    bots.value = bots.value.filter(b => b.id !== id);
    const res  = await api.get('/analytics/overview');
    overview.value = res.data;
  } catch (e) {
    console.error('Failed to delete bot:', e);
  }
};

const fetchData = async () => {
  loading.value = true;
  try {
    const [ovRes, botsRes] = await Promise.all([
      api.get('/analytics/overview'),
      api.get('/bots'),
    ]);
    overview.value = ovRes.data;
    bots.value     = botsRes.data;
  } catch (e) {
    console.error('Dashboard fetch error:', e);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchData);
</script>
