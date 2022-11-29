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

    if (error.response.status == 422) {
      toast.error('User credentials are invalid!')
      errors.value = error.response.data.errors

    } else {

      toast.error('User was not updated due to unknown server error!')
    }

  } else {
    toast.success('User ' + userStore.user.name + ' has entered the application.')

    emit('login')
    router.push({ name: 'User', params: { id: userStore.user.id } })
  }
}
</script>

<template>
  <form class="row pt-5 mt-5 needs-validation justify-content-center" novalidate @submit.prevent="login">
    <div class="w-50">
      <h3 class="mb-3 d-flex justify-content-center">Login</h3>
      <div class="mb-3">
        <label for="inputUsername" class="form-label">Username</label>
        <input type="text" class="form-control" id="inputUsername" v-model="credentials.username">
        <field-error-message :errors="errors" fieldName="username"></field-error-message>
      </div>
      <div class="mb-3">
        <label for="inputPassword" class="form-label">Password</label>
        <input type="password" class="form-control" id="inputPassword" v-model="credentials.password">
        <field-error-message :errors="errors" fieldName="password"></field-error-message>
      </div>
      <div class="mt-4 d-flex justify-content-center">
        <button type="button" class="btn btn-primary px-5" @click="login">Login</button>
      </div>
    </div>
  </form>
</template>

