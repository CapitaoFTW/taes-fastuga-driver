<script setup>
import { computed } from "@vue/reactivity"
import { routerKey } from "vue-router";
import { useUserStore } from "../../stores/user.js"

const userStore = useUserStore()

const props = defineProps({
  orders: {
    type: Array,
    default: () => [],
  },
  showMine: {
    type: Boolean,
    default: false,
  },
  showPrice: {
    type: Boolean,
    default: true,
  },
  showStatus: {
    type: Boolean,
    default: true,
  },
  showShowButton: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(['show', 'accept'])

const showClick = (order) => {
  emit('show', order)
}

const acceptClick = (order, user) => {
  emit('accept', order, user)
}

const filteredOrders = computed(() => {
  return props.orders.filter(item => item.accepted == 0 && !props.showMine).splice(0, 10)
})

const filteredMyOrders = computed(() => {
  return props.orders.filter(item => item.driver_id == userStore.userId && props.showMine)
})
</script>

<template>
  <div class="mb-3 d-flex justify-content-between flex-wrap">
    <div class="mx-2 mt-2">
      <div class="row">
        <div class="col-5" v-for="order in filteredOrders" :key="order.id">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Order #{{ order.ticket_number }}</h5>
              <p class="mb-3"></p>
              <p class="card-text">Distance: {{ order.distance }} km</p>
              <p class="mb-3"></p>
              <p class="d-flex justify-content-end"><a class="btn btn-xl btn-success"
                  @click="acceptClick(order, userStore.user)"
                  v-if="((order.status == 'P' || order.status == 'R') && order.accepted == 0)">Accept</a>&nbsp;<a
                  class="btn btn-xl btn-primary" @click="showClick(order)" v-if="showShowButton">Details</a></p>
            </div>
          </div>
        </div>
        <div class="col-5" v-for="order in filteredMyOrders" :key="order.id">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Order #{{ order.ticket_number }}</h5>
              <p class="mb-3"></p>
              <p class="card-text">Distance: {{ order.distance }} km</p>
              <p class="mb-3"></p>
              <p class="d-flex justify-content-end">
                <a class="btn btn-xl btn-primary" @click="showClick(order)" v-if="showShowButton">Details</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
button {
  margin-left: 3px;
  margin-right: 3px;
}
</style>
