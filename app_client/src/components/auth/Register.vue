<script setup>
import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '../../stores/user.js'

const router = useRouter()
const toast = inject('toast')

const credentials = ref({
  name: '',
  username: '',
  password: '',
  password_confirmation: '',
  license_plate: '',
  phone_number: ''
})

const userStore = useUserStore()

const emit = defineEmits(['register'])

const register = async () => {
  if (await userStore.register(credentials.value)) {
    toast.success('User ' + userStore.user.name + ' was successfully registered')
    
    emit('register')
    router.back()

  } else {
    credentials.value.password = ''

    toast.error('User credentials are invalid!')
  }
}

</script>

<template>
  <form class="row pt-4 needs-validation" novalidate @submit.prevent="register">
    <h3 class="mb-3">Register</h3>
    <hr>
    <div class="w-50">
      <div class="mb-3">
        <label for="inputName" class="form-label">Name</label>
        <input type="text" class="form-control" id="inputName" required v-model="credentials.name">
      </div>
      <div class="mb-3">
        <label for="inputEmail" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="inputEmail" required v-model="credentials.username">
      </div>
      <div class="mb-3">
        <label for="inputPassword" class="form-label">Password</label>
        <input type="password" class="form-control" id="inputPassword" required v-model="credentials.password">
      </div>
      <div class="mb-3">
        <label for="inputPasswordConfirm" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="inputPasswordConfirm" required v-model="credentials.password_confirmation">
      </div>
      <div class="mb-3">
        <label for="inputLicensePlate" class="form-label">License Plate</label>
        <input type="text" class="form-control" id="inputLicensePlate" required v-model="credentials.license_plate">
      </div>
      <div class="mb-3">
        <label for="inputPhoneNumber" class="form-label">Phone Number</label>
        <input type="text" class="form-control" id="inputPhoneNumber" required v-model="credentials.phone_number">
      </div>
      <div class="mb-3 d-flex justify-content-center">
        <button type="button" class="btn btn-primary px-5" @click="register">Register</button>
      </div>
    </div>
  </form>
</template>