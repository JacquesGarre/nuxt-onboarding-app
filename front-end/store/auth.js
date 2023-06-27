import { defineStore } from 'pinia';


export const useAuthStore = defineStore('auth', {

    state: () => ({
        token: null
    }),
    getters: {
        isAuthenticated: (state) => {
            const cookieToken = useCookie('token')
            return cookieToken.value !== null
        }
    },
    actions: {
        setToken(token) {
            this.token = token
            const cookieToken = useCookie('token')
            cookieToken.value = token
        },
        clearToken() {
            this.token = null
        }
    }

});