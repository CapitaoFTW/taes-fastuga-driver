import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from "../stores/user.js"

import HomeView from '../views/HomeView.vue'

import Dashboard from "../components/Dashboard.vue"
import Login from "../components/auth/Login.vue"
import Register from "../components/auth/Register.vue"
import ChangePassword from "../components/auth/ChangePassword.vue"
import Orders from "../components/orders/Orders.vue"
import Order from "../components/orders/Order.vue"
import Users from "../components/users/Users.vue"
import User from "../components/users/User.vue"
import RouteRedirector from "../components/global/RouteRedirector.vue"

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Dashboard',
      component: Dashboard
    },
    {
      path: '/redirect/:redirectTo',
      name: 'Redirect',
      component: RouteRedirector,
      props: route => ({ redirectTo: route.params.redirectTo })
    },
    {
      path: '/register',
      name: 'Register',
      component: Register
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/password',
      name: 'ChangePassword',
      component: ChangePassword
    },
    {
      path: '/orders',
      name: 'Orders',
      component: Orders,
    },
    {
      path: '/orders/new',
      name: 'NewOrder',
      component: Order,
      props: { id: -1 }
    },
    {
      path: '/orders/:id',
      name: 'Order',
      component: Order,
      props: route => ({ id: parseInt(route.params.id) })
    },
    {
      path: '/users',
      name: 'Users',
      component: Users,
    },
    {
      path: '/users/:id',
      name: 'User',
      component: User,
      //props: true
      // Replaced with the following line to ensure that id is a number
      props: route => ({ id: parseInt(route.params.id) })
    },
    {
      path: '/reports',
      name: 'Reports',
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    }
  ]
})

let handlingFirstRoute = true

router.beforeEach((to, from, next) => {
  if (handlingFirstRoute) {
    handlingFirstRoute = false

    next({ name: 'Redirect', params: { redirectTo: to.fullPath } })
    return

  } else if (to.name == 'Redirect') {
    next()
    return
  }

  const userStore = useUserStore()
  
  if (to.name == 'Dashboard') {
    next({ name: 'Orders' })
    return
  }

  if (userStore.user && (to.name == 'Register' || to.name == 'Login')) {
    next({ name: 'Orders' })
    return
  }

  if ((to.name == 'Register') || (to.name == 'Login')) {
    next()
    return
  }

  if (!userStore.user) {
    next({ name: 'Login' })
    return
  }

  if (to.name == 'Reports') {
    if (userStore.user.type != 'D') {
      next({ name: 'Orders' })
      return
    }
  }

  if (to.name != 'Register' && to.name != 'Login' && to.name != 'ChangePassword' && to.name != 'Orders' && to.name != 'NewOrder'
  && to.name != 'Order' && to.name != 'Users' && to.name != 'User' && to.name != 'Reports' && to.name != 'about') {
    next({ name: 'Orders' })
    return
  }

  if (to.name == 'User') {
    if ((userStore.user.type == 'D') || (userStore.user.id == to.params.id)) {
      next()
      return
    }

    next({ name: 'Orders' })
    return
  }

  next()
})

export default router
