import { defineStore } from 'pinia'
import { api } from '../services/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('poti_user') || 'null'),
    token: localStorage.getItem('poti_token'),
    loading: false,
    error: '',
  }),

  getters: {
    isAuthenticated: (state) => Boolean(state.token && state.user),
    isAdmin: (state) => state.user?.role === 'admin',
    isPiket: (state) => state.user?.role === 'piket',
  },

  actions: {
    async login(payload) {
      this.loading = true
      this.error = ''

      try {
        const { data } = await api.post('/login', payload)
        this.user = data.user
        this.token = data.token
        localStorage.setItem('poti_user', JSON.stringify(data.user))
        localStorage.setItem('poti_token', data.token)
      } catch (error) {
        this.error = error.response?.data?.message || 'Login gagal. Periksa email/username dan password.'
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      try {
        if (this.token) {
          await api.post('/logout')
        }
      } finally {
        this.user = null
        this.token = null
        localStorage.removeItem('poti_user')
        localStorage.removeItem('poti_token')
      }
    },
  },
})
