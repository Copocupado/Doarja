/**
 * router/index.ts
 *
 * Automatic routes for `./src/pages/*.vue`
 */

// Composables
import { createRouter, createWebHistory } from 'vue-router/auto'
import { setupLayouts } from 'virtual:generated-layouts'
import { routes } from 'vue-router/auto-routes'
import { adminDAO } from '@/models/Admins/adminDAO'
import { entidadeDAO } from '@/models/Entidade/entidadeDAO'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: setupLayouts(routes),
})

// Workaround for https://github.com/vitejs/vite/issues/11804
router.onError((err, to) => {
  if (err?.message?.includes?.('Failed to fetch dynamically imported module')) {
    if (!localStorage.getItem('vuetify:dynamic-reload')) {
      console.log('Reloading page to fix dynamic import error')
      localStorage.setItem('vuetify:dynamic-reload', 'true')
      location.assign(to.fullPath)
    } else {
      console.error('Dynamic import error, reloading page did not fix it', err)
    }
  } else {
    console.error(err)
  }
})

router.beforeEach(async (to) => {
  if(to.name == '/Admin/login') {
    const response = await adminDAO.isItem()
    if (!response.success) {
      return
    }
    if(response.message){
      return {name: '/Admin/dashboard'}
    } else{
      return
    }
  }

  if(to.name == '/Admin/dashboard') {
    const response = await adminDAO.isItem()
    if (!response.success) {
      return {name: '/Admin/login'}
    }
    if(response.message){
      return
    } else{
      return {name: '/Admin/login'}
    }
  }

  if(to.name == '/Entidade/login') {
    const response = await entidadeDAO.isItem()
    if (!response.success) {
      return
    }
    if(response.message){
      return {name: '/Entidade/dashboard'}
    } else{
      return
    }
  }

  if(to.name == '/Entidade/dashboard') {
    const response = await entidadeDAO.isItem()
    if (!response.success) {
      return {name: '/Entidade/login'}
    }
    if(response.message){
      return
    } else{
      return {name: '/Entidade/login'}
    }
  }
});

router.isReady().then(() => {
  localStorage.removeItem('vuetify:dynamic-reload')
})

export default router
