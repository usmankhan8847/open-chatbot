import { defineStore } from 'pinia';
import api from '../services/api';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token') || null,
        loading: false,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
    },

    actions: {
        async login(credentials) {
            this.loading = true;
            try {
                const response = await api.post('/auth/login', credentials);
                this.token = response.data.access_token;
                this.user = response.data.user;
                localStorage.setItem('auth_token', this.token);
                return response.data;
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            try {
                await api.post('/auth/logout');
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem('auth_token');
            }
        },

        async fetchUser() {
            if (!this.token) return;
            
            try {
                const response = await api.get('/auth/me');
                this.user = response.data;
            } catch (error) {
                this.logout();
            }
        },
    },
});
