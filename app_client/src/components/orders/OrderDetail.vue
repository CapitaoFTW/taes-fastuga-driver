<script setup>
import { ref, watch } from 'vue'

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
    default: 'show'
  },
  users: {
    type: Array,
    required: true
  }
})

const order = ref(props.order)

watch(
  () => props.order,
  
  (newOrder) => {
    order.value = newOrder
  }
)

/*const emit = defineEmits(['save', 'cancel'])

const save = () => {
  emit('save', order.value)
}

const cancel = () => {
  emit('cancel', order.value)
}*/

</script>



<template>
  <div class="container">
    <div class="row">
      <h3 class="mt-4 mb-3">Order #{{ order.ticket_number }}</h3>
      <hr>
      <div class="mb-3 d-flex justify-content-start flex-wrap">
        <div class="mx-2 mt-2">
          <div class="row mb-2">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title mt-2">Products</h5>
                <p class="mb-5"></p>
                <p class="d-flex justify-content-between"><span>Produto 1</span><span>Quantity: 1</span></p>
                <p class="d-flex justify-content-between"><span>Produto 2</span><span>Quantity: 4</span></p>
                <p class="d-flex justify-content-between"><span>Produto 3</span><span>Quantity: 6</span></p>
                <p class="d-flex justify-content-between"><span>Produto 4</span><span>Quantity: 3</span></p>
                <p class="d-flex justify-content-between"><span>Produto 5</span><span>Quantity: 5</span></p>
                <span class="mx-5 px-5"><span class="mx-3 px-2"></span></span>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="card">
              <div class="card-body mt-2">
                <h6 class="card-title text-center">YOU WILL EARN: 3,00 €</h6>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8 p-0">
              <router-link class="btn btn-primary" :class="{ active: $route.name === 'Orders' }"
                :to="{ name: 'Orders' }" @click="clickMenuOption">
                <i class="bi bi-backspace"></i>Back</router-link>
            </div>
            <div class="col-4 p-0 mt-2 align-right">
              Price: {{ order.total_price }} €
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.total_price {
  width: 26rem;
}
</style>
