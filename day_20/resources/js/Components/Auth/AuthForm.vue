<template>
  <div class="auth-overlay">
    <div class="auth-box">
      <h2>{{ mode === 'login' ? 'Вход' : 'Регистрация' }}</h2>

      <SForm @submit.prevent="submit">
        <SFormRow title="Логин">
          <SInput v-model="form.username" />
        </SFormRow>

        <SFormRow title="Пароль">
          <SInput type="password" v-model="form.password" />
        </SFormRow>

        <SFormRow v-if="mode === 'register'" title="Подтверждение пароля">
          <SInput type="password" v-model="form.password_confirmation" />
        </SFormRow>

        <p v-if="form.errors.general" class="error">
            {{ form.errors.general }}
        </p>

        <p v-if="form.errors.username" class="error">
            {{ form.errors.username }}
        </p>

        <p v-if="form.errors.password" class="error">
            {{ form.errors.password }}
        </p>
        <div class="buttons">
          <SButton type="submit">
            {{ mode === 'login' ? 'Войти' : 'Зарегистрироваться' }}
          </SButton>

          <SButton type="button" theme="outline" @click="$emit('close')">
            Отмена
          </SButton>
        </div>

        <p class="switch">
          <span v-if="mode === 'login'">
            Нет аккаунта?
            <a @click.prevent="$emit('switch', 'register')">Регистрация</a>
          </span>

          <span v-else>
            Уже есть аккаунт?
            <a @click.prevent="$emit('switch', 'login')">Вход</a>
          </span>
        </p>
      </SForm>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { SForm, SFormRow, SInput, SButton } from 'startup-ui'

const emit = defineEmits(['close', 'switch'])

const props = defineProps({
  mode: {
    type: String,
    default: 'login',
  },
})

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
</script>

<style scoped>
.auth-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}

.auth-box {
  background: #fff;
  padding: 24px;
  border-radius: 16px;
  width: 400px;
}

.buttons {
  display: flex;
  gap: 12px;
  margin-top: 16px;
}

.switch {
  margin-top: 12px;
  text-align: center;
}

.switch a {
  cursor: pointer;
  text-decoration: underline;
}
</style>