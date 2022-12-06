<script setup>
import { ref, watch, computed, onMounted, inject } from 'vue'
import { useRouter, onBeforeRouteLeave } from 'vue-router'
import { useUserStore } from "../../stores/user.js"

import OrderDetail from "./OrderDetail.vue"

const router = useRouter()
const axios = inject('axios')
const toast = inject('toast')
const userStore = useUserStore()

const newOrder = () => {
  return {
    id: null,
    ticket_number: '',
    status: 'P',
    total_price: null,
    driver_id: null,
    accepted: 0,
    delivered: 0,
  }
}

const loadOrder = (id) => {
  let originalValueStr = ''
  errors.value = null

  if (!id || (id < 0)) {
    order.value = newOrder()
    originalValueStr = dataAsString()

  } else {
    axios.get('orders/' + id)
      .then((response) => {
        order.value = response.data.data
        originalValueStr = dataAsString()
      })

      .catch((error) => {
        console.log(error)
      })
  }
}

const dataAsString = () => {
  return JSON.stringify(order.value)
}

const props = defineProps({
  id: {
    type: Number,
    default: null
  }
})

const order = ref(newOrder())
const errors = ref(null)

watch(
  () => props.id,

  (newValue) => {

    loadOrder(newValue)
  }, {

  immediate: true,
}
)

const back = () => {
  router.push({ name: 'Orders' })
}

const acceptOrder = (userId) => {
  if (order.value.accepted == 0)
    order.value.accepted = 1

  else
    order.value.accepted = 0

  axios.patch("orders/" + order.value.id + "/" + userId + "/accepted")
    .then((response) => {
      toast.success("Order #" + order.value.ticket_number + " was accepted")
      loadOrder(order.value.id)
    })

    .catch((error) => {
      console.log(error)
    })
}

const claimOrder = () => {
  order.value.status = 'O'

  axios.patch("orders/" + order.value.id + "/status", { status: order.value.status })
    .then((response) => {
      toast.success("Order #" + order.value.ticket_number + " was claimed")
      loadOrder(order.value.id)
    })

    .catch((error) => {
      console.log(error)
    })
}

const completeOrder = (user) => {
  if (order.value.delivered == 0) {
    order.value.delivered = 1
    order.value.status = 'D'
  
  } else {
    order.value.delivered = 0
  }

  axios.patch("orders/" + order.value.id + "/" + user.id + "/delivered")
    .then((response) => {
      toast.success('Order #' + order.value.ticket_number + ' was delivered, ' + ((order.value.distance <= 3) ? (+ '2.00') : (order.value.distance <= 10 ? (+ '3.00') : (+ '4.00'))) + "€ sent to your account!")
      loadOrder(order.value.id)
      userStore.loadUser
    })

    .catch((error) => {
      console.log(error)
    })
}

const cancelOrder = () => {
  order.value.status = 'C'

  axios.patch("orders/" + order.value.id + "/status", { status: order.value.status })
    .then((response) => {
      toast.success("Order #" + order.value.ticket_number + " was cancelled")
      loadOrder(order.value.id)
    })

    .catch((error) => {
      console.log(error)
    })
}

onMounted(() => {
  loadOrder(props.id)
})
</script>

<template>
  <OrderDetail :order="order" :errors="errors" @back="back" @accept="acceptOrder" @claim="claimOrder"
    @complete="completeOrder" @cancel="cancelOrder">
  </OrderDetail>
</template>
