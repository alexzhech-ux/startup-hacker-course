<template>
  <div class="timesegment">
    <div class="timesegment-datawrapper">
      <div class="timesegment-number-wrapper">
        <transition name="slide" mode="out-in">
          <span class="number" :key="formattedValue">
            {{ formattedValue }}
          </span>
        </transition>
      </div>

      <span class="label">
        {{ declinedLabel }}
      </span>
    </div>
  </div>
</template>

<script setup>
  import { computed } from 'vue'

  const props = defineProps({
      value: {
          type: Number,
          required: true
      },
      label: {
          type: String,
          required: true
      }
  })

  const formattedValue = computed(() =>
  String(props.value).padStart(2, '0')
  )

  const declinedLabel = computed(() => {
      const forms = {
          день: ['день', 'дня', 'дней'],
          час: ['час', 'часа', 'часов'],
          минута: ['минута', 'минуты', 'минут'],
          секунда: ['секунда', 'секунды', 'секунд']
      }

      const wordForms = forms[props.label]
      if (!wordForms) return props.label

      const n = Math.abs(props.value) % 100
      const n1 = n % 10

      if (n > 10 && n < 20) return wordForms[2]
      if (n1 === 1) return wordForms[0]
      if (n1 > 1 && n1 < 5) return wordForms[1]
      return wordForms[2]
  })
</script>

<style scoped lang="scss">
  .timesegment {
    &-datawrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
    }

    &-number-wrapper {
      width: 70px;
      height: 50px;
      background: #1a1a1a;
      border: 1px solid #2a2a2a;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .number {
      font-size: 32px;
      font-weight: 700;
      color: #fff;
    }

    .label {
      font-size: 14px;
      opacity: 0.7;
    }
  }

  .slide-enter-active,
  .slide-leave-active {
    transition: all 0.25s ease;
  }

  .slide-enter-from {
    transform: translateY(30%);
    opacity: 0;
  }

  .slide-leave-to {
    transform: translateY(-30%);
    opacity: 0;
  }
</style>
