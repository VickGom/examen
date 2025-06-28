<template>
  <div class="min-vh-100 bg-light">
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <a class="navbar-brand" href="#">Sistema de Gestión</a>
        <div class="navbar-nav ms-auto">
          <span class="navbar-text me-3">
            Bienvenido, {{ authStore.user?.name }}
          </span>
          <button
            @click="handleLogout"
            class="btn btn-outline-light"
          >
            Cerrar Sesión
          </button>
        </div>
      </div>
    </nav>

    <!-- Navegacion -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
      <div class="container">
        <div class="navbar-nav">
          <router-link
            to="/dashboard"
            class="nav-link active"
          >
            Dashboard
          </router-link>
          <router-link
            to="/equipos"
            class="nav-link"
          >
            Equipos
          </router-link>
          <router-link
            v-if="authStore.isAdmin()"
            to="/usuarios"
            class="nav-link"
          >
            Usuarios
          </router-link>
        </div>
      </div>
    </nav>

    <!-- el contenido principal -->
    <div class="container mt-4">
      <div class="row">
        <div class="col-12">
          <h2 class="mb-4">Dashboard</h2>
        </div>
      </div>

      <!-- las cardas de los equipos y usuarios validados por rol -->
      <div class="row mb-4">
        <div class="col-md-6 mb-3">
          <div class="card">
            <div class="card-body text-center">
              <h5 class="card-title">Equipos Registrados</h5>
              <h2 class="text-primary">{{ equiposCount }}</h2>
              <router-link to="/equipos" class="btn btn-outline-primary btn-sm">
                Ver equipos
              </router-link>
            </div>
          </div>
        </div>

        <div v-if="authStore.isAdmin()" class="col-md-6 mb-3">
          <div class="card">
            <div class="card-body text-center">
              <h5 class="card-title">Usuarios Registrados</h5>
              <h2 class="text-success">{{ usuariosCount }}</h2>
              <router-link to="/usuarios" class="btn btn-outline-success btn-sm">
                Ver usuarios
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/config/axios'

const router = useRouter()
const authStore = useAuthStore()

const equiposCount = ref(0)
const usuariosCount = ref(0)

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

const loadCounts = async () => {
  try {
    const equiposResponse = await api.get('/equipos')
    equiposCount.value = equiposResponse.data.data.length
    
    // Solo cargar usuarios si es administrador
    if (authStore.isAdmin()) {
      const usuariosResponse = await api.get('/users')
      usuariosCount.value = usuariosResponse.data.data.length
    }
  } catch (error) {
    console.error('Error cargando estadísticas:', error)
  }
}

onMounted(() => {
  loadCounts()
})
</script> 