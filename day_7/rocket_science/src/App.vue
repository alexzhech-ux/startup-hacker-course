<template>
    <div class="app" :style="{ backgroundImage: 'url(/bg.jpg)' }">
        <aside class="menu">
    <h3>Корабли</h3>
    <div
        v-for="(cfg, index) in ships"
        :key="cfg.id"
        class="shipCard"
    >
        <img :src="`/${cfg.image}`" class="shipCard__image" />
        <div class="shipCard__info">
            <p><b>Топливо:</b> {{ cfg.fuel }}</p>
            <p><b>Расход:</b> {{ cfg.speed }}</p>
        </div>
        <SButton block @click="chooseShip(index)">
            Выбрать корабль
        </SButton>
    </div>
    </aside>
        <div
            v-for="ship in activeShips"
            :key="ship.uid"
            class="activeShip"
            :style="{ top: ship.y + 'px', left: ship.x + 'px' }"
        >
            <img
                class="activeShip__ship"
                :src="ship.crashed
                ? `/${ships[ship.type].crashed}`
                : `/${ships[ship.type].image}`"
                @mousedown.prevent.stop="refuel(ship)"
                draggable="false"
            />
            <div class="activeShip__name">{{ ship.name }}</div>
            <div class="activeShip__fuelBar">
                <div
                    class="activeShip__fuelFill"
                    :class="{ danger: ship.fuel / ship.maxFuel <= 0.1 }"
                    :style="{ width: (ship.fuel / ship.maxFuel) * 100 + '%' }"
                    />
            </div>
        </div>
        <div v-if="showModal" class="shipNameBox">
            <div class="shipNameBox__content">
                <h3>Имя корабля</h3>
                <SInput v-model="shipName" placeholder="Введите имя" />
                <SButton block :disabled="!shipName" @click="launch">
                    Запустить
                </SButton>
            </div>
        </div>
        <div class="statsHud">
            <div>Активные: {{ activeCount }}</div>
            <div>Разбитые: {{ crashedCount }}</div>
            <div>Всего запущено: {{ totalLaunched }}</div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, onUnmounted, computed } from 'vue'
    import { SButton, SInput } from 'startup-ui'
    import 'startup-ui/dist/index.css';
    const totalLaunched = ref(0)
    const ships = [
        { id: 1, image: 'ship1.png', crashed: 'ship_crashed1.png', fuel: 120, speed: 0.8 },
        { id: 2, image: 'ship2.png', crashed: 'ship_crashed2.png', fuel: 140, speed: 1.0 },
        { id: 3, image: 'ship3.png', crashed: 'ship_crashed3.png', fuel: 160, speed: 1.2 },
        { id: 4, image: 'ship4.png', crashed: 'ship_crashed4.png', fuel: 180, speed: 1.4 },
        { id: 5, image: 'ship5.png', crashed: 'ship_crashed5.png', fuel: 200, speed: 1.6 }
    ]
    const MENU_WIDTH = 260
    const SHIP_WIDTH = 280
    const SHIP_HEIGHT = 280
    const FALL_THRESHOLD = 0.1
    const GRAVITY = 0.8
    const ACCELERATION = 0.05
    const MAX_SPEED = 10
    const REFUEL_AMOUNT = 8
    const activeShips = ref([])
    const selectedType = ref(null)
    const showModal = ref(false)
    const shipName = ref('')
    const paused = ref(false)
    let uid = 1
    let timer = null

    const chooseShip = (type) => {
        paused.value = true
        selectedType.value = type
        shipName.value = ''
        showModal.value = true
    }

    const launch = () => {
        const cfg = ships[selectedType.value]

        activeShips.value.push({
            uid: uid++,
            type: selectedType.value,
            name: shipName.value,
            fuel: cfg.fuel,
            maxFuel: cfg.fuel,
            x: MENU_WIDTH + (window.innerWidth - MENU_WIDTH) / 2,
            y: window.innerHeight / 2 - SHIP_HEIGHT / 2,
            speed: 2,
            dirX: Math.random() > 0.5 ? 1 : -1,
            dirY: Math.random() > 0.5 ? 1 : -1,
            crashed: false
        })

        totalLaunched.value++
        showModal.value = false
        paused.value = false
    }

    const activeCount = computed(() =>
        activeShips.value.filter(s => !s.crashed).length
    )

    const crashedCount = computed(() =>
        activeShips.value.filter(s => s.crashed).length
    )

    const refuel = (ship) => {
        if (ship.crashed || ship.fuel <= 0) return
        ship.fuel = Math.min(ship.fuel + REFUEL_AMOUNT, ship.maxFuel)
    }

    const tick = () => {
        if (paused.value) return

        activeShips.value.forEach(ship => {
            if (ship.crashed) return

            ship.fuel -= ships[ship.type].speed
            if (ship.fuel < 0) ship.fuel = 0

            if (ship.speed < MAX_SPEED) {
                ship.speed += ACCELERATION
            }

            ship.x += ship.speed * ship.dirX

            const fuelRatio = ship.fuel / ship.maxFuel

            if (fuelRatio > FALL_THRESHOLD) {
                ship.vy = 0
                ship.y += ship.speed * 0.5 * ship.dirY
            } else {
                ship.vy += GRAVITY
                ship.y += ship.vy
            }

            if (
                ship.x <= MENU_WIDTH + SHIP_WIDTH / 2 ||
                ship.x >= window.innerWidth - SHIP_WIDTH / 2
            ) {
                ship.dirX *= -1
            }

            if (fuelRatio > FALL_THRESHOLD) {
                if (ship.y <= 0) {
                    ship.y = 0
                    ship.dirY = 1
                }

                if (ship.y >= window.innerHeight - SHIP_HEIGHT) {
                    ship.y = window.innerHeight - SHIP_HEIGHT
                    ship.dirY = -1
                }
            }

            if (ship.y + SHIP_HEIGHT >= window.innerHeight) {
                ship.y = window.innerHeight - SHIP_HEIGHT
                if (fuelRatio <= FALL_THRESHOLD) {
                    ship.crashed = true
                } else {
                    ship.dirY = -1
                }
            }
        })
    }

    onMounted(() => {
        timer = setInterval(tick, 50)
    })

    onUnmounted(() => {
        clearInterval(timer)
    })
</script>

<style>
    .app {
        width: 100vw;
        height: 100vh;
        background-size: cover;
        position: relative;
        overflow: hidden;
    }

    .menu {
        position: absolute;
        left: 0;
        top: 0;
        width: 260px;
        height: 100%;
        background: rgba(0,0,0,.75);
        color: white;
        padding: 10px;
        overflow-y: auto;
        z-index: 5;
    }

    .shipCard {
        border: 1px solid rgba(255,255,255,.2);
        padding: 10px;
        margin-bottom: 10px;
    }

    .shipCard__image {
        width: 100%;
    }

    .shipCard button {
        width: 100%;
        margin-top: 5px;
    }

    .activeShip {
        position: absolute;
        transform: translateX(-50%);
        text-align: center;
    }

    .activeShip__ship {
        width: 280px;
        cursor: pointer;
        user-select: none;
        -webkit-user-drag: none;
    }

    .activeShip__name {
        color: white;
        font-size: 14px;
        margin-top: 4px;
    }

    .activeShip__fuelBar {
        width: 120px;
        height: 8px;
        background: rgba(255,255,255,.25);
        border-radius: 4px;
        margin: 4px auto 0;
        overflow: hidden;
    }

    .activeShip__fuelFill {
        height: 100%;
        background: limegreen;
    }

    .activeShip__fuelFill.danger {
        background: red;
    }

    .shipNameBox {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 20;
    }

    .shipNameBox__content {
        background: white;
        padding: 20px;
        min-width: 250px;
    }

    .statsHud {
        position: fixed;
        top: 10px;
        right: 10px;
        padding: 10px 14px;
        background: rgba(0, 0, 0, 0.35);
        color: white;
        font-size: 14px;
        border-radius: 8px;
        line-height: 1.4;
        z-index: 50;
        pointer-events: none;
    }

    .s-button {
        width: 100%;
    }
</style>
