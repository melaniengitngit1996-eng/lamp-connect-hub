<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue'

const events = ref([])

async function loadEvents() {
    const { data } = await axios.get('/api/events/highlights')
    events.value = data
}

const current = ref(0)

const currentEvent = computed(() => {
    if (!events.value.length) {
        return null
    }

    return events.value[current.value]
})

function next() {
    if (!events.value.length) return

    current.value = (current.value + 1) % events.value.length
}

function previous() {
    if (!events.value.length) return

    current.value =
        (current.value - 1 + events.value.length) % events.value.length
}

const subtitle = computed(() => {
    return events.value
        .slice(0, 2)
        .map(event => event.title)
        .join(', ') +
        (events.value.length > 2 ? ', and more' : '')
})

watch(events, (list) => {
    if (current.value >= list.length) {
        current.value = 0
    }
})

let interval

onMounted(() => {
    interval = setInterval(next, 5000)
})

onBeforeUnmount(() => {
    clearInterval(interval)
})

onMounted(loadEvents)
</script>

<template>
    <div class="rounded-xl border bg-card text-card-foreground shadow p-5 sm:p-6 overflow-hidden lg:col-span-8 lg:row-span-2">
        <div class="flex items-center gap-3 mb-4" data-tsd-source="/src/routes/_app.feed.tsx:168:5">
            <div class="h-9 w-9 rounded-xl bg-primary/10 text-primary grid place-items-center shrink-0" data-tsd-source="/src/routes/_app.feed.tsx:169:7">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar h-4 w-4" aria-hidden="true" data-tsd-source="/src/routes/_app.feed.tsx:170:9">
                    <path d="M8 2v4"></path>
                    <path d="M16 2v4"></path>
                    <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                    <path d="M3 10h18"></path>
                </svg>
            </div>
            <div class="min-w-0" data-tsd-source="/src/routes/_app.feed.tsx:172:7">
                <h2 class="font-display text-lg">
                    Events
                </h2>

                <p class="text-xs text-muted-foreground truncate">
                    {{ subtitle }}
                </p>
            </div>
        </div>
        <div class="relative rounded-xl overflow-hidden">
            <template v-if="currentEvent">
                <Transition name="fade" mode="out-in">
                    <div
                        :key="currentEvent.id"
                        class="relative aspect-[16/9] w-full rounded-xl overflow-hidden"
                    >
                        <img
                            :src="currentEvent.cover_image_url"
                            :alt="currentEvent.title"
                            class="absolute inset-0 h-full w-full object-cover"
                        >

                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 to-transparent"></div>

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

                            <RouterLink
                                :to="currentEvent.url"
                                class="inline-flex items-center gap-1 mt-3 text-sm font-medium hover:underline"
                            >
                                Learn more →
                            </RouterLink>

                        </div>
                    </div>
                </Transition>

                <!-- Previous -->
                <button
                    @click="previous"
                    class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-black/40 p-2 text-white hover:bg-black/60 transition"
                >
                    ←
                </button>

                <!-- Next -->
                <button
                    @click="next"
                    class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-black/40 p-2 text-white hover:bg-black/60 transition"
                >
                    →
                </button>

                <!-- Dots -->
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
                    <button
                        v-for="(event, index) in events"
                        :key="event.id"
                        @click="current = index"
                        class="h-2 w-2 rounded-full transition-all"
                        :class="current === index
                            ? 'bg-white w-6'
                            : 'bg-white/50'"
                    />
                </div>
            </template>

            <div
                v-else
                class="aspect-[16/9] rounded-xl bg-muted flex items-center justify-center text-muted-foreground"
            >
                No featured events.
            </div>
        </div>
    </div>
</template>