import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import avatarNoneUrl from '@/assets/avatar-none.png'

export const useUserStore = defineStore('user', () => {
    const axios = inject('axios')
    const serverBaseUrl = inject('serverBaseUrl')
    
    const user = ref(null)
    const inProgressProjects = ref([])
    
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
            clearUser () 
            throw error
        }
    }

    function clearUser () {
        delete axios.defaults.headers.common.Authorization
        sessionStorage.removeItem('token')
        user.value = null
    }  
    
    async function loadInProgressProjects () {
        try {
            const response = await axios.get('users/' + userId.value + '/projects/inprogress')
            inProgressProjects.value = response.data.data
        } 
        catch(error) {
            clearInProgressProjects()
            throw error
        }
    }

    function clearInProgressProjects () {
        inProgressProjects.value = []
    }

    async function login (credentials) {
        try {
            const response = await axios.post('login', credentials)
            axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token
            sessionStorage.setItem('token', response.data.access_token)
            await loadUser()
            return true       
        } 
        catch(error) {
            clearUser()
            return false
        }
    }

    async function register (credentials) {
        try {
            await axios.post('register', credentials)
            return true       
        } 
        catch(error) {
            return false
        }
    }
    
    async function logout () {
        try {
            await axios.post('logout')
            clearUser()
            return true
        } catch (error) {
            return false
        }
    }

    async function restoreToken () {
        let storedToken = sessionStorage.getItem('token')
        if (storedToken) {
            axios.defaults.headers.common.Authorization = "Bearer " + storedToken
            await loadUser()
            return true
        }
        clearUser()
        return false
    }
    
    return { user, inProgressProjects, userId, userPhotoUrl, login, register, logout, restoreToken }
})
