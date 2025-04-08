import Vue from 'vue'
import VueRouter from 'vue-router'
import Folders from '../views/Folders';

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'folder_management',
    component: Folders
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
