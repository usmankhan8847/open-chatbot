<template>
  <div class="min-h-screen bg-bg flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Animated background orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full opacity-20 blur-3xl"
           style="background: radial-gradient(circle, #6366f1, transparent)"></div>
      <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full opacity-15 blur-3xl"
           style="background: radial-gradient(circle, #06b6d4, transparent)"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full opacity-5 blur-3xl"
           style="background: radial-gradient(circle, #8b5cf6, transparent)"></div>
    </div>

    <!-- Grid pattern overlay -->
    <div class="absolute inset-0 opacity-[0.02]"
         style="background-image: linear-gradient(rgba(255,255,255,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 40px 40px;"></div>

    <!-- Auth Card -->
    <div class="relative w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4 gradient-brand shadow-lg glow-primary">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
          </svg>
        </div>
        <h1 class="text-3xl font-black text-white tracking-tight">BotForge</h1>
        <p class="text-gray-500 mt-1.5 text-sm">Sign in to manage your AI chatbot fleet</p>
      </div>

      <div class="glass rounded-3xl p-8 shadow-2xl" style="box-shadow: 0 25px 60px rgba(0,0,0,0.5);">
        <router-view></router-view>
      </div>

      <p class="text-center text-xs text-gray-700 mt-6">
        © {{ new Date().getFullYear() }} BotForge · All rights reserved
      </p>
    </div>
  </div>
</template>

<script setup>
// Auth layout with premium glass UI
</script>
