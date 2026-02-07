import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import './font-awesome.js'
import 'startup-ui/dist/index.css'
import {
  SButton,
  SInput,
  SCheckbox,
  SSelect,
  SDialog,
  SForm,
  SFormRow
} from 'startup-ui'
import { library } from '@fortawesome/fontawesome-svg-core'
import {
  faStar,
  faPen,
  faTrash,
  faCheck
} from '@fortawesome/free-solid-svg-icons'

library.add(faStar, faPen, faTrash, faCheck)
const app = createApp(App)
app.use(router)
app.component('FontAwesomeIcon', FontAwesomeIcon)
app.component('SButton', SButton)
app.component('SInput', SInput)
app.component('SCheckbox', SCheckbox)
app.component('SSelect', SSelect)
app.component('SDialog', SDialog)
app.component('SForm', SForm)
app.component('SFormRow', SFormRow)
app.mount('#app')