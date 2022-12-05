<script setup>
import { ref, computed, onMounted, inject } from 'vue'
import { useRouter } from 'vue-router'
import OrderTable from "./OrderTable.vue"

const router = useRouter()

const axios = inject("axios")
const toast = inject("toast")

const orders = ref([])
const users = ref([])
const filterByStatus = ref('P')

const loadOrders = () => {
  axios.get('orders')
    .then((response) => {
      orders.value = response.data.data
    })

    .catch((error) => {
      console.log(error)
    })
}

const loadUsers = () => {
  axios.get('users')
    .then((response) => {
      users.value = response.data.data
    })

    .catch((error) => {
      console.log(error)

    })
}

const showOrder = (order) => {
  router.push({ name: 'Order', params: { id: order.id } })
}

const acceptOrder = (order, user) => {
  if (order.accepted == 0)
    order.accepted = 1

  else
    order.accepted = 0

  axios.patch("orders/" + order.id + "/" + user.id + "/accepted", { accepted: order.accepted })
    .then((response) => {
      toast.success("Order #" + order.ticket_number + " was accepted")
      router.go(0)
    })

    .catch((error) => {
      console.log(error)
    })
}

const filteredOrders = computed(() => {
  return orders.value.filter(p => (!filterByStatus.value || filterByStatus.value == p.status))
})

const totalOrders = computed(() => {
  return orders.value
})

onMounted(() => {
  loadUsers()
  // Calling loadOrders refresh the list of orders from the API
  loadOrders()
})

</script>

<template>

  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Orders</h3>
    </div>
  </div>
  <hr>
  <order-table :orders="totalOrders" :showMine="false" :showDates="true" @show="showOrder" @accept="acceptOrder">
  </order-table>
  <hr>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-2">My Orders</h3>
    </div>
  </div>
  <hr>
  <div class="mb-3 d-flex justify-content-start flex-wrap">
    <div class="mx-2 mt-2 filter-div">
      <label for="selectStatus" class="form-label">Filter by status:</label>
      <select class="form-select" id="selectStatus" v-model="filterByStatus">
        <option :value="null" disabled>Choose an option</option>
        <option value="P">Preparing</option>
        <option value="R">Ready</option>
      </select>
    </div>
  </div>
  <order-table :orders="filteredOrders" :showMine="true" :showDates="true" @show="showOrder">
  </order-table>
</template>

<style scoped>
.filter-div {
  min-width: 12rem;
}

.total-filtro {
  margin-top: 0.35rem;
}

.btn-addprj {
  margin-top: 1.85rem;
}
</style>
