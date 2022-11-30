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
  <div class="container">
    <div class="row">
      <h3 class="mt-4 mb-3">{{ orderTitle }}</h3>
      <hr>
      <div class="mb-3 d-flex justify-content-start flex-wrap">
        <div class="mx-2 mt-2">
          <div class="row">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title mt-2">Products</h5>
                <p class="mb-5"></p>
                <p class="d-flex justify-content-between"><span>Alface</span><span>Quantity: 1</span></p>
                <p class="d-flex justify-content-between"><span>Espinafres</span><span>Quantity: 4</span></p>
                <p class="d-flex justify-content-between"><span>Pão</span><span>Quantity: 6</span></p>
                <p class="d-flex justify-content-between"><span>Coca-Cola</span><span>Quantity: 3</span></p>
                <p class="d-flex justify-content-between"><span>Alface</span><span>Quantity: 5</span></p>
                <span class="mx-5 px-5"><span class="mx-5 px-5"></span></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-between">
        <router-link class="btn btn-primary" :class="{ active: $route.name === 'Orders' }" :to="{ name: 'Orders' }"
          @click="clickMenuOption">
          <i class="bi bi-backspace"></i>Back</router-link>
        <div class="row mt-2">
          Price: {{ editingOrder.total_price }} € <span class="mx-5 px-5"></span>
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
