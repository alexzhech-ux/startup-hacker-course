<template>
  <SModal
    :open="modelOpen"
    @update:open="modelOpen = $event"
    size="sm"
  >
    <template #title>
      {{ mode === 'login' ? 'Вход' : 'Регистрация' }}
    </template>

    <form @submit.prevent="submit">
      <SInput
        v-if="mode === 'register'"
        v-model="form.name"
        label="Имя"
        required
      />

      <SInput
        v-model="form.email"
        label="Email"
        type="email"
        required
      />

      <SInput
        v-model="form.password"
        label="Пароль"
        type="password"
        required
      />

      <div class="actions">
        <SButton type="submit">
          {{ mode === 'login' ? 'Войтии' : 'Зарегистрироваться' }}
        </SButton>

        <SButton type="secondary" @click="modelOpen = false">
          Отмена
        </SButton>
      </div>
    </form>
  </SModal>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  open: Boolean,
  mode: {
    type: String,
    default: 'login',
  },
})

const emit = defineEmits(['update:open'])

const modelOpen = computed({
  get: () => props.open,
  set: (value) => emit('update:open', value),
})

const form = useForm({
  name: '',
  email: '',
  password: '',
})

watch(() => props.open, (isOpen) => {
  if (!isOpen) form.reset()
})

const submit = () => {
  const url = props.mode === 'login'
    ? '/login'
    : '/registration'

  form.post(url, {
    onSuccess: () => {
      modelOpen.value = false
    },
  })
}
</script>

<style scoped>
.actions {
  display: flex;
  gap: 12px;
  margin-top: 16px;
}
</style>