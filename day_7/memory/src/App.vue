<template>
  <div class="app-wrapper">
    <div class="table">
      <h1>Игра Memory</h1>
      <div class="stats">
        <div>Ходы: <strong>{{ moves }}</strong></div>
        <div>Осталось карт: <strong>{{ remainingCards }}</strong></div>
        <div>Время: <strong>{{ formattedTime }}</strong></div>
        <div>Пар: <strong>{{ initialPairsCount }}</strong></div>
      </div>
      <div v-if="gameFinished" class="result">
        <div class="result-title">Игра завершена</div>
        <div class="result-grade">{{ resultGrade }}</div>
        <div class="result-details">
          Ходы: {{ moves }} · Время: {{ formattedTime }}
        </div>
      </div>
      <div class="controls">
        <SInput
          type="number"
          min="2"
          max="20"
          v-model.number="pairsCount"
          :disabled="gameStarted && !gameFinished"
          class="input"
        />
        <SButton as="button" @click="startGame">
          Старт
        </SButton>
      </div>
      <div class="cards-grid">
        <Card
          v-for="card in cards"
          :key="card.id"
          :card="card"
          @flip="onCardFlip"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
    import { ref, computed } from "vue";
    import Card from "@/components/Card.vue";
    import { SButton, SInput } from "startup-ui";
    import 'startup-ui/dist/index.css';
    const pairsCount = ref(4);
    const initialPairsCount = ref(0);
    const cards = ref([]);
    const moves = ref(0);
    const gameStarted = ref(false);
    const gameFinished = ref(false);
    const startTime = ref(null);
    const elapsedSeconds = ref(0);
    const resultGrade = ref("");
    let firstCard = null;
    let secondCard = null;
    let lockBoard = false;
    let timerId = null;

    function startGame() {
        if (timerId) stopTimer();

        cards.value = [];
        moves.value = 0;
        elapsedSeconds.value = 0;
        startTime.value = null;
        gameStarted.value = false;
        gameFinished.value = false;
        resultGrade.value = "";

        firstCard = null;
        secondCard = null;
        lockBoard = false;

        initialPairsCount.value = pairsCount.value;

        const numbers = [];
        for (let i = 1; i <= initialPairsCount.value; i++) {
            numbers.push(i, i);
        }

        cards.value = shuffle(
            numbers.map((num, index) => ({
                id: `${index}-${Math.random()}`,
                value: num,
                flipped: false,
                matched: false
            }))
        );
    }

    function shuffle(array) {
        return array.sort(() => Math.random() - 0.5);
    }

    function onCardFlip(card) {
        if (lockBoard || card.flipped || card.matched) return;

        if (!gameStarted.value) {
            gameStarted.value = true;
            startTimer();
        }

        card.flipped = true;

        if (!firstCard) {
            firstCard = card;
            return;
        }

        secondCard = card;
        lockBoard = true;
        moves.value++;

        if (firstCard.value === secondCard.value) {
            setTimeout(() => {
                firstCard.matched = true;
                secondCard.matched = true;
                resetTurn();

                if (remainingCards.value === 0) {
                    stopTimer();
                    gameFinished.value = true;
                    resultGrade.value = calculateGrade();
                }
            }, 400);
        } else {
            setTimeout(() => {
                firstCard.flipped = false;
                secondCard.flipped = false;
                resetTurn();
            }, 800);
        }
    }

    function resetTurn() {
        firstCard = null;
        secondCard = null;
        lockBoard = false;
    }

    function startTimer() {
        if (timerId) return;

        startTime.value = Date.now();
        timerId = setInterval(() => {
            elapsedSeconds.value = Math.floor(
            (Date.now() - startTime.value) / 1000
            );
        }, 1000);
    }

    function stopTimer() {
        clearInterval(timerId);
        timerId = null;
    }

    const remainingCards = computed(() =>
        cards.value.filter(card => !card.matched).length
    );

    const formattedTime = computed(() => {
        const min = Math.floor(elapsedSeconds.value / 60);
        const sec = elapsedSeconds.value % 60;
        return `${min}:${String(sec).padStart(2, "0")}`;
    });

    function calculateGrade() {
        const idealMoves = initialPairsCount.value;
        const ratio = moves.value / idealMoves;

        if (ratio <= 1.5) return "⭐⭐⭐ Отлично";
        if (ratio <= 2) return "⭐⭐ Хорошо";
        return "⭐ Нормально";
    }
</script>

<style scoped lang="scss">
        .app-wrapper {
        display: flex;
        justify-content: center;
        padding: 20px;
    }

    .table {
        width: 100%;
        max-width: 600px;
    }

    .stats {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
        padding: 10px;
        background: #f2f2f2;
        border-radius: 8px;
        font-size: 14px;
    }

    .controls {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;

        input {
            width: 80px;
        }
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 12px;
    }

    .result {
        margin: 15px 0;
        padding: 15px;
        border-radius: 10px;
        background: #e8f5e9;
        text-align: center;
    }

    .result-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .result-grade {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .result-details {
        font-size: 14px;
        opacity: 0.7;
    }
</style>
