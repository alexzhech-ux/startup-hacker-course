<script setup>
import { useForm, router } from '@inertiajs/vue3'

const form = useForm({
  username: '',
  password: '',
})
</script>

<template>
  <div class="s-login">
    <SForm @submit.prevent="form.post('/login')">
      <SFormRow title="Логин">
        <SInput v-model="form.username" />
      </SFormRow>

      <SFormRow title="Пароль">
        <SInput type="password" v-model="form.password" />
      </SFormRow>

      <SFormRow>
        <SButton type="submit">Войти</SButton>
        <SButton type="button" @click="router.visit('/')">Отмена</SButton>
        <SButton type="button" @click="router.visit('/registration')">
          Регистрация
        </SButton>
      </SFormRow>
    </SForm>
  </div>
</template>
