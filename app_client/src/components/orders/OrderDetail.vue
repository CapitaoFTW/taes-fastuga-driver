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

  const OrderTitle = computed(()=>{
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
  <form
    class="row g-3 needs-validation"
    novalidate
    @submit.prevent="save"
  >
    <h3 class="mt-5 mb-3">{{ orderTitle }}</h3>
    <hr>

    <div class="mb-3">
      <label
        for="inputTicket"
        class="form-label"
      >Order Ticket</label>
      <input
        type="text"
        class="form-control"
        id="inputTicket"
        placeholder="Order ticket"
        required
        v-model="editingOrder.ticket_number"
      >
      <field-error-message :errors="errors" fieldName="name"></field-error-message>
    </div>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 me-3 flex-grow-1">
        <label
          for="inputDeliveredBy"
          class="form-label"
        >DeliveredBy</label>
        <select
          class="form-select pe-2"
          id="inputDeliveredBy"
          v-model="editingOrder.delivered_by"
        >
          <option :value="null">-- No DeliveredBy --</option>
          <option
            v-for="user in users"
            :key="user.id"
            :value="user.id"
          >{{user.name}}</option>
        </select>
        <field-error-message :errors="errors" fieldName="deliveredBy"></field-error-message>
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1">
        <label
          for="inputOrder"
          class="form-label"
        >Status</label>
        <select
          class="form-select"
          id="inputOrder"
          v-model="editingOrder.status"
        >
          <option :value="null"></option>
          <option value="P">Preparing</option>
          <option value="R">Ready</option>
          <option value="C">Cancelled</option>
          <option value="D">Delivered</option>
        </select>
        <field-error-message :errors="errors" fieldName="status"></field-error-message>
      </div>
    </div>

    
    <div class="mb-3">
      <label
        for="inputDescription"
        class="form-label"
      >Description</label>
      <textarea
        class="form-control"
        id="inputDescription"
        rows="3"
        v-model="editingOrder.description"
      ></textarea>
      <field-error-message :errors="errors" fieldName="description"></field-error-message>
    </div>

  </form>
</template>

<style scoped>
.total_price {
  width: 26rem;
}
</style>
