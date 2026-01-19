<template>
  <div class="timesegment">
    <div class="timesegment-datawrapper">
      <div class="timesegment-number-wrapper">
        <transition name="slide" mode="out-in">
          <span class="number" :key="value">
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
    import { computed } from "vue";
    const props = defineProps({
    value: {
        type: Number,
        required: true
    },
    label: {
        type: String,
        required: true
    }
    });
    const formattedValue = computed(() =>
    String(props.value).padStart(2, "0")
    );
    const declinedLabel = computed(() => {
    const forms = {
        день: ["день", "дня", "дней"],
        час: ["час", "часа", "часов"],
        минута: ["минута", "минуты", "минут"],
        секунда: ["секунда", "секунды", "секунд"]
    };
    const n = Math.abs(props.value) % 100;
    const n1 = n % 10;

    if (n > 10 && n < 20) return forms[props.label][2];
    if (n1 === 1) return forms[props.label][0];
    if (n1 > 1 && n1 < 5) return forms[props.label][1];
    return forms[props.label][2];
    });
</script>

<style scoped lang="scss">
    .timesegment {
        &-datawrapper {
            display: flex;
            flex-direction: column;
            gap: 5px;
            align-items: center;
        }

        &-number-wrapper {
            background-color: aliceblue;
            padding: 5px;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 70px;
            height: 50px;
            overflow: hidden;
        }

        .number {
            font-size: 32px;
            display: inline-block;
        }

        .label {
            font-size: 16px;
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