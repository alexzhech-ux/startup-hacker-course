<template>
  <div class="auth-box">
    <h2>{{ mode === 'login' ? 'Вход' : 'Регистрация' }}</h2>

    <form @submit.prevent="submit">
      <input v-model="form.username" placeholder="Логин" />

      <p v-if="form.errors.username" class="error">
        {{ form.errors.username }}
      </p>

      <input v-model="form.password" type="password" placeholder="Пароль" />

      <p v-if="form.errors.password" class="error">
        {{ form.errors.password }}
      </p>

      <input
        v-if="mode === 'register'"
        v-model="form.password_confirmation"
        type="password"
        placeholder="Повтор пароля"
      />

      <button type="submit">
        {{ mode === 'login' ? 'Войти' : 'Зарегистрироваться' }}
      </button>

      <button type="button" @click="toggle">
        {{ mode === 'login' ? 'Регистрация' : 'Вход' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  mode: String,
})

const emit = defineEmits(['switch', 'close'])

const form = useForm({
  username: '',
  password: '',
  password_confirmation: '',
})

function submit() {
  const url = props.mode === 'login'
    ? '/login'
    : '/registration'

  form.post(url, {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      emit('close')
    },
  })
}

function toggle() {
  emit('switch', props.mode === 'login' ? 'register' : 'login')
}
</script>