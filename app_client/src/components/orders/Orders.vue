<script setup>
  import { ref, computed, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import OrderTable from "./OrderTable.vue"
  import { useProjectsStore } from "../../stores/orders.js"

  const router = useRouter()

  const axios = inject("axios")
  const toast = inject("toast")
  const ordersStore = useOrdersStore()

  const OrderToDelete = ref(null)
  const users = ref([])
  const filterByResponsibleId = ref(null)
  const filterByStatus = ref('P')
  const deleteConfirmationDialog = ref(null)  
  
  /* Change this function */
  const loadOrders = () => {
    ordersStore.loadOrders()
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

  const addOrder = () => {
    router.push({ name: 'NewOrder'})
  }
  
  const editOrder = (order) => {
    router.push({ name: 'Order', params: { id: order.id } })
  }

  /* Change this function */
  const deleteOrderConfirmed = () => {
    ordersStore.deleteOrder(orderToDelete.value)
      .then((deletedOrder) => {
        toast.info("Order " + orderToDeleteDescription.value + " was deleted")
      })
      .catch(() => {
        toast.error("It was not possible to delete Order " + orderToDeleteDescription.value + "!")
      })
  }

  const clickToDeleteOrder = (order) => {
    orderToDelete.value = order
    deleteConfirmationDialog.value.show()
  }


  /* Change this function */
  const filteredOrders = computed(()=>{
    return orderStore.getOrdersByFilter(filterByResponsibleId.value, filterByStatus.value)
  })

  /* Change this function */
  const totalOrders = computed(()=>{
    return ordersStore.getOrdersByFilterTotal(filterByResponsibleId.value, filterByStatus.value)
  })

  const orderToDeleteDescription = computed(() => {
    return orderToDelete.value
    ? `#${orderToDelete.value.id} (${orderToDelete.value.name})`
    : ""
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
    <div class="mx-2 total-filtro">
      <h5 class="mt-4">Total: {{ totalOrders }}</h5>
    </div>
  </div>
  <hr>
  <div class="mb-3 d-flex justify-content-between flex-wrap">
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectStatus"
        class="form-label"
      >Filter by status:</label>
      <select
        class="form-select"
        id="selectStatus"
        v-model="filterByStatus"
      >
        <option :value="null"></option>
        <option value="P">Preparing</option>
        <option value="R">Ready</option>
        <option value="C">Cancelled</option>
        <option value="D">Delivered</option>
      </select>
    </div>
    
    <div class="mx-2 mt-2">
      <button
        type="button"
        class="btn btn-success px-4 btn-addprj"
        @click="addOrder"
      ><i class="bi bi-xs bi-plus-circle"></i>&nbsp; Add Order</button>
    </div>
  </div>
  <order-table
    :orders="filteredOrders"
    :showId="true"
    :showDates="true"
    @edit="editOrder"
    @delete="clickToDeleteOrder"
  ></order-table>
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
