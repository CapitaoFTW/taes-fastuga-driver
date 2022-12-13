<script setup>
import { useRouter, RouterLink, RouterView } from "vue-router"
import { ref, inject } from "vue"
import { useUserStore } from "./stores/user.js"

const router = useRouter()
const toast = inject("toast")
const userStore = useUserStore()

const buttonSidebarExpand = ref(null)

const logout = async () => {
  if (await userStore.logout()) {
    toast.success("User has logged out of the application.")

    router.push({ name: 'Login' })

  } else {
    toast.error("There was a problem logging out of the application!")
  }
}

const clickMenuOption = () => {
  if (window.getComputedStyle(buttonSidebarExpand.value).display !== "none") {
    buttonSidebarExpand.value.click()
  }
}
</script>

<template>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top flex-md-nowrap p-0">
    <div class="container-fluid px-1" style="padding-right: 0.8rem !important">
      <router-link v-if="!userStore.user" class="navbar-brand col-md-3 col-lg-2 me-0 px-3" :to="{ name: 'Orders' }"
        @click="clickMenuOption">
        <img src="@/assets/logoFasTuga.png" alt="" width="30" height="30" class="d-inline-block align-text-top" />
      </router-link>
      <div class="d-block d-md-none">
        <ul class="nav">
          <li class="nav-item dropdown" v-show="userStore.user">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button"
              data-bs-toggle="dropdown" aria-expanded="false" style="padding-left: 0px">
              <img :src="userStore.userPhotoUrl" class="rounded-circle z-depth-0 avatar-img" alt="avatar image" />
              <span class="avatar-text">{{ userStore.user?.name ?? "Anonymous" }}&nbsp; {{ userStore.user?.balance }}
                €</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-start" aria-labelledby="navbarDropdownMenuLink2">
              <li>
                <router-link class="dropdown-item"
                  :class="{ active: $route.name == 'User' && $route.params.id == userStore.userId }"
                  :to="{ name: 'User', params: { id: userStore.userId } }" @click="clickMenuOption">
                  <i class="bi bi-person-square"></i><span class="position-absolute"
                    style="top: 1.1rem; left:3rem">Profile</span>
                </router-link>
              </li>
              <li>
                <router-link class="dropdown-item" :class="{ active: $route.name === 'ChangePassword' }"
                  :to="{ name: 'ChangePassword' }" @click="clickMenuOption">
                  <i class="bi bi-key-fill"></i><span class="position-absolute" style="top: 3.6rem; left:3rem">Change
                    Pass</span>
                </router-link>
              </li>
              <hr class="dropdown-divider" />
              <li>
                <router-link class="dropdown-item" :class="{ active: $route.name === 'Orders' }"
                  :to="{ name: 'Orders' }" @click="clickMenuOption">
                  <i class="bi bi-receipt"></i><span class="position-absolute"
                    style="bottom: 7.02rem; left:3rem">Orders</span>
                </router-link>
              </li>
              <li>
                <router-link class="dropdown-item" :class="{ active: $route.name === 'Statistics' }"
                  :to="{ name: 'Statistics' }" @click="clickMenuOption">
                  <i class="bi bi-bar-chart-fill"></i><span class="position-absolute"
                    style="bottom: 4.55rem; left:3rem">Statistics</span>
                </router-link>
              </li>
              <hr class="dropdown-divider" />
              <li>
                <a class="dropdown-item" href="#" @click.prevent="logout">
                  <i class="bi bi-arrow-right"></i><span class="position-absolute"
                    style="bottom: 1.1rem; left:3rem">Logout</span>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="d-block d-md-none">
        <ul class="nav">
          <li class="nav-item dropdown" v-show="userStore.user">
            <a class="nav-link dropdown-toggle arrow" href="#" id="navbarDropdownMenuLink2" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-bell"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink2">
              <li>
                <a class="dropdown-item" @click="clickMenuOption">Sem notificações
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <button v-show="!userStore.user" id="buttonSidebarExpandId" ref="buttonSidebarExpand" class="navbar-toggler"
        type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item" v-show="!userStore.user">
            <router-link class="nav-link" :class="{ active: $route.name === 'Register' }" :to="{ name: 'Register' }"
              @click="clickMenuOption">
              <i class="bi bi-box-arrow-in-right"></i>
              Register
            </router-link>
          </li>
          <li class="nav-item" v-show="!userStore.user">
            <router-link class="nav-link" :class="{ active: $route.name === 'Login' }" :to="{ name: 'Login' }"
              @click="clickMenuOption">
              <i class="bi bi-box-arrow-in-right"></i>
              Login
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3">
          <div class="d-block d-md-none">
            <ul class="nav flex-column mb-2">
              <li class="nav-item" v-show="!userStore.user">
                <router-link class="nav-link" :class="{ active: $route.name === 'Register' }" :to="{ name: 'Register' }"
                  @click="clickMenuOption">
                  <i class="bi bi-person-check-fill"></i>
                  Register
                </router-link>
              </li>
              <li class="nav-item" v-show="!userStore.user">
                <router-link class="nav-link" :class="{ active: $route.name === 'Login' }" :to="{ name: 'Login' }"
                  @click="clickMenuOption">
                  <i class="bi bi-box-arrow-in-right"></i>
                  Login
                </router-link>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<style>
@import "./assets/dashboard.css";

.avatar-img {
  margin: -1.2rem 0.8rem -2rem 0.8rem;
  width: 3.3rem;
  height: 3.3rem;
}

.avatar-text {
  line-height: 2.2rem;
  margin: 1rem 0.5rem -2rem 0;
  padding-top: 1rem;
}

.dropdown-item {
  font-size: 0.875rem;
}

.btn:focus {
  outline: none;
  box-shadow: none;
}

ul.nav {
  --bs-nav-link-padding-x: 0rem;
}

ul.nav.flex-column {
  --bs-nav-link-padding-x: 1rem;
}

.nav-link.dropdown-toggle {
  color: rgba(255, 255, 255, 0.55) !important;
}

.nav-link.dropdown-toggle:hover {
  color: rgba(255, 255, 255, 0.75) !important;
}

.nav-link.dropdown-toggle:disabled {
  color: rgba(255, 255, 255, 0.25) !important;
}

#sidebarMenu {
  overflow-y: auto;
}

.dropdown-toggle.arrow::after {
  content: none;
}
</style>