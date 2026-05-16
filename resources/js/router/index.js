import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const routes = [
    {
        path: '/login',
        component: () => import('../layouts/AuthLayout.vue'),
        children: [
            {
                path: '',
                name: 'login',
                component: () => import('../views/auth/Login.vue'),
            },
        ],
    },
    {
        path: '/',
        component: () => import('../layouts/AppLayout.vue'),
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                redirect: '/dashboard',
            },
            {
                path: 'dashboard',
                name: 'dashboard',
                component: () => import('../views/Dashboard.vue'),
            },
            {
                path: 'bots',
                name: 'bots',
                component: () => import('../views/bots/BotList.vue'),
            },
            {
                path: 'bots/create',
                name: 'bots.create',
                component: () => import('../views/bots/BotCreateView.vue'),
            },
            {
                path: 'bots/:id/edit',
                name: 'bots.edit',
                component: () => import('../views/bots/BotEditView.vue'),
            },
            {
                path: 'analytics',
                name: 'analytics',
                component: () => import('../views/Analytics.vue'),
            },
            {
                path: 'settings',
                name: 'settings',
                component: () => import('../views/Settings.vue'),
            },
        ],
    },
    // Fallback
    {
        path: '/:pathMatch(.*)*',
        redirect: '/dashboard',
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const auth = useAuthStore();
    
    // Fetch user if token exists but user doesn't
    if (auth.token && !auth.user) {
        await auth.fetchUser();
    }

    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        next({ name: 'login' });
    } else if (to.name === 'login' && auth.isAuthenticated) {
        next({ name: 'dashboard' });
    } else {
        next();
    }
});

export default router;
