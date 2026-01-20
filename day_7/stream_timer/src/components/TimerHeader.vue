<template>
  <div class="timerheader">
    <h1>{{ title }}</h1>
  </div>
</template>

<script setup>
    defineProps({
    title: {
        type: String,
        required: true
    }
    })
</script>

<style scoped lang="scss">
    .timerheader {
        text-align: center;

        h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
    }
</style>
