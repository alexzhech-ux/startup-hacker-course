import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../HomeView.vue'
import AboutUs from '../AboutUs.vue'

const routes = [
  { path: '/', component: HomeView },
  { path: '/aboutUs', component: AboutUs }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router