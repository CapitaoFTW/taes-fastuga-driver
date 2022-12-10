<script setup>
import { ref, computed, onMounted, inject } from 'vue'
import { useRouter } from 'vue-router'
import OrderTable from "./OrderTable.vue"

const router = useRouter()

const axios = inject("axios")
const toast = inject("toast")

const orders = ref([])

const loadOrders = () => {
  axios.get('orders')
    .then((response) => {
      orders.value = response.data.data
    })

    .catch((error) => {
      console.log(error)
    })
}

const showOrder = (order) => {
  router.push({ name: 'Order', params: { id: order.id } })
}

const acceptOrder = (order, userId) => {
  if (order.accepted == 0)
    order.accepted = 1

  else
    order.accepted = 0

  axios.patch("orders/" + order.id + "/" + userId + "/accepted")
    .then((response) => {
      toast.success("Order #" + order.ticket_number + " was accepted")
      loadOrders()
    })

    .catch((error) => {
      console.log(error)
    })
}

const claimOrder = (order) => {
  order.status = 'O'

  axios.patch("orders/" + order.id + "/status", { status: order.status })
    .then((response) => {
      toast.success("Order #" + order.ticket_number + " was claimed")
      loadOrders()
    })

    .catch((error) => {
      console.log(error)
    })
}

const completeOrder = (order, user) => {
  if (order.delivered == 0) {
    order.delivered = 1
    order.status = 'D'

  } else {
    order.delivered = 0
  }

  axios.patch("orders/" + order.id + "/" + user.id + "/delivered")
    .then((response) => {
      toast.success('Order #' + order.ticket_number + ' was delivered, ' + ((order.distance <= 3) ? (+ '2.00') : (order.distance <= 10 ? (+ '3.00') : (+ '4.00'))) + "€ sent to your account!")
      loadOrders()
    })

    .catch((error) => {
      console.log(error)
    })
}

const cancelOrder = (order) => {
  order.status = 'C'

  axios.patch("orders/" + order.id + "/status", { status: order.status })
    .then((response) => {
      toast.success("Order #" + order.ticket_number + " was cancelled")
      loadOrders()
    })

    .catch((error) => {
      console.log(error)
    })
}

const filteredOrders = computed(() => {
  return orders.value.filter(o => ('P' == o.status || 'R' == o.status))
})

const totalOrders = computed(() => {
  return filteredOrders.value.length
})

const filteredMyOrders = computed(() => {
  return orders.value.filter(o => ('P' == o.status || 'R' == o.status || 'O' == o.status))
})

const totalMyOrders = computed(() => {
  return filteredMyOrders.value.length
})

onMounted(() => {
  // Calling loadOrders refresh the list of orders from the API
  loadOrders()
})

</script>

<template>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Orders</h3>
    </div>
    <div class="mx-2 total-filtro">
      <h5 class="mt-4">Total: {{ totalOrders }}</h5>
    </div>
  </div>
  <hr>
  <order-table :orders="filteredOrders" :showMine="false" @show="showOrder" @accept="acceptOrder" @claim="claimOrder"
    @complete="completeOrder" @cancel="cancelOrder">
  </order-table>
  <hr>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-2">My Orders</h3>
    </div>
    <!--<div class="mx-2 total-filtro">
      <h5 class="mt-4">Total: {{ totalMyOrders }}</h5>
    </div>-->
  </div>
  <hr>
  <order-table :orders="filteredMyOrders" :showMine="true" @show="showOrder" @claim="claimOrder" @complete="completeOrder"
    @cancel="cancelOrder">
  </order-table>
</template>

<style scoped>

.total-filtro {
  margin-top: 0.35rem;
}

.btn-addprj {
  margin-top: 1.85rem;
}
</style>
