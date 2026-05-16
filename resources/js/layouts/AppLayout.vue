<template>
  <div class="min-h-screen bg-bg flex text-gray-100 selection:bg-primary/30">

    <!-- ═══════════════════════════════════ SIDEBAR ═══════════════════════════════════ -->
    <aside
      :class="['fixed inset-y-0 left-0 z-40 w-72 flex flex-col transition-transform duration-300 lg:translate-x-0 lg:static lg:flex',
               sidebarOpen ? 'translate-x-0' : '-translate-x-full']"
      style="background: linear-gradient(180deg, #0d0d1a 0%, #090912 100%); border-right: 1px solid rgba(255,255,255,0.05);"
    >
      <!-- Logo -->
      <div class="px-6 py-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center gradient-brand shadow-lg glow-primary flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
          </div>
          <div>
            <span class="text-lg font-black text-white tracking-tight">BotForge</span>
            <p class="text-[10px] text-gray-600 font-medium tracking-widest uppercase">AI Platform</p>
          </div>
        </div>
        <!-- Close on mobile -->
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-600 hover:text-gray-400 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Divider -->
      <div class="mx-6 h-px" style="background: linear-gradient(90deg, transparent, rgba(99,102,241,0.3), transparent);"></div>

      <!-- Nav Label -->
      <p class="px-6 pt-6 pb-2 text-[10px] font-bold text-gray-600 uppercase tracking-widest">Navigation</p>

      <!-- Nav Items -->
      <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
        <router-link
          v-for="item in navItems"
          :key="item.path"
          :to="item.path"
          class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-500 hover:text-gray-200 hover:bg-surface-2 transition-all duration-200 group relative"
          active-class="nav-active !text-primary-light"
        >
          <!-- Icon -->
          <span class="flex-shrink-0 w-5 h-5 text-current" v-html="item.icon"></span>
          <span class="font-semibold text-sm">{{ item.name }}</span>

          <!-- Active dot -->
          <span
            v-if="$route.path.startsWith(item.path)"
            class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-primary"
          ></span>
        </router-link>
      </nav>

      <!-- Bottom user card -->
      <div class="p-4 mt-auto">
        <div class="h-px mx-2 mb-4" style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);"></div>
        <div v-if="auth.user" class="flex items-center p-3 rounded-2xl group cursor-pointer hover:bg-surface-2 transition-all duration-200"
             style="background: rgba(99,102,241,0.05); border: 1px solid rgba(99,102,241,0.1);">
          <!-- Avatar -->
          <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-black text-white flex-shrink-0 gradient-brand">
            {{ auth.user.name.charAt(0).toUpperCase() }}
          </div>
          <div class="ml-3 flex-1 min-w-0">
            <p class="text-sm font-bold text-white truncate">{{ auth.user.name }}</p>
            <p class="text-[10px] text-gray-600 truncate">{{ auth.user.email }}</p>
          </div>
          <button @click="handleLogout" title="Sign Out"
                  class="ml-2 text-gray-600 hover:text-red-400 transition-colors flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
          </button>
        </div>
      </div>
    </aside>

    <!-- Mobile overlay -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 bg-black/60 z-30 lg:hidden backdrop-blur-sm"
    ></div>

    <!-- ═══════════════════════════════════ MAIN ═══════════════════════════════════ -->
    <div class="flex-1 flex flex-col min-h-screen min-w-0">

      <!-- Top Header -->
      <header class="sticky top-0 z-20 flex items-center justify-between h-16 px-6 glass border-b border-border/50">
        <!-- Mobile burger -->
        <button @click="sidebarOpen = true" class="lg:hidden text-gray-400 hover:text-white transition-colors mr-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <!-- Page title (dynamic) -->
        <div class="flex items-center space-x-2 min-w-0">
          <div class="hidden lg:flex items-center space-x-2 text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
            </svg>
            <span class="text-sm text-gray-500">BotForge</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </div>
          <span class="font-semibold text-gray-300 text-sm truncate">{{ currentPageTitle }}</span>
        </div>

        <!-- Right actions -->
        <div class="flex items-center space-x-3 ml-auto">
          <!-- Status pill -->
          <div class="hidden sm:flex items-center space-x-2 px-3 py-1.5 rounded-xl"
               style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-xs font-semibold text-emerald-400">System Online</span>
          </div>

          <!-- Sign out -->
          <button @click="handleLogout"
                  class="btn-ghost text-sm px-4 py-2 flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="hidden sm:inline">Sign Out</span>
          </button>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-6 lg:p-8 overflow-x-hidden">
        <div class="max-w-7xl mx-auto">
          <router-view v-slot="{ Component }">
            <transition
              enter-active-class="transition duration-300 ease-out"
              enter-from-class="opacity-0 translate-y-3"
              enter-to-class="opacity-100 translate-y-0"
              leave-active-class="transition duration-150 ease-in"
              leave-from-class="opacity-100"
              leave-to-class="opacity-0"
            >
              <component :is="Component" />
            </transition>
          </router-view>
        </div>
      </main>

      <!-- Footer -->
      <footer class="px-8 py-4 border-t border-border/30 flex items-center justify-between">
        <p class="text-xs text-gray-700">© {{ new Date().getFullYear() }} BotForge · AI Chatbot Platform</p>
        <p class="text-xs text-gray-700">v1.0.0</p>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const route  = useRoute();
const auth   = useAuthStore();
const sidebarOpen = ref(false);

const chatIconSvg = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>`;
const gridIconSvg  = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>`;
const chartIconSvg = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>`;
const cogIconSvg   = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`;

const navItems = [
  { name: 'Dashboard',  path: '/dashboard', icon: gridIconSvg  },
  { name: 'My Bots',    path: '/bots',      icon: chatIconSvg  },
  { name: 'Analytics',  path: '/analytics', icon: chartIconSvg },
  { name: 'Settings',   path: '/settings',  icon: cogIconSvg   },
];

const currentPageTitle = computed(() => {
  const match = navItems.find(item => route.path.startsWith(item.path));
  if (route.path.includes('/bots/create')) return 'Create New Bot';
  if (route.path.match(/\/bots\/\d+\/edit/)) return 'Edit Bot';
  return match ? match.name : 'BotForge';
});

const handleLogout = async () => {
  await auth.logout();
  router.push('/login');
};
</script>
