<template>
  <div class="min-vh-100 bg-light">
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <a class="navbar-brand" href="#">Sistema de Gestion</a>
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

    <!-- navegacion -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
      <div class="container">
        <div class="navbar-nav">
          <router-link
            to="/dashboard"
            class="nav-link"
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
            to="/usuarios"
            class="nav-link active"
          >
            Usuarios
          </router-link>
        </div>
      </div>
    </nav>

    <!-- contenido principal -->
    <div class="container mt-4">
      <div class="row mb-4">
        <div class="col-md-8">
          <h2>Gestión de Usuarios</h2>
        </div>
        <div class="col-md-4 text-end">
          <button
            @click="openModal()"
            class="btn btn-primary"
          >
            <i class="bi bi-person-plus me-2"></i>
            Nuevo Usuario
          </button>
        </div>
      </div>

      <!-- esta parte si por algun futuro se agrega los usuarios al rol usuario -->
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Lista de Usuarios</h5>
        </div>
        <div class="card-body">
          <div v-if="users.length > 0">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="user in users" :key="user.id">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                      <span 
                        :class="[
                          'badge',
                          user.role === 'admin' ? 'bg-danger' : 'bg-success'
                        ]"
                      >
                        {{ user.role === 'admin' ? 'Administrador' : 'Usuario' }}
                      </span>
                    </td>
                    <td>
                      <button
                        @click="openModal(user)"
                        class="btn btn-sm btn-outline-primary me-2"
                      >
                        <i class="bi bi-pencil"></i>
                      </button>
                      <button
                        @click="confirmDelete(user)"
                        class="btn btn-sm btn-outline-danger"
                      >
                        <i class="bi bi-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <!-- esta parte si por algun futuro se agrega los usuarios al rol usuario -->
          <div v-else class="text-center py-5">
            <i class="bi bi-people display-1 text-muted"></i>
            <h5 class="mt-3">No hay usuarios</h5>
            <p class="text-muted">Comienza agregando un nuevo usuario.</p>
            <button
              @click="openModal()"
              class="btn btn-primary"
            >
              <i class="bi bi-person-plus me-2"></i>
              Nuevo Usuario
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ editingUser ? 'Editar Usuario' : 'Nuevo Usuario' }}
            </h5>
            <button
              @click="closeModal"
              type="button"
              class="btn-close"
            ></button>
          </div>
          <form @submit.prevent="handleSubmit">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Nombre *</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': errors.name }"
                  required
                  placeholder="Nombre completo"
                  minlength="2"
                  maxlength="255"
                />
                <div v-if="errors.name" class="invalid-feedback">
                  {{ errors.name[0] }}
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Email *</label>
                <input
                  v-model="form.email"
                  type="email"
                  class="form-control"
                  :class="{ 'is-invalid': errors.email }"
                  required
                  placeholder="email@ejemplo.com"
                  maxlength="255"
                />
                <div v-if="errors.email" class="invalid-feedback">
                  {{ errors.email[0] }}
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Contraseña {{ editingUser ? '' : '*' }}</label>
                <input
                  v-model="form.password"
                  type="password"
                  class="form-control"
                  :class="{ 'is-invalid': errors.password }"
                  :required="!editingUser"
                  placeholder="Contraseña"
                  :minlength="editingUser ? 0 : 8"
                />
                <div v-if="errors.password" class="invalid-feedback">
                  {{ errors.password[0] }}
                </div>
                <div v-if="editingUser" class="form-text">
                  Deja vacío para mantener la contraseña actual
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Rol *</label>
                <select
                  v-model="form.role"
                  class="form-select"
                  :class="{ 'is-invalid': errors.role }"
                  required
                >
                  <option value="">Selecciona un rol</option>
                  <option value="user">Usuario</option>
                  <option value="admin">Administrador</option>
                </select>
                <div v-if="errors.role" class="invalid-feedback">
                  {{ errors.role[0] }}
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                @click="closeModal"
                class="btn btn-secondary"
              >
                Cancelar
              </button>
              <button
                type="submit"
                :disabled="loading"
                class="btn btn-primary"
              >
                <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                {{ loading ? 'Guardando...' : (editingUser ? 'Actualizar' : 'Crear') }}
              </button>
            </div>
          </form>
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

const users = ref([])
const showModal = ref(false)
const editingUser = ref(null)
const loading = ref(false)
const errors = ref({})

const form = ref({
  name: '',
  email: '',
  password: '',
  role: ''
})

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

const loadUsers = async () => {
  try {
    const response = await api.get('/users')
    users.value = response.data.data
  } catch (error) {
    console.error('Error cargando usuarios:', error)
  }
}

const openModal = (user = null) => {
  editingUser.value = user
  errors.value = {}
  
  if (user) {
    form.value = {
      name: user.name,
      email: user.email,
      password: '',
      role: user.role
    }
  } else {
    form.value = {
      name: '',
      email: '',
      password: '',
      role: ''
    }
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingUser.value = null
  errors.value = {}
  form.value = {
    name: '',
    email: '',
    password: '',
    role: ''
  }
}

const handleSubmit = async () => {
  loading.value = true
  errors.value = {}
  
  try {
    const submitData = { ...form.value }
    if (!submitData.password) {
      delete submitData.password
    }
    
    if (editingUser.value) {
      await api.put(`/users/${editingUser.value.id}`, submitData)
    } else {
      await api.post('/users', submitData)
    }
    closeModal()
    loadUsers()
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error('Error guardando usuario:', error)
      alert('Error al guardar el usuario. Por favor, inténtalo de nuevo.')
    }
  } finally {
    loading.value = false
  }
}

const confirmDelete = async (user) => {
  if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
    try {
      await api.delete(`/users/${user.id}`)
      loadUsers()
    } catch (error) {
      console.error('Error eliminando usuario:', error)
      alert('Error al eliminar el usuario. Por favor, inténtalo de nuevo.')
    }
  }
}

onMounted(() => {
  loadUsers()
})
</script> 