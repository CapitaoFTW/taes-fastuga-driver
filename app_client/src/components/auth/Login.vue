<script setup>
import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '../../stores/user.js'

const router = useRouter()
const toast = inject('toast')

const credentials = ref({
  username: '',
  password: ''
})

const errors = ref(null)
const userStore = useUserStore()

const emit = defineEmits(['login'])

const login = async () => {
  errors.value = null
  const error = await userStore.login(credentials.value)

  if (error) {
    credentials.value.password = ''

    //toast.error('User credentials are invalid!')
    errors.value = 'Invalid credentials!'

  } else {
    toast.success('User ' + userStore.user.name + ' has entered the application.')

    emit('login')
    router.push({ name: 'Orders' })
  }
}
</script>

<template>
  <form class="row pt-4 needs-validation justify-content-center" novalidate @submit.prevent="login">
    <h3 class="mb-3">Login</h3>
    <hr>
    <div class="w-75">
        <div v-if="errors" class="alert alert-danger text-center" role="alert">
          {{ errors }}
        </div>
        <div v-else class="mb-5 pb-4">
        </div>
      <div class="mb-3">
        <label for="inputUsername" class="form-label">E-mail</label>
        <input type="text" class="form-control" id="inputUsername" v-model="credentials.username">
      </div>
      <div class="mb-3">
        <label for="inputPassword" class="form-label">Password</label>
        <input type="password" class="form-control" id="inputPassword" v-model="credentials.password">
      </div>
      <div class="mt-4 d-flex justify-content-center">
        <button type="button" class="btn btn-primary px-5" @click="login">Login</button>
      </div>
    </div>
  </form>
</template>

