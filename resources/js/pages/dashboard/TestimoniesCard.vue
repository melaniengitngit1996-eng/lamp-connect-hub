<script setup>
import axios from 'axios'
import { onMounted, ref } from 'vue'

const testimonies = ref([])

async function loadTestimonies() {
    const { data } = await axios.get('/api/testimonies/latest')
    testimonies.value = data
}

onMounted(loadTestimonies)
</script>

<template>
    <div class="rounded-xl border bg-card text-card-foreground shadow p-5 sm:p-6 lg:col-span-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-9 w-9 rounded-xl bg-primary/10 text-primary grid place-items-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-quote h-4 w-4" aria-hidden="true">
                    <path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"></path>
                    <path d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <h2 class="font-display text-lg leading-tight truncate">Testimonies</h2>
                <p class="text-xs text-muted-foreground truncate">How God is moving</p>
            </div>
        </div>
        <div
            v-if="testimonies.length"
            class="space-y-4"
        >
            <blockquote
                v-for="testimony in testimonies"
                :key="testimony.id"
                class="text-sm"
            >
                <p class="italic leading-relaxed text-foreground/90 line-clamp-4">
                    "{{ testimony.content }}"
                </p>

                <footer class="mt-2 flex items-center gap-2">
                    <span
                        class="relative flex shrink-0 overflow-hidden rounded-full h-6 w-6"
                    >
                        <span
                            class="flex h-full w-full items-center justify-center rounded-full bg-muted text-[10px] font-medium"
                        >
                            {{ testimony.initials }}
                        </span>
                    </span>

                    <span class="text-xs text-muted-foreground truncate">
                        {{ testimony.author }} · {{ testimony.approved_at }}
                    </span>
                </footer>
            </blockquote>
        </div>

        <div
            v-else
            class="py-8 text-center text-sm text-muted-foreground"
        >
            No testimonies available.
        </div>
    </div>
</template>