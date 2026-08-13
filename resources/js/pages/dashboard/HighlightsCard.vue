<script setup>
import {
    ref,
    computed,
    onMounted,
    onBeforeUnmount,
    watch,
} from 'vue'

const events = ref([])

async function loadEvents() {
    const { data } = await axios.get('/api/events/highlights')
    events.value = data
}

const current = ref(0)
const currentImage = ref(0)

const currentEvent = computed(() => {
    if (!events.value.length) {
        return null
    }

    return events.value[current.value]
})

const currentImages = computed(() => {
    if (!currentEvent.value) {
        return []
    }

    // Use event images if available
    if (currentEvent.value.images?.length) {
        return currentEvent.value.images
    }

    // Fallback to cover image
    if (currentEvent.value.cover_image_url) {
        return [
            {
                url: currentEvent.value.cover_image_url,
            },
        ]
    }

    return []
})

const currentImageUrl = computed(() => {
    if (!currentImages.value.length) {
        return currentEvent.value?.cover_image_url
    }

    return (
        currentImages.value[currentImage.value]?.url ??
        currentEvent.value?.cover_image_url
    )
})

const subtitle = computed(() => {
    return (
        events.value
            .slice(0, 2)
            .map(event => event.title)
            .join(', ') +
        (events.value.length > 2 ? ', and more' : '')
    )
})

/**
 * Event duration:
 *
 * 1 photo  = 3 seconds
 * 2 photos = 5 seconds
 * 3 photos = 7 seconds
 * 4 photos = 9 seconds
 *
 * Formula:
 * 3 seconds + 2 seconds for every additional photo
 */
function getEventDuration() {
    const photoCount = currentImages.value.length || 1

    return 3000 + ((photoCount - 1) * 2000)
}

/**
 * Move to the next event.
 */
function next() {
    if (!events.value.length) return

    current.value =
        (current.value + 1) % events.value.length

    currentImage.value = 0

    startEventTimer()
}

/**
 * Move to the previous event.
 */
function previous() {
    if (!events.value.length) return

    current.value =
        (current.value - 1 + events.value.length) %
        events.value.length

    currentImage.value = 0

    startEventTimer()
}

/**
 * Move to the next photo.
 */
function nextImage() {
    if (currentImages.value.length <= 1) {
        return
    }

    currentImage.value =
        (currentImage.value + 1) %
        currentImages.value.length
}

/**
 * Select a specific event.
 */
function selectEvent(index) {
    current.value = index
    currentImage.value = 0

    startEventTimer()
}

let eventTimeout
let imageInterval

/**
 * Start/restart the timer for the current event.
 *
 * Example:
 * 3 photos = 7 seconds total
 *
 * Each photo gets an equal portion of the event duration.
 */
function startEventTimer() {
    clearTimeout(eventTimeout)
    clearInterval(imageInterval)

    const photoCount = currentImages.value.length || 1
    const eventDuration = getEventDuration()

    // If there are multiple photos,
    // divide the event duration equally between them.
    if (photoCount > 1) {
        const photoDuration = eventDuration / photoCount

        imageInterval = setInterval(() => {
            nextImage()
        }, photoDuration)
    }

    // Move to the next event after the full duration.
    eventTimeout = setTimeout(() => {
        current.value =
            (current.value + 1) % events.value.length

        currentImage.value = 0

        startEventTimer()
    }, eventDuration)
}

watch(events, (list) => {
    if (!list.length) {
        current.value = 0
        currentImage.value = 0
        return
    }

    if (current.value >= list.length) {
        current.value = 0
    }

    currentImage.value = 0

    startEventTimer()
})

let initialized = false

watch(current, () => {
    if (!initialized) return

    currentImage.value = 0
    startEventTimer()
})

onMounted(async () => {
    await loadEvents()

    if (events.value.length) {
        initialized = true
        startEventTimer()
    }
})

onBeforeUnmount(() => {
    clearTimeout(eventTimeout)
    clearInterval(imageInterval)
})
</script>

<template>
    <div
        class="rounded-xl border bg-card text-card-foreground shadow p-5 sm:p-6 overflow-hidden lg:col-span-8 lg:row-span-2">
        <!-- Header -->
        <div class="flex items-center gap-3 mb-4">
            <div class="h-9 w-9 rounded-xl bg-primary/10 text-primary grid place-items-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="h-4 w-4">
                    <path d="M8 2v4" />
                    <path d="M16 2v4" />
                    <rect width="18" height="18" x="3" y="4" rx="2" />
                    <path d="M3 10h18" />
                </svg>
            </div>

            <div class="min-w-0">
                <h2 class="font-display text-lg">
                    Events
                </h2>

                <p class="text-xs text-muted-foreground truncate">
                    {{ subtitle }}
                </p>
            </div>
        </div>

        <!-- Event -->
        <div class="relative rounded-xl overflow-hidden">
            <template v-if="currentEvent">
                <Transition name="fade" mode="out-in">
                    <div :key="`${currentEvent.id}-${currentImage}`"
                        class="relative aspect-[16/9] w-full rounded-xl overflow-hidden">
                        <!-- Image -->
                        <img :src="currentImageUrl" :alt="currentEvent.title"
                            class="absolute inset-0 h-full w-full object-cover" />

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 to-transparent" />

                        <!-- Event Details -->
                        <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6 text-white">
                            <div class="text-[11px] uppercase tracking-widest text-white/70 mb-1">
                                Event · {{ currentEvent.published_at }}
                            </div>

                            <h3 class="font-semibold text-xl">
                                {{ currentEvent.title }}
                            </h3>

                            <p class="mt-2 text-base leading-snug line-clamp-3">
                                {{ currentEvent.description }}
                            </p>
                        </div>

                        <!-- Photo indicators -->
                        <div v-if="currentImages.length > 1"
                            class="absolute top-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                            <span v-for="(_, index) in currentImages" :key="index"
                                class="h-1.5 rounded-full transition-all duration-300" :class="currentImage === index
                                        ? 'w-6 bg-white'
                                        : 'w-1.5 bg-white/50'
                                    " />
                        </div>
                    </div>
                </Transition>

                <!-- Previous Event -->
                <button v-if="events.length > 1" @click="previous"
                    class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-black/40 p-2 text-white hover:bg-black/60 transition">
                    ←
                </button>

                <!-- Next Event -->
                <button v-if="events.length > 1" @click="next"
                    class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-black/40 p-2 text-white hover:bg-black/60 transition">
                    →
                </button>

                <!-- Event Dots -->
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
                    <button v-for="(event, index) in events" :key="event.id" @click="selectEvent(index)"
                        class="h-2 w-2 rounded-full transition-all" :class="current === index
                                ? 'bg-white w-6'
                                : 'bg-white/50'
                            " />
                </div>
            </template>

            <!-- Empty -->
            <div v-else
                class="aspect-[16/9] rounded-xl bg-muted flex items-center justify-center text-muted-foreground">
                No featured events.
            </div>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>