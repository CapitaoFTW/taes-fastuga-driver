<script setup>
</script>

<template>
  <div class="d-flex justify-content-start">
    <div class="mx-2">
      <h3 class="mt-4">Statistics</h3>
    </div>
  </div>
  <hr>
  <div class="d-flex justify-content-center">
    <div class="w-75 mt-5">
      <div class="mb-3">
        <div class="mb-3">
          <label for="inputCurrentPassword" class="form-label">Current Password</label>
          <input type="password" class="form-control" id="inputCurrentPassword" disabled>
        </div>
      </div>
      <div class="mb-3">
        <div class="mb-3">
          <label for="inputPassword" class="form-label">New Password</label>
          <input type="password" class="form-control" id="inputPassword" disabled>
        </div>
      </div>
      <div class="mb-3">
        <div class="mb-3">
          <label for="inputPasswordConfirm" class="form-label">Password Confirmation</label>
          <input type="password" class="form-control" id="inputPasswordConfirm" disabled>

        </div>
      </div>
      <div class="mb-3 d-flex justify-content-center">
        <button type="button" class="btn btn-primary" @click="changePassword">Change</button>
      </div>
    </div>
  </div>
</template>
  