<template>
  <div>
    <h1>Панель управления</h1>

    <form @submit.prevent="submit">
      <div>
        <label>
          Имя:
          <input v-model="form.name" type="text">
        </label>
      </div>

      <div>
        <label>
          Email:
          <input v-model="form.email" type="email">
        </label>
      </div>

      <button type="submit">Отправить</button>
    </form>

    <hr>

    <div v-if="submitted">
      <h3>Отправленные данные:</h3>
      <p>Имя: {{ form.name }}</p>
      <p>Email: {{ form.email }}</p>
    </div>
  </div>
</template>

<script setup>
import { defineOptions, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppLayout from './App.vue'

defineOptions({
  layout: AppLayout,
})

const submitted = ref(false)

const form = useForm({
  name: '',
  email: '',
})

const submit = () => {
  form.post('/dashboard', {
    preserveState: true,
    onSuccess: () => {
      submitted.value = true
    },
  })
}
</script>