import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/config/axios'

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null)
    const token = ref(localStorage.getItem('token') || null)

    const login = async (credentials) => {
        try {
            const response = await api.post('/login', credentials)
            const { user: userData, token: tokenData } = response.data
            
            user.value = userData
            token.value = tokenData
            
            localStorage.setItem('token', tokenData)
            localStorage.setItem('user', JSON.stringify(userData))
            
            return { success: true }
        } catch (error) {
            return { 
                success: false, 
                message: error.response?.data?.message || 'Error al iniciar sesión' 
            }
        }
    }

    const logout = async () => {
        try {
            await api.post('/logout')
        } catch (error) {
            console.error('Error al cerrar sesión:', error)
        } finally {
            user.value = null
            token.value = null
            localStorage.removeItem('token')
            localStorage.removeItem('user')
        }
    }

    const checkAuth = async () => {
        try {
            const response = await api.get('/me')
            user.value = response.data.user
            return true
        } catch (error) {
            logout()
            return false
        }
    }

    const isAuthenticated = () => {
        return !!token.value
    }

    const isAdmin = () => {
        return user.value?.role === 'admin'
    }

    return {
        user,
        token,
        login,
        logout,
        checkAuth,
        isAuthenticated,
        isAdmin
    }
}) 