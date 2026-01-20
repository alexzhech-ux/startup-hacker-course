<template>
    <div
        class="card"
        :class="{ flipped: card.flipped, hidden: card.matched }"
        @click="$emit('flip', card)"
    >
        <div class="card-inner">
            <div class="card-front">
                <img src="/img/card.png" alt="card" />
            </div>
            <div class="card-back">
                {{ card.value }}
            </div>
        </div>
    </div>
</template>

<script setup>
    defineProps({
        card: Object
    });
</script>

<style scoped lang="scss">
    .card {
        perspective: 800px;
        cursor: pointer;

        &.hidden {
            visibility: hidden;
        }
    }

    .card-inner {
        position: relative;
        width: 100%;
        height: 100px;
        transform-style: preserve-3d;
        transition: transform 0.5s;
    }

    .card.flipped .card-inner {
    transform: rotateY(180deg);
    }

    .card-front,
    .card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-front {
    background: #ccc;

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
    }

    .card-back {
        background: #4caf50;
        color: white;
        font-size: 32px;
        font-weight: bold;
        transform: rotateY(180deg);
    }
</style>
