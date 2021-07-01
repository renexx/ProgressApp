import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Dashboard from '../views/Dashboard.vue'
import Profile from '../views/Profile.vue'
import Register from '../views/Register.vue'
import LogHistory from '../views/LogHistory.vue'
import Activity from '../views/Activity.vue'
import Bodyparam from '../views/Bodyparam.vue'
import store from '@/store'
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/register',
    name: 'register',
    component: Register
  },
  {
    path: '/dashboard',
    name: 'allactivities',
    component: Dashboard,
    beforeEnter: (to, from, next) =>{
      
      if(! store.getters['auth/authenticated']){
        return next({
          name: 'login'
        })
      }
      next()
    }
  },
  {
    path: '/me',
    name: 'profile',
    component: Profile
  },
  {
    path: '/log-history',
    name: 'logHistory',
    component: LogHistory
  },
  {
    path: '/activity/:activityId',
    name: 'activity',
    component: Activity,
    props:true,
  },
  {
    path: '/bodyparam/:bodyparamId',
    name: 'bodyparam',
    component: Bodyparam,
    props: true,
  },
  {
    path: '*',
    redirect: '/'
    
  },
  
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
