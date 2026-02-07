<script setup>
import { useForm, router } from '@inertiajs/vue3'

const form = useForm({
  username: '',
  password: '',
  password_confirmation: '',
})
</script>

<template>
  <div class="s-login">
    <SForm @submit.prevent="form.post('/registration', {
  onSuccess: () => emit('success'),
})">
      <SFormRow title="Логин">
        <SInput v-model="form.username" />
      </SFormRow>

      <SFormRow title="Пароль">
        <SInput type="password" v-model="form.password" />
      </SFormRow>

      <SFormRow title="Подтверждение пароля">
        <SInput type="password" v-model="form.password_confirmation" />
      </SFormRow>

      <SFormRow>
        <SButton type="button" @click="router.visit('/login')">Отмена</SButton>
        <SButton type="submit">Регистрация</SButton>
      </SFormRow>
    </SForm>
  </div>
</template>