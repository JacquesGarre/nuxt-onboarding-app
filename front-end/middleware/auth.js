import { useAuthStore } from '~/store/auth'

export default defineNuxtRouteMiddleware(async () => {
    const authStore = useAuthStore()
    if (!authStore.isAuthenticated) return navigateTo({ path: '/' });
});