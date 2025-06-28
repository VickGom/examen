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

//En esta logica se encarga de la autenticacion del usuario, con rutas privadas o publicas en base al back
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  

  if (to.meta.requiresAuth) {
    if (!authStore.isAuthenticated()) {
      next('/login')
      return
    }
    

    if (!authStore.user) {
      const isAuthenticated = await authStore.checkAuth()
      if (!isAuthenticated) {
        next('/login')
        return
      }
    }
    

    if (to.meta.requiresAdmin && !authStore.isAdmin()) {
      next('/dashboard')
      return
    }
  }
  

  if (to.meta.requiresGuest && authStore.isAuthenticated()) {
    next('/dashboard')
    return
  }
  
  next()
})

export default router 