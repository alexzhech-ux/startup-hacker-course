<template>
  <div class="dialogOverlay" @click.self="onCancel">
    <transition name="dialog-fade-slide" appear>
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
    </transition>
  </div>
</template>
<script setup>
import { computed, useSlots } from 'vue'
const props = defineProps({
  title: {
    type: String,
    default: ''
  }
})
const emit = defineEmits(['cancel'])
const slots = useSlots()
const hasHeader = computed(() => !!props.title || !!slots.title)
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
    justify-content: center;
    align-items: center;

    z-index: 1000;
  }
  .dialogWindow {
    background-color: white;
    padding: 0 20px 20px 20px;
    border: 2px solid black;
    border-radius: 30px;
  }
  .dialog-fade-slide-enter-from,
  .dialog-fade-slide-appear-from {
    opacity: 0;
    transform: translateY(40px);
  }
  .dialog-fade-slide-enter-active,
  .dialog-fade-slide-appear-active {
    transition: opacity 300ms ease, transform 300ms ease;
  }
  .dialog-fade-slide-enter-to,
  .dialog-fade-slide-appear-to {
    opacity: 1;
    transform: translateY(0);
  }
  .dialog-fade-slide-leave-from {
    opacity: 1;
    transform: translateY(0);
  }
  .dialog-fade-slide-leave-to {
    opacity: 0;
    transform: translateY(40px);
  }
</style>