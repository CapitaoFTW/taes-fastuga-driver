<script setup>
const props = defineProps({
  orders: {
    type: Array,
    default: () => [],
  },
  showId: {
    type: Boolean,
    default: true,
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

const emit = defineEmits(['show'])

const showClick = (order) => {
  emit('show', order)
}
</script>

<template>
  <div class="mb-3 d-flex justify-content-start flex-wrap">
    <div class="mx-2 mt-2">
      <div class="row">
        <div class="col-sm-4" v-for="order in orders" :key="order.id">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Order #{{ order.ticket_number }}</h5>
              <p class="mb-5"></p>
              <p class="card-text">Distance: 53.40 km</p>
              <p class="mb-5"></p>
              <p class="card-text justify-content-end"><a class="btn btn-xl btn-primary" @click="showClick(order)"
                  v-if="showShowButton">Detalhes</a></p>
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
