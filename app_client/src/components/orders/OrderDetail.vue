<script setup>
import { ref, watch } from 'vue'
import { useUserStore } from "../../stores/user.js"

const userStore = useUserStore()

const props = defineProps({
  order: {
    type: Object,
    required: true
  },
  errors: {
    type: Object,
    required: false
  }
})

const order = ref(props.order)

watch(
  () => props.order,

  (newOrder) => {
    order.value = newOrder
  }
)

const emit = defineEmits(['back', 'accept', 'claim', 'complete', 'cancel'])

const backClick = () => {
  emit('back')
}

const acceptClick = (userId) => {
  emit('accept', userId)
}

const claimClick = () => {
  emit('claim')
}

const completedClick = (user) => {
  emit('complete', user)
}

const cancelClick = () => {
  emit('cancel')
}

</script>
<template>
  <div class="container">
    <div class="row">
      <h3 class="mt-4 mb-3">Order #{{ order.ticket_number }}</h3>
    </div>
    <hr>
    <div class="d-flex justify-content-start mb-3 mt-5">
      <h5>Quantity:</h5>&nbsp;
      <h5><b>{{ order.quantity }}</b></h5>
    </div>
    <div class="d-flex justify-content-start mb-3">
      <h5>Status:</h5>&nbsp;
      <h5><b>{{ order.status_name }}</b></h5>
    </div>
    <div class="d-flex justify-content-start mb-3">
      <h5>Distance:</h5>&nbsp;
      <h5><b>{{ order.distance }} km</b></h5>
    </div>
    <div class="d-flex justify-content-start mb-5">
      <h5>Price:</h5>&nbsp;
      <h5><b>{{ order.total_price }} €</b></h5>
    </div>
    <div class="d-flex justify-content-start mb-5">
      <h5>Your Earnings:</h5>&nbsp;
      <h5><b>{{ order.distance <= 3 ? '2.00' : order.distance <= 10 ? '3.00' : '4.00' }} €</b>
      </h5>
    </div>
    <div class="d-flex justify-content-center">
      <a class="btn btn-primary" @click="backClick">Back</a>&nbsp;
      <a class="btn btn-success" @click="acceptClick(userStore.userId)"
        v-if="(order.accepted == 0 && (order.status == 'P' || order.status == 'R'))">Accept</a>
      <a class="btn btn-success text-light" @click="claimClick"
        v-if="(order.status == 'R' && order.driver_id == userStore.userId)">Claim</a>
      <a class="btn btn-success text-light" @click="completedClick(userStore.user)"
        v-if="(order.status == 'O' && order.driver_id == userStore.userId)">Complete</a><span v-if="(order.status == 'O' && order.driver_id == userStore.userId)">&nbsp;</span>
      <a class="btn btn-danger" @click="cancelClick"
        v-if="(order.status == 'O' && order.driver_id == userStore.userId)">Cancel</a>
    </div>
  </div>
</template>

<style scoped>
.total_price {
  width: 26rem;
}
</style>
