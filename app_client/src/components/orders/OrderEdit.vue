<script setup>
import { ref, watch, computed } from 'vue'

const props = defineProps({
  order: {
    type: Object,
    required: true
  },
  errors: {
    type: Object,
    required: false
  },
  operationType: {
    type: String,
    default: 'insert'  // insert / update
  },
  users: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['save', 'cancel'])

const editingOrder = ref(props.order)

watch(
  () => props.order,
  (newOrder) => {
    editingOrder.value = newOrder
  }
)

const orderTitle = computed(() => {
  if (!editingOrder.value) {
    return ''
  }
  return props.operationType == 'insert' ? 'New Order' : 'Order #' + editingOrder.value.id
})

const save = () => {
  emit('save', editingOrder.value)
}

const cancel = () => {
  emit('cancel', editingOrder.value)
}

</script>

<template>
  <form class="row needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-4 mb-3">{{ orderTitle }}</h3>
    <hr>

    <div class="mb-3">
      <label for="inputTicket" class="form-label">Order Ticket</label>
      <input type="text" class="form-control" id="inputTicket" placeholder="Order Ticket" required
        v-model="editingOrder.ticket_number">
      <field-error-message :errors="errors" fieldName="name"></field-error-message>
    </div>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 me-3 flex-grow-1">
        <label for="inputDriver" class="form-label">Driver to Deliver</label>
        <select class="form-select pe-2" id="inputDriver" v-model="editingOrder.user_name">
          <option :value="null">-- No Driver --</option>
          <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
        </select>
        <field-error-message :errors="errors" fieldName="user_name"></field-error-message>
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1">
        <label for="inputOrder" class="form-label">Status</label>
        <select class="form-select" id="inputOrder" v-model="editingOrder.status">
          <option :value="null"></option>
          <option value="P">Preparing</option>
          <option value="R">Ready</option>
          <option value="C">Cancelled</option>
          <option value="D">Delivered</option>
        </select>
        <field-error-message :errors="errors" fieldName="status"></field-error-message>
      </div>
    </div>
    <div class="d-flex flex-wrap justify-content-between">
      <div class="row mb-3 total_price">
        <label for="inputTotalPrice" class="col-sm-3 col-form-label">Total Price</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="inputTotalPrice" v-model="editingOrder.total_price">
          <field-error-message :errors="errors" fieldName="total_price"></field-error-message>
        </div>
      </div>
    </div>

    <div class="mb-3 d-flex justify-content-end">
      <button type="button" class="btn btn-primary px-5" @click="save">Save</button>
      <button type="button" class="btn btn-light px-5" @click="cancel">Cancel</button>
    </div>

  </form>
</template>

<style scoped>
.total_price {
  width: 26rem;
}
</style>