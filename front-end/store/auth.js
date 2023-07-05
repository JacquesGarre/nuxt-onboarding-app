
import { defineStore } from 'pinia'

const baseUrl = import.meta.env.VITE_API_URL

export const useAuthStore = defineStore({
  id: 'auth',
  state: () => ({
    /* Initialize state from local storage to enable user to stay logged in */
    user: JSON.parse(localStorage.getItem('user')),
    token: JSON.parse(localStorage.getItem('token')),
  }),
  actions: {
    async login(user) {
      await $fetch(`${baseUrl}/auth`, {
        headers: {
            "Content-Type": "application/json",
            "x-api-key": import.meta.env.VITE_API_TOKEN
        },
        method: 'POST',
        body: user
      })
        .then(response => {
            this.user = user
            this.token = response.token
            localStorage.setItem('user', JSON.stringify(this.user))
            localStorage.setItem('token', JSON.stringify(this.token))
        })
        .catch(error => { throw error })
    },
    logout() {
      this.user = null
      this.token = null
      localStorage.removeItem('user')
      localStorage.removeItem('token')
    }
  }
})