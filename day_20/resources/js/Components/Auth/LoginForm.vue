<template>
  <SForm @submit.prevent="submit">
    <SFormRow title="Логин">
      <SInput v-model="form.username" />
    </SFormRow>

    <SFormRow title="Пароль">
      <SInput type="password" v-model="form.password" />
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

    <SFormRow>
      <SButton type="submit" :loading="form.processing">
        Войти
      </SButton>
    </SFormRow>
  </SForm>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { SForm, SFormRow, SInput, SButton } from 'startup-ui'

const emit = defineEmits(['success'])

const form = useForm({
  username: '',
  password: '',
})

const submit = () => {
  form.post('/login', {
    preserveScroll: true,
    onSuccess: () => {
      emit('success')
    },
  })
}
</script>