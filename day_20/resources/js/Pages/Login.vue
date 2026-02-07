<template>
  <div class="login-page">
    <h1>Вход</h1>

    <form @submit.prevent="submit">
      <input v-model="form.username" placeholder="Username">
      <input v-model="form.password" type="password" placeholder="Password">

      <button type="submit">Войти</button>
    </form>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const form = reactive({
  username: '',
  password: '',
})

const submit = () => {
  router.post('/login', form)
}
</script>