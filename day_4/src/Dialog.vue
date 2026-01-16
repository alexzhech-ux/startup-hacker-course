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

<style scoped lang="scss">
.dialogOverlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.dialogWindow {
  background: #fff;
  border-radius: 8px;
  min-width: 400px;
  max-width: 90%;
}

.dialogHeader {
  padding: 16px;
  border-bottom: 1px solid #ddd;
}

.dialogBody {
  padding: 16px;
}

.dialogTitle {
  margin: 0;
}
</style>