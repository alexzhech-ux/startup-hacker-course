<template>
  <div v-if="visible"class="dialogOverlay" @click.self="onCancel">
    <div class="dialogWindow">
      <header class="dialogHeader" v-if="hasHeader">
            <slot name="title">
                <h2 class="dialogTitle">{{ title }}</h2>
            </slot>
      </header>
      <section class="dialogBody">
        <slot />
      </section>
    </div>
  </div>
</template>

<script setup>
    import { computed, useSlots } from 'vue'
    const slots = useSlots()
    const hasHeader = computed(() => !!props.title || !!slots.title)
    const props = defineProps({
    visible: {
        type: Boolean,
        required: true
    },
    title: {
        type: String,
        default: ''
    }
    })
    const emit = defineEmits(['cancel'])
    const onCancel = () => {
    emit('cancel')
    }
</script>