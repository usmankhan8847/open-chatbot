import { useAuthStore } from '@/stores/auth.js';

export function setupGuards(router) {
    router.beforeEach((to, _from, next) => {
        const auth = useAuthStore();

        // Route requires authenticated user
        if (to.meta.requiresAuth && !auth.isLoggedIn) {
            return next({ name: 'login', query: { redirect: to.fullPath } });
        }

        // Route requires admin role
        if (to.meta.requiresAdmin && !auth.isAdmin) {
            return next({ name: 'forbidden' });
        }

        // Route is guest-only (login/register), redirect if already logged in
        if (to.meta.guest && auth.isLoggedIn) {
            return next({ name: 'chat-home' });
        }

        next();
    });
}
