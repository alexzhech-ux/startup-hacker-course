import './bootstrap'
import './font-awesome'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

import StartupUI from 'startup-ui'
import 'startup-ui/dist/index.css'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

const pages = import.meta.glob('./Pages/**/*.vue')

createInertiaApp({
  resolve: name => pages[`./Pages/${name}.vue`](),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(StartupUI)
      .mount(el)
  },
})