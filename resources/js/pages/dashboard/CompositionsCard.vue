<script setup>
import { ref, computed, onMounted } from 'vue'

const compositions = ref([])

async function loadCompositions() {
    const { data } = await axios.get('/api/compositions/latest')
    compositions.value = data
}

const subtitle = computed(() => {
    if (!compositions.value.length) {
        return 'Latest worship resources'
    }

    return compositions.value
        .slice(0, 2)
        .map(item => item.title)
        .join(', ') +
        (compositions.value.length > 2 ? ', and more' : '')
})

function icon(type) {
    switch (type) {
        case 'song':
        case 'lyrics':
            return '🎵'

        case 'setlist':
            return '🎼'

        case 'lead_sheet':
        case 'chord_chart':
            return '📄'

        case 'audio':
            return '🎧'

        default:
            return '📁'
    }
}

onMounted(loadCompositions)
</script>

<template>
    <div class="rounded-xl border text-card-foreground shadow p-5 sm:p-6 bg-gradient-to-br from-primary/10 via-background to-background">

        <div class="flex items-center gap-3 mb-4">
            <div class="h-9 w-9 rounded-xl bg-primary/10 text-primary grid place-items-center shrink-0">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M9 18V5l12-2v13" />
                    <circle cx="6" cy="18" r="3" />
                    <circle cx="18" cy="16" r="3" />
                </svg>
            </div>

            <div class="min-w-0">
                <h2 class="font-display text-lg">
                    LAMP Music
                </h2>

                <p class="text-xs text-muted-foreground truncate">
                    {{ subtitle }}
                </p>
            </div>
        </div>

        <p class="text-sm text-muted-foreground mb-4">
            Download worship songs, chord charts, lead sheets, and Sunday worship resources.
        </p>

        <div
            v-if="compositions.length"
            class="space-y-2"
        >
            <a
                v-for="composition in compositions"
                :key="composition.id"
                :href="composition.download_url"
                target="_blank"
                rel="noreferrer"
                class="flex items-center gap-3 rounded-lg border bg-card/50 p-3 hover:bg-accent transition"
            >
                <div class="h-9 w-9 rounded-lg bg-primary/10 text-primary grid place-items-center shrink-0">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="h-4 w-4"
                    >
                        <path d="M12 15V3"></path>
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <path d="m7 10 5 5 5-5"></path>
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="text-sm font-medium line-clamp-2 leading-snug">
                        {{ composition.title }}
                    </div>

                    <div class="py-1.5 text-muted-foreground text-xs">{{ composition.description }}</div>

                    <div class="text-xs text-muted-foreground truncate">
                        {{ composition.user }} · {{ composition.published_at }}
                    </div>
                </div>
            </a>
        </div>

        <div
            v-else
            class="py-8 text-center text-sm text-muted-foreground"
        >
            No compositions available.
</div>

    </div>
</template>