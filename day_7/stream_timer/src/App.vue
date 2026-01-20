<template>
  <div class="app-wrapper">
    <div class="launch-card">
      <TimerHeader
        :title="isLive ? UI_TEXT.liveTitle : UI_TEXT.prelaunchTitle"
      />
      <div class="launch-countdown">
        <template v-if="!isLive">
          <TimerSegment label="день" :value="time.days" />
          <TimerSegment label="час" :value="time.hours" />
          <TimerSegment label="минута" :value="time.minutes" />
          <TimerSegment label="секунда" :value="time.seconds" />
        </template>
        <div v-else class="launch-status live">
          Мы в эфире
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted, onUnmounted } from 'vue'
  import TimerHeader from '@/components/TimerHeader.vue'
  import TimerSegment from '@/components/TimerSegment.vue'
  const TARGET_DATE = new Date('2026-02-01T15:00:00+03:00')
  const UI_TEXT = {
    prelaunchTitle: 'Запуск через',
  }
  const time = ref({
    days: 0,
    hours: 0,
    minutes: 0,
    seconds: 0
  })
  const isLive = ref(false)
  let timerId

  function updateTimer() {
    const diff = TARGET_DATE - new Date()

    if (diff <= 0) {
      isLive.value = true
      clearInterval(timerId)
      return
    }

    const totalSeconds = Math.floor(diff / 1000)

    time.value.days = Math.floor(totalSeconds / 86400)
    time.value.hours = Math.floor((totalSeconds % 86400) / 3600)
    time.value.minutes = Math.floor((totalSeconds % 3600) / 60)
    time.value.seconds = totalSeconds % 60
  }

  onMounted(() => {
    updateTimer()
    timerId = setInterval(updateTimer, 1000)
  })

  onUnmounted(() => clearInterval(timerId))
</script>

<style scoped lang="scss">
  .app-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: radial-gradient(circle at top, #d3d3d3, #0b0b0b)
  }

  .launch-card {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 28px 32px;
    border-radius: 12px;
    background: #c7c7c7;
    border: 1px solid #2a2a2a;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
  }

  .launch-countdown {
    display: flex;
    justify-content: center;
    gap: 14px;
  }

  .launch-status {
    font-size: 32px;
    font-weight: 700;
    padding: 14px 28px;
    border-radius: 12px;
  }

  .launch-status.live {
    background: linear-gradient(135deg, #ff2d2d, #ff6b2d);
    color: #fff;
    animation: pulse 1.5s infinite;
  }

  @keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.9; }
    100% { transform: scale(1); opacity: 1; }
  }
</style>
