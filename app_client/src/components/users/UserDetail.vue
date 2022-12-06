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
  }
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

const convertBase64 = (file) => {
    return new Promise((resolve, reject) => {
        const fileReader = new FileReader();
        fileReader.readAsDataURL(file);

        fileReader.onload = () => {
            resolve(fileReader.result);
        };

        fileReader.onerror = (error) => {
            reject(error);
        };
    });
};

const uploadImage = async (e) => {
  const image = e.target.files[0]
    const base64 = await convertBase64(image)
    editingUser.value.photo = base64
};

</script>

<template>
  <form class="row pt-4 needs-validation justify-content-center" novalidate @submit.prevent="save">
    <h3 class="mb-3">Profile</h3>
    <hr>
    <div class="w-75">
      <div class="text-center pt-3 mb-3">
        <img :src="photoFullUrl" class="img-fluid rounded-circle z-depth-0"/>
        <br />
        <input type="file" name="photo" id="photo" class="inputPhoto text-primary" @change="uploadImage" />
        <label class="text-primary" for="photo">Alterar Foto</label>
        <field-error-message :errors="errors" fieldName="photo"></field-error-message>
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

.img-fluid {
  width: 115px;
  height: 115px;
}

</style>