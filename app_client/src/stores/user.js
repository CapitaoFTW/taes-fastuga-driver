import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import avatarNoneUrl from '@/assets/avatar-none.png'

export const useUserStore = defineStore('user', () => {
    const axios = inject('axios')
    const serverBaseUrl = inject('serverBaseUrl')
    
    const user = ref(null)
    const inProgressOrders = ref([])
    
    const userPhotoUrl = computed(() => {
        if (!user.value?.photo_url) {
            return avatarNoneUrl
        }
        return serverBaseUrl + '/storage/fotos/' + user.value.photo_url
    })
    
    const userId = computed(() => {
        return user.value?.id ?? -1
    })

    async function loadUser () {
        try {
            const response = await axios.get('users/me')
            user.value = response.data.data
        } catch (error) {
            clearUser() 
            throw error
        }
    }

    function clearUser () {
        delete axios.defaults.headers.common.Authorization
        sessionStorage.removeItem('token')
        user.value = null
    }  
    
    async function loadInProgressOrders () {
        try {
            const response = await axios.get('users/' + userId.value + '/orders/inprogress')
            inProgressOrders.value = response.data.data
        } 
        catch(error) {
            clearInProgressOrders()
            throw error
        }
    }

    function clearInProgressOrders () {
        inProgressOrders.value = []
    }

    async function login (credentials) {
        try {
            const response = await axios.post('login', credentials)
            axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token
            sessionStorage.setItem('token', response.data.access_token)
            await loadUser()
            await loadInProgressOrders()
            return false
        } 
        catch(error) {
            clearUser()
            clearInProgressOrders()
            return error
        }
    }

    async function register (credentials) {
        try {
            const response = await axios.post('register', credentials)
            axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token
            sessionStorage.setItem('token', response.data.access_token)
            await loadUser()
            await loadInProgressOrders()
            return false
        } 
        catch(error) {
            clearUser()
            clearInProgressOrders()
            return error
        }
    }
    
    async function logout () {
        try {
            await axios.post('logout')
            clearUser()
            clearInProgressOrders()
            return true

        } catch (error) {
            return false
        }
    }

    async function changePassword (passwords) {
        try {
            await axios.patch('users/' + userId.value + '/password', passwords)
            return false
        } 
        catch(error) {
            return error
        }
    }

    async function restoreToken () {
        let storedToken = sessionStorage.getItem('token')
        if (storedToken) {
            axios.defaults.headers.common.Authorization = "Bearer " + storedToken
            await loadUser()
            await loadInProgressOrders()
            return true
        }
        clearUser()
        return false
    }
    
    return { user, inProgressOrders, userId, userPhotoUrl, login, register, changePassword, logout, restoreToken }
})
