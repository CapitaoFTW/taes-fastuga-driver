<script setup>
import { ref, watch, computed } from 'vue'
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

const src = computed(() => {
  return "https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d196208.44058502177!2d-9.047226035076093!3d39.79001099953573!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e6!4m4!2s39.735356%20-8.821473!3m2!1d39.735356!2d-8.821473!4m4!2s" + order.value.latitude + "%20" + order.value.longitude + "!3m2!1d" + order.value.latitude + "!2d" + order.value.longitude + "!5e0!3m2!1spt-PT!2spt!4v1670546580255!5m2!1spt-PT!2spt"
})

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
    <iframe class="mb-4" :src="src" width="480" height="450" style="border:0;" allowfullscreen="" loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
    <div class="d-flex justify-content-center mb-4 pb-2">
      <a class="btn btn-primary" @click="backClick">Back</a>&nbsp;
      <a class="btn btn-success" @click="acceptClick(userStore.userId)"
        v-if="(order.accepted == 0 && (order.status == 'P' || order.status == 'R'))">Accept</a>
      <a class="btn btn-success text-light" @click="claimClick"
        v-if="(order.status == 'R' && order.driver_id == userStore.userId)">Claim</a>
      <a class="btn btn-success text-light" @click="completedClick(userStore.user)"
        v-if="(order.status == 'O' && order.driver_id == userStore.userId)">Complete</a><span
        v-if="(order.status == 'O' && order.driver_id == userStore.userId)">&nbsp;</span>
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
