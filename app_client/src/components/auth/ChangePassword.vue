<script setup>
import { ref } from 'vue'

const passwords = ref({
  current_password: '',
  password: '',
  password_confirm: ''
})

const emit = defineEmits(['changedPassword'])

const changePassword = () => {
  // FALTA FAZER O LOGIN
  emit('changedPassword')
}
</script>

<template>
  <form class="row pt-4 needs-validation justify-content-center" novalidate @submit.prevent="changePassword">
    <h3 class="mb-3">Change Password</h3>
    <hr>
    <div class="w-50 pt-5 mt-5">
      <div class="mb-3">
        <div class="mb-3">
          <label for="inputCurrentPassword" class="form-label">Current Password</label>
          <input type="password" class="form-control" id="inputCurrentPassword" required
            v-model="passwords.current_password">
        </div>
      </div>
      <div class="mb-3">
        <div class="mb-3">
          <label for="inputPassword" class="form-label">New Password</label>
          <input type="password" class="form-control" id="inputPassword" required v-model="passwords.password">
        </div>
      </div>
      <div class="mb-3">
        <div class="mb-3">
          <label for="inputPasswordConfirm" class="form-label">Password Confirmmation</label>
          <input type="password" class="form-control" id="inputPasswordConfirm" required
            v-model="passwords.password_confirm">
        </div>
      </div>
      <div class="mb-3 d-flex justify-content-center">
        <button type="button" class="btn btn-primary" @click="changePassword">Submit</button>
      </div>
    </div>
  </form>
</template>

