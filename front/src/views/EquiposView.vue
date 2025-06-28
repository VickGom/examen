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
            class="nav-link active"
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

    <!-- contenido principal -->
    <div class="container mt-4">
      <div class="row mb-4">
        <div class="col-md-8">
          <h2>Gestión de Equipos</h2>
        </div>
        <div class="col-md-4 text-end">
          <button
            @click="openModal()"
            class="btn btn-primary"
          >
            <i class="bi bi-plus-circle me-2"></i>
            Nuevo Equipo
          </button>
        </div>
      </div>

      <!-- buscador -->
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="input-group">
            <input
              v-model="searchTerm"
              @input="handleSearch"
              type="text"
              class="form-control"
              placeholder="Buscar equipos..."
            />
            <button class="btn btn-outline-secondary" type="button">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- muestra los equipos -->
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Lista de Equipos</h5>
        </div>
        <div class="card-body">
          <div v-if="equipos.length > 0">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Tipo de Motor</th>
                    <th>Fecha Fabricación</th>
                    <th>Potencia (kW)</th>
                    <th>Velocidad (RPM)</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="equipo in equipos" :key="equipo.id">
                    <td>{{ equipo.nombre }}</td>
                    <td>{{ equipo.tipo_motor }}</td>
                    <td>{{ formatDate(equipo.fecha_fabricacion) }}</td>
                    <td>{{ equipo.potencia }}</td>
                    <td>{{ equipo.velocidad }}</td>
                    <td>
                      <button
                        @click="openModal(equipo)"
                        class="btn btn-sm btn-outline-primary me-2"
                      >
                        <i class="bi bi-pencil"></i>
                      </button>
                      <button
                        @click="confirmDelete(equipo)"
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
          
          <!-- cuando no hay usuarios se muestra esta parte -->
          <div v-else class="text-center py-5">
            <i class="bi bi-box display-1 text-muted"></i>
            <h5 class="mt-3">No hay equipos</h5>
            <p class="text-muted">Comienza agregando un nuevo equipo.</p>
            <button
              @click="openModal()"
              class="btn btn-primary"
            >
              <i class="bi bi-plus-circle me-2"></i>
              Nuevo Equipo
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
              {{ editingEquipo ? 'Editar Equipo' : 'Nuevo Equipo' }}
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
                <label class="form-label">Tipo de Motor *</label>
                <input
                  v-model="form.tipo_motor"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': errors.tipo_motor }"
                  required
                  placeholder="Ej: Eléctrico, Diesel, Gasolina"
                />
                <div v-if="errors.tipo_motor" class="invalid-feedback">
                  {{ errors.tipo_motor[0] }}
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Nombre *</label>
                <input
                  v-model="form.nombre"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': errors.nombre }"
                  required
                  placeholder="Nombre del equipo"
                />
                <div v-if="errors.nombre" class="invalid-feedback">
                  {{ errors.nombre[0] }}
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Fecha de Fabricación *</label>
                <input
                  v-model="form.fecha_fabricacion"
                  type="date"
                  class="form-control"
                  :class="{ 'is-invalid': errors.fecha_fabricacion }"
                  required
                />
                <div v-if="errors.fecha_fabricacion" class="invalid-feedback">
                  {{ errors.fecha_fabricacion[0] }}
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Potencia (kW) *</label>
                <input
                  v-model="form.potencia"
                  type="number"
                  step="0.01"
                  class="form-control"
                  :class="{ 'is-invalid': errors.potencia }"
                  required
                  placeholder="0.00"
                  min="0"
                  max="999999.99"
                />
                <div v-if="errors.potencia" class="invalid-feedback">
                  {{ errors.potencia[0] }}
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Velocidad (RPM) *</label>
                <input
                  v-model="form.velocidad"
                  type="number"
                  class="form-control"
                  :class="{ 'is-invalid': errors.velocidad }"
                  required
                  placeholder="0"
                  min="0"
                  max="999999"
                />
                <div v-if="errors.velocidad" class="invalid-feedback">
                  {{ errors.velocidad[0] }}
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
                {{ loading ? 'Guardando...' : (editingEquipo ? 'Actualizar' : 'Crear') }}
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

const equipos = ref([])
const searchTerm = ref('')
const showModal = ref(false)
const editingEquipo = ref(null)
const loading = ref(false)
const errors = ref({})

const form = ref({
  tipo_motor: '',
  nombre: '',
  fecha_fabricacion: '',
  potencia: '',
  velocidad: ''
})

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

const loadEquipos = async () => {
  try {
    const params = searchTerm.value ? { search: searchTerm.value } : {}
    const response = await api.get('/equipos', { params })
    equipos.value = response.data.data
  } catch (error) {
    console.error('Error cargando equipos:', error)
  }
}

const handleSearch = () => {
  loadEquipos()
}

const openModal = (equipo = null) => {
  editingEquipo.value = equipo
  errors.value = {}
  
  if (equipo) {
    form.value = {
      tipo_motor: equipo.tipo_motor,
      nombre: equipo.nombre,
      fecha_fabricacion: equipo.fecha_fabricacion ? new Date(equipo.fecha_fabricacion).toISOString().split('T')[0] : '',
      potencia: equipo.potencia,
      velocidad: equipo.velocidad
    }
  } else {
    form.value = {
      tipo_motor: '',
      nombre: '',
      fecha_fabricacion: '',
      potencia: '',
      velocidad: ''
    }
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingEquipo.value = null
  errors.value = {}
  form.value = {
    tipo_motor: '',
    nombre: '',
    fecha_fabricacion: '',
    potencia: '',
    velocidad: ''
  }
}

const handleSubmit = async () => {
  loading.value = true
  errors.value = {}
  
  try {
    if (editingEquipo.value) {
      await api.put(`/equipos/${editingEquipo.value.id}`, form.value)
    } else {
      await api.post('/equipos', form.value)
    }
    closeModal()
    loadEquipos()
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error('Error guardando equipo:', error)
      alert('Error al guardar el equipo. Por favor, inténtalo de nuevo.')
    }
  } finally {
    loading.value = false
  }
}

const confirmDelete = async (equipo) => {
  if (confirm('¿Estás seguro de que quieres eliminar este equipo?')) {
    try {
      await api.delete(`/equipos/${equipo.id}`)
      loadEquipos()
    } catch (error) {
      console.error('Error eliminando equipo:', error)
      alert('Error al eliminar el equipo. Por favor, inténtalo de nuevo.')
    }
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('es-ES')
}

onMounted(() => {
  loadEquipos()
})
</script> 