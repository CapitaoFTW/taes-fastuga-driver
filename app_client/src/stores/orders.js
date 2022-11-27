import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import { useUserStore } from "./user.js"

export const useOrderStore = defineStore('orders', () => {
    const userStore = useUserStore()
    const socket = inject("socket")
    const axios = inject('axios')
    const toast = inject("toast")
    
    const orders = ref([])

    const totalOrders = computed(() => {
        return orders.value.length
    })

    const myInprogressOrders = computed(() => {        
        return orders.value.filter( ord => 
            ord.status == 'P' && prj.delivered_by == userStore.userId)
    })

    const totalMyInprogressOrders = computed(() => {        
        return myInprogressOrders.value.length
    })

    function getOrdersByFilter(delivered_by, status) {
        return orders.value.filter( ord =>
            (!delivered_by || delivered_by == ord.delivered_by) &&
            (!status || status == ord.status)
        )
    }
    
    function getOrdersByFilterTotal(delivered_by, status) {
        return getOrdersByFilter(delivered_by, status).length
    }

    function clearOrders() {
        orders.value = []
    }

    async function loadOrders() {
        try {
            const response = await axios.get('orders')
            orders.value = response.data.data
            return orders.value
        } catch (error) {
            clearOrders()
            throw error
        }
    }
    
    async function insertOrder(newOrder) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the insertOrder
        const response = await axios.post('orders', newOrder)
        orders.value.push(response.data.data)
        socket.emit('newOrder', response.data.data)
        return response.data.data
    }

    socket.on('newOrder', (order) => {
        orders.value.push(order)
        toast.success(`A new order was created (#${order.id} : ${order.name})`)
      })       

    function updateOrderOnArray(updateOrder) {
        let idx = orders.value.findIndex((t) => t.id === updateOrder.id)
        if (idx >= 0) {
            orders.value[idx] = updateOrder
        }
    }
    async function updateOrder(updateOrder) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the updateOrder
        const response = await axios.put('orders/' + updateOrder.id, updateOrder)
        updateOrderOnArray(response.data.data)
        socket.emit('updateOrder', response.data.data)
        return response.data.data
    }
    socket.on('updateOrdert', (order) => {
        updateOrderOnArray(order)
        toast.info(`The order (#${order.id} : ${order.ticket_number}) was updated!`)
    })       

    function deleteOrderOnArray(deleteOrder) {
        let idx = orders.value.findIndex((t) => t.id === deleteOrder.id)
        if (idx >= 0) {
            orders.value.splice(idx, 1)
        }
    }
    async function deleteOrder( deleteOrder) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the deleteOrder
        const response = await axios.delete('orders/' + deleteOrder.id)
        deleteOrderOnArray(response.data.data)
        socket.emit('deleteOrder', response.data.data)
        return response.data.data
    }   
    socket.on('deleteOrder', (order) => {
        deleteOrderOnArray(order)
        toast.info(`The order (#${order.id} : ${order.ticket_number}) was deleted!`)
    })       
    
    return { orders, totalOrders, myInprogressOrders, totalMyInprogressOrders, 
        getOrdersByFilter, getOrdersByFilterTotal, 
        loadOrders, clearOrders, insertOrder, updateOrder, deleteOrder}
})
