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
  showDriver: {
    type: Boolean,
    default: true,
  },
  showTicket: {
    type: Boolean,
    default: true,
  },
  showPrice: {
    type: Boolean,
    default: true,
  },
  showEditButton: {
    type: Boolean,
    default: true,
  },
  showDeleteButton: {
    type: Boolean,
    default: true,
  }
})

const emit = defineEmits(['edit', 'delete'])

const editClick = (order) => {
  emit('edit', order)
}

const deleteClick = (order) => {
  emit('delete', order)
}
</script>

<template>
  <table class="table">
    <thead>
      <tr>
        <th v-if="showTicket">Ticket</th>
        <th>Status</th>
        <th v-if="showDriver">Driver</th>
        <th v-if="showPrice">Total Price</th>
        <th v-if="showEditButton || showDeleteButton"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="order in orders" :key="order.id">
        <td v-if="showTicket">{{ order.ticket_number }}</td>
        <td>{{ order.status_name }}</td>
        <td v-if="showDriver">{{ order.driver }}</td>
        <td v-if="showPrice">{{ order.total_price }} €</td>
        <td class="text-end" v-if="showEditButton || showDeleteButton">
          <div class="d-flex justify-content-end">
            <button class="btn btn-xs btn-light" @click="editClick(order)" v-if="showEditButton"><i
                class="bi bi-xs bi-pencil"></i>
            </button>

            <button class="btn btn-xs btn-light" @click="deleteClick(order)" v-if="showDeleteButton"><i
                class="bi bi-xs bi-x-square-fill"></i>
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>
button {
  margin-left: 3px;
  margin-right: 3px;
}
</style>
