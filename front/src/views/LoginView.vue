<template>
  <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="row w-100 justify-content-center">
      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-body p-4">
            <div class="text-center mb-4">
              <h3 class="card-title">Sistema de Gestión</h3>
              <p class="text-muted">Inicia sesión para continuar</p>
            </div>
            
            <form @submit.prevent="handleLogin">
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="form-control"
                  required
                  placeholder="Ingresa tu email"
                />
              </div>
              
              <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input
                  id="password"
                  v-model="form.password"
                  type="password"
                  class="form-control"
                  required
                  placeholder="Ingresa tu contraseña"
                />
              </div>

              <div v-if="error" class="alert alert-danger" role="alert">
                {{ error }}
              </div>

              <button
                type="submit"
                :disabled="loading"
                class="btn btn-primary w-100"
              >
                <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                {{ loading ? 'Iniciando sesión...' : 'Iniciar Sesión' }}
              </button>
            </form>

            <div class="mt-4">
              <div class="alert alert-info" role="alert">
                <strong>Credenciales de prueba:</strong><br>
                <strong>Admin:</strong> admin@example.com / password<br>
                <strong>Usuario:</strong> user1@example.com / password
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: ''
})

const loading = ref(false)
const error = ref('')

const handleLogin = async () => {
  loading.value = true
  error.value = ''

  const result = await authStore.login(form.value)
  
  if (result.success) {
    router.push('/dashboard')
  } else {
    error.value = result.message
  }
  
  loading.value = false
}
</script> 