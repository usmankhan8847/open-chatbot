<template>
  <div class="space-y-8">

    <!-- ─── Header ─── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-white tracking-tight">Analytics</h1>
        <p class="text-sm text-gray-500 mt-1">Track performance and engagement across your AI fleet.</p>
      </div>
      <div class="flex items-center space-x-3">
        <select
          class="input-field text-sm px-4 py-2.5"
          style="min-width: 150px;"
        >
          <option>Last 7 Days</option>
          <option>Last 30 Days</option>
          <option>Last 90 Days</option>
          <option>All Time</option>
        </select>
        <button
          class="btn-ghost text-sm px-4 py-2.5 flex items-center space-x-2 flex-shrink-0"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          <span class="hidden sm:inline">Export</span>
        </button>
      </div>
    </div>

    <!-- ─── KPI Cards ─── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="card p-5 relative overflow-hidden group cursor-default"
      >
        <!-- Glow blob -->
        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full opacity-10 blur-2xl transition-opacity group-hover:opacity-20"
             :style="`background: ${stat.glow}`"></div>

        <div class="flex items-center justify-between mb-4">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white"
               :style="`background: ${stat.gradient}`">
            <span v-html="stat.icon" class="w-4 h-4 block"></span>
          </div>
          <span v-if="stat.trend"
                class="text-xs font-bold px-2 py-0.5 rounded-lg"
                style="background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.2);">
            +{{ stat.trend }}%
          </span>
        </div>

        <p class="text-[11px] font-semibold text-gray-600 uppercase tracking-widest">{{ stat.label }}</p>
        <h3 class="text-2xl font-black text-white mt-0.5">{{ stat.value }}</h3>
      </div>
    </div>

    <!-- ─── Charts Row ─── -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

      <!-- Conversation Volume Chart (3 cols) -->
      <div class="card p-6 flex flex-col lg:col-span-3" style="min-height: 340px;">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h3 class="font-bold text-white text-sm">Conversation Volume</h3>
            <p class="text-xs text-gray-600 mt-0.5">Monthly trend overview</p>
          </div>
          <div class="flex items-center space-x-2">
            <span class="w-2 h-2 rounded-full" style="background: #6366f1;"></span>
            <span class="text-xs font-medium text-gray-500">Conversations</span>
          </div>
        </div>

        <!-- Bar chart visual -->
        <div class="flex-1 flex items-end space-x-1.5 pb-1 min-h-0">
          <div
            v-for="(bar, i) in chartBars"
            :key="i"
            class="flex-1 rounded-t-lg transition-all duration-300 cursor-pointer group/bar relative"
            :style="`height: ${bar.h}%; background: ${bar.active ? 'linear-gradient(180deg,#818cf8,#6366f1)' : 'rgba(99,102,241,0.15)'};`"
            @mouseenter="bar.active = true"
            @mouseleave="bar.active = false"
          >
            <!-- Tooltip -->
            <div v-if="bar.active"
                 class="absolute -top-9 left-1/2 -translate-x-1/2 px-2 py-1 rounded-lg text-[10px] font-bold text-white whitespace-nowrap z-10"
                 style="background: rgba(99,102,241,0.9);">
              {{ bar.value }}
            </div>
          </div>
        </div>

        <!-- X-axis labels -->
        <div class="flex justify-between mt-3 text-[10px] font-bold text-gray-700 uppercase tracking-widest px-0.5">
          <span v-for="m in months" :key="m">{{ m }}</span>
        </div>
      </div>

      <!-- Top Bots (2 cols) -->
      <div class="card p-6 lg:col-span-2 flex flex-col" style="min-height: 340px;">
        <div class="mb-6">
          <h3 class="font-bold text-white text-sm">Top Performing Bots</h3>
          <p class="text-xs text-gray-600 mt-0.5">By conversation share</p>
        </div>
        <div class="space-y-5 flex-1">
          <div v-for="(bot, idx) in topBots" :key="bot.name" class="space-y-2">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-2">
                <span class="text-[11px] font-bold text-gray-600 w-4">{{ idx + 1 }}</span>
                <span class="text-sm font-semibold text-gray-300 truncate max-w-[110px]">{{ bot.name }}</span>
              </div>
              <span class="text-xs font-bold text-gray-400">{{ bot.percentage }}%</span>
            </div>
            <div class="w-full h-1.5 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.05);">
              <div
                class="h-full rounded-full transition-all duration-700"
                :style="`width: ${bot.percentage}%; background: ${bot.color};`"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ─── Activity Feed ─── -->
    <div class="card p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="font-bold text-white text-sm">Recent Activity</h3>
          <p class="text-xs text-gray-600 mt-0.5">Latest conversations across all bots</p>
        </div>
        <span class="badge" style="background: rgba(99,102,241,0.12); color: #818cf8; border: 1px solid rgba(99,102,241,0.2);">Live</span>
      </div>

      <div class="space-y-3">
        <div v-for="item in recentActivity" :key="item.id"
             class="flex items-center space-x-4 p-3 rounded-xl transition-colors"
             style="background: rgba(255,255,255,0.02);">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-white text-xs font-black"
               :style="`background: ${item.color};`">
            {{ item.bot.charAt(0) }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-gray-300 truncate">{{ item.bot }}</p>
            <p class="text-xs text-gray-600 truncate">{{ item.preview }}</p>
          </div>
          <span class="text-[10px] text-gray-700 flex-shrink-0">{{ item.time }}</span>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';

/* ── KPI Stats ── */
const chatSvg  = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>`;
const boltSvg  = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>`;
const chipSvg  = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>`;
const smileSvg = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;

const stats = [
  { label: 'Total Chats',    value: '24,592', trend: 12, icon: chatSvg,  gradient: 'linear-gradient(135deg,#6366f1,#4f46e5)', glow: '#6366f1' },
  { label: 'Avg Response',   value: '1.2s',   trend: 8,  icon: boltSvg,  gradient: 'linear-gradient(135deg,#8b5cf6,#7c3aed)', glow: '#8b5cf6' },
  { label: 'Tokens Used',    value: '1.2M',   trend: 24, icon: chipSvg,  gradient: 'linear-gradient(135deg,#f59e0b,#f97316)', glow: '#f59e0b' },
  { label: 'Satisfaction',   value: '98%',    trend: 3,  icon: smileSvg, gradient: 'linear-gradient(135deg,#10b981,#06b6d4)', glow: '#10b981' },
];

/* ── Chart ── */
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const rawValues = [42, 68, 55, 89, 72, 95, 63, 110, 88, 74, 120, 98];
const maxVal = Math.max(...rawValues);
const chartBars = reactive(rawValues.map(v => ({
  h: Math.round((v / maxVal) * 85) + 10,
  value: v,
  active: false,
})));

/* ── Top Bots ── */
const topBots = [
  { name: 'Support Bot',      percentage: 85, color: 'linear-gradient(90deg,#6366f1,#818cf8)' },
  { name: 'Sales Assistant',  percentage: 62, color: 'linear-gradient(90deg,#8b5cf6,#a78bfa)' },
  { name: 'Knowledge Base',   percentage: 45, color: 'linear-gradient(90deg,#06b6d4,#22d3ee)' },
  { name: 'Onboarding AI',    percentage: 28, color: 'linear-gradient(90deg,#10b981,#34d399)' },
];

/* ── Activity ── */
const recentActivity = [
  { id: 1, bot: 'Support Bot',     preview: 'How do I reset my password?',        time: '2m ago',  color: 'linear-gradient(135deg,#6366f1,#4f46e5)' },
  { id: 2, bot: 'Sales Assistant', preview: 'What are the enterprise pricing plans?', time: '5m ago',  color: 'linear-gradient(135deg,#8b5cf6,#7c3aed)' },
  { id: 3, bot: 'Knowledge Base',  preview: 'Explain the onboarding process',     time: '12m ago', color: 'linear-gradient(135deg,#06b6d4,#0891b2)' },
  { id: 4, bot: 'Onboarding AI',   preview: 'Can you show me the dashboard?',     time: '18m ago', color: 'linear-gradient(135deg,#10b981,#059669)' },
  { id: 5, bot: 'Support Bot',     preview: 'I need help with my subscription',   time: '31m ago', color: 'linear-gradient(135deg,#6366f1,#4f46e5)' },
];
</script>
