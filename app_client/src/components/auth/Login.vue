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

const userStore = useUserStore()

const emit = defineEmits(['login'])

const login = async () => {
  if (await userStore.login(credentials.value)) {
    toast.success('User ' + userStore.user.name + ' has entered the application.')
    
    emit('login')
    router.back()

  } else {
    credentials.value.password = ''

    toast.error('Invalid access data. Please try again!')
  }
}
</script>

<template>
  <form class="row pt-4 needs-validation" novalidate @submit.prevent="login">
    <h3 class="mb-3">Login</h3>
    <hr>
    <div class="">
      <div class="mb-3">
        <label for="inputUsername" class="form-label">Username</label>
        <input type="text" class="form-control" id="inputUsername" required v-model="credentials.username">
      </div>
      <div class="mb-3">
        <label for="inputPassword" class="form-label">Password</label>
        <input type="password" class="form-control" id="inputPassword" required v-model="credentials.password">
      </div>
      <div class="mb-3 d-flex justify-content-center">
        <button type="button" class="btn btn-primary px-5" @click="login">Login</button>
      </div>
    </div>
  </form>
</template>

