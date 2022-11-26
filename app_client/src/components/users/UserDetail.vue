<script setup>
import { ref, watch, computed, inject } from "vue";
import avatarNoneUrl from '@/assets/avatar-none.png'

const serverBaseUrl = inject("serverBaseUrl");

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    required: false
  }/*,
  uploadFile() {
    var files = this.$refs.photo.files;
    var data = new FormData();
   // data.append('logo', files[0]);
   console.log(data);
}*/
})

const emit = defineEmits(["save", "cancel"]);

const editingUser = ref(props.user)

watch(
  () => props.user,

  (newUser) => {
    editingUser.value = newUser
  },

  { immediate: true }
)

const photoFullUrl = computed(() => {
  return editingUser.value.photo_url ? serverBaseUrl + "/storage/fotos/" + editingUser.value.photo_url : avatarNoneUrl
})

const save = () => {
  emit("save", editingUser.value);
}

const cancel = () => {
  emit("cancel", editingUser.value);
}

</script>

<template>
  <form class="row pt-4 needs-validation justify-content-center" novalidate @submit.prevent="save">
    <h3 class="mb-3">Profile</h3>
    <hr>
    <div class="w-50">
      <div class="text-center pt-3">
        <img :src="photoFullUrl" class="img-fluid rounded-circle" />
        <br />
        <input ref="photo" type="file" name="photo" id="photo" class="inputPhoto text-primary" @change="uploadFile" />
        <label class="text-primary" for="photo">Alterar Foto</label>
        <field-error-message :errors="errors" fieldName="photo_url"></field-error-message>
      </div>
      <div class="mb-2">
        <label for="inputName" class="form-label">Name</label>
        <input type="text" class="form-control" id="inputName" placeholder="Name" v-model="editingUser.name" />
        <field-error-message :errors="errors" fieldName="name"></field-error-message>
      </div>
      <div class="mb-2">
        <label for="inputEmail" class="form-label">Email</label>
        <input type="email" class="form-control" id="inputEmail" placeholder="Email" disabled
          v-model="editingUser.email" />
        <field-error-message :errors="errors" fieldName="email"></field-error-message>
      </div>
      <div class="mb-2">
        <label for="inputLicense" class="form-label">License Plate</label>
        <input type="text" class="form-control" id="inputLicense" placeholder="License Plate"
          v-model="editingUser.license_plate" />
        <field-error-message :errors="errors" fieldName="license_plate"></field-error-message>
      </div>
      <div class="mb-3">
        <label for="inputPhone" class="form-label">Phone Number</label>
        <input type="text" class="form-control" id="inputPhone" placeholder="Phone Number"
          v-model="editingUser.phone_number" />
        <field-error-message :errors="errors" fieldName="phone_number"></field-error-message>
      </div>
      <div class="d-flex ms-1 mt-4 justify-content-around">
        <!--<div class="d-flex mb-3 ms-xs-3 flex-grow-1">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="radioGender" value="M" required
              v-model="editingUser.gender" id="inputGenderM" />
            <label class="form-check-label" for="inputGenderM">Masculino</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="radioGender" value="F" v-model="editingUser.gender"
              id="inputGenderF" />
            <label class="form-check-label" for="inputGenderF">Feminino</label>
          </div>
          <field-error-message :errors="errors" fieldName="gender"></field-error-message>
        </div>-->
      </div>
    </div>
    <div class="mt-3 mb-5 d-flex justify-content-center">
      <button type="button" class="btn btn-primary px-5" @click="save">Save</button>
      <button type="button" class="btn btn-light px-5" @click="cancel">Cancel</button>
    </div>
  </form>
</template>

<style scoped>
.total_hours {
  width: 26rem;
}

.inputPhoto {
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  position: absolute;
  z-index: -1;
}

.inputPhoto + label {
  cursor: pointer;
  /* "hand" cursor */
  font-weight: bold;
}
</style>