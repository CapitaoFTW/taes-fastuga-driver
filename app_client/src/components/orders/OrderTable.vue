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

const emit = defineEmits(['show', 'accept', 'claim', 'complete', 'cancel'])

const showClick = (order) => {
  emit('show', order)
}

const acceptClick = (order, userId) => {
  emit('accept', order, userId)
}

const claimClick = (order) => {
  emit('claim', order)
}

const completedClick = (order, user) => {
  emit('complete', order, user)
}

const cancelClick = (order) => {
  emit('cancel', order)
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
          <div class="card mb-3" style="min-width: 208px; min-height: 209px;">
            <div class="card-body">
              <h5 class="card-title">Order #{{ order.ticket_number }}</h5>
              <p class="mb-4"></p>
              <p class="card-text">Status: {{ order.status_name }}</p>
              <p class="mb-3"></p>
              <p class="card-text">Distance: {{ order.distance }} km</p>
              <p class="mb-4"></p>
              <p class="card-text text-center">
                <a class="btn btn-xl btn-success" @click="acceptClick(order, userStore.userId)"
                  v-if="((order.status == 'P' || order.status == 'R') && order.accepted == 0)">Accept</a>&nbsp;
                <a class="btn btn-xl btn-primary" @click="showClick(order)" v-if="showShowButton">Details</a>
              </p>
            </div>
          </div>
        </div>
        <div class="col-5" v-for="order in filteredMyOrders" :key="order.id">
          <div class="card mb-3" style="min-width: 208px; min-height: 209px;">
            <div class="card-body">
              <h5 class="card-title">Order #{{ order.ticket_number }}</h5>
              <p class="mb-4"></p>
              <p class="card-text">Status: {{ order.status_name }}</p>
              <p class="mb-3"></p>
              <p class="card-text">Distance: {{ order.distance }} km</p>
              <p class="mb-4"></p>
              <p class="card-text text-center">
                <a class="btn btn-success text-light" @click="claimClick(order)"
                  v-if="(order.status == 'R' && order.driver_id == userStore.userId)">Claim</a><span
                  v-if="(order.status == 'R' && order.driver_id == userStore.userId)">&nbsp;</span>
                <a class="btn btn-success text-light" @click="completedClick(order, userStore.user)"
                  v-if="(order.status == 'O' && order.driver_id == userStore.userId)">Complete</a><span
                  v-if="(order.status == 'O' && order.driver_id == userStore.userId)">&nbsp;</span>
                <a class="btn btn-danger" @click="cancelClick(order)"
                  v-if="(order.status == 'O' && order.driver_id == userStore.userId)">Cancel</a><span
                  v-if="(order.status == 'O' && order.driver_id == userStore.userId)">&nbsp;</span>
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
