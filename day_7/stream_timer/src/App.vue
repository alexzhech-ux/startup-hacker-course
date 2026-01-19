<template>
  <div class="app-wrapper">
    <div class="timerbox">
      <TimerHeader title="Будем в эфире через" />
      <div class="timerbox-display">
        <template v-if="!isLive">
          <TimerSegment label="день" :value="time.days" />
          <TimerSegment label="час" :value="time.hours" />
          <TimerSegment label="минута" :value="time.minutes" />
          <TimerSegment label="секунда" :value="time.seconds" />
        </template>
        <div v-else class="live-label">
          В эфире
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
    import { ref, onMounted, onUnmounted } from "vue";
    import TimerHeader from "@/components/TimerHeader.vue";
    import TimerSegment from "@/components/TimerSegment.vue";
    const TARGET_DATE = new Date("2026-02-01T15:00:00+03:00");
    const time = ref({
    days: 0,
    hours: 0,
    minutes: 0,
    seconds: 0
    });
    const isLive = ref(false);
    let timerId;

    function updateTimer() {
    const diff = TARGET_DATE - new Date();
    if (diff <= 0) {
        isLive.value = true;
        clearInterval(timerId);
        return;
    }
    const totalSeconds = Math.floor(diff / 1000);
    time.value.days = Math.floor(totalSeconds / 86400);
    time.value.hours = Math.floor((totalSeconds % 86400) / 3600);
    time.value.minutes = Math.floor((totalSeconds % 3600) / 60);
    time.value.seconds = totalSeconds % 60;
    }

    onMounted(() => {
    updateTimer();
    timerId = setInterval(updateTimer, 1000);
    });

    onUnmounted(() => clearInterval(timerId));
</script>

<style scoped lang="scss">
    .app-wrapper {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .timerbox {
        display: flex;
        flex-direction: column;
        gap: 16px;
        padding: 20px;
        border: 1px solid #3a3a3a;
        border-radius: 6px;
        box-shadow: 3px 3px 6px black;

        &-display {
            display: flex;
            justify-content: center;
            gap: 12px;
        }
    }

    .live-label {
        font-size: 36px;
        font-weight: bold;
        padding: 10px 30px;
        border-radius: 8px;
        background: #ff2d2d;
        color: white;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.85; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>