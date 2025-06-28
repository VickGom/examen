import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import LoginView from '@/views/LoginView.vue'
import DashboardView from '@/views/DashboardView.vue'
import EquiposView from '@/views/EquiposView.vue'
import UsuariosView from '@/views/UsuariosView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/login'
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { requiresGuest: true }
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardView,
      meta: { requiresAuth: true }
    },
    {
      path: '/equipos',
      name: 'equipos',
      component: EquiposView,
      meta: { requiresAuth: true }
    },
    {
      path: '/usuarios',
      name: 'usuarios',
      component: UsuariosView,
      meta: { requiresAuth: true, requiresAdmin: true }
    }
  ]
})

// Navigation guards
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  // Check if user is authenticated
  if (to.meta.requiresAuth) {
    if (!authStore.isAuthenticated()) {
      next('/login')
      return
    }
    
    // Check if user data is loaded
    if (!authStore.user) {
      const isAuthenticated = await authStore.checkAuth()
      if (!isAuthenticated) {
        next('/login')
        return
      }
    }
    
    // Check admin requirement
    if (to.meta.requiresAdmin && !authStore.isAdmin()) {
      next('/dashboard')
      return
    }
  }
  
  // Redirect authenticated users away from login
  if (to.meta.requiresGuest && authStore.isAuthenticated()) {
    next('/dashboard')
    return
  }
  
  next()
})

export default router 