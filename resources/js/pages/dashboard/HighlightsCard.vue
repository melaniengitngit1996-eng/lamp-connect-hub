<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue'

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

    // If the API already returns images
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

    return currentImages.value[currentImage.value]?.url
        ?? currentEvent.value?.cover_image_url
})

const subtitle = computed(() => {
    return events.value
        .slice(0, 2)
        .map(event => event.title)
        .join(', ') +
        (events.value.length > 2 ? ', and more' : '')
})

function next() {
    if (!events.value.length) return

    current.value =
        (current.value + 1) % events.value.length

    currentImage.value = 0
}

function previous() {
    if (!events.value.length) return

    current.value =
        (current.value - 1 + events.value.length) %
        events.value.length

    currentImage.value = 0
}

function nextImage() {
    if (currentImages.value.length <= 1) {
        return
    }

    currentImage.value =
        (currentImage.value + 1) %
        currentImages.value.length
}

function selectEvent(index) {
    current.value = index
    currentImage.value = 0
}

watch(events, (list) => {
    if (current.value >= list.length) {
        current.value = 0
    }

    currentImage.value = 0
})

watch(current, () => {
    currentImage.value = 0
})

let eventInterval
let imageInterval

onMounted(async () => {
    await loadEvents()

    // Change event every 5 seconds
    eventInterval = setInterval(next, 5000)

    // Change image every 3 seconds
    imageInterval = setInterval(nextImage, 3000)
})

onBeforeUnmount(() => {
    clearInterval(eventInterval)
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
                            class="absolute inset-0 h-full w-full object-cover">

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

                            <!-- <RouterLink :to="currentEvent.url"
                                class="inline-flex items-center gap-1 mt-3 text-sm font-medium hover:underline">
                                Learn more →
                            </RouterLink> -->
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