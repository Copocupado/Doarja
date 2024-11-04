/**
 * main.ts
 *
 * Bootstraps Vuetify and other plugins then mounts the App
 */

// Plugins
import { registerPlugins } from '@/plugins'
import { VueMaskDirective } from 'v-mask'

// Components
import App from './App.vue'

// Composables
import { createApp } from 'vue'

const app = createApp(App)
app.directive('mask', VueMaskDirective)
registerPlugins(app)

app.mount('#app')
