<script setup>
import { ref, onMounted } from 'vue'

const announcements = ref([])

async function loadAnnouncements() {
    const { data } = await axios.get('/api/announcements/latest')
    announcements.value = data
}

onMounted(loadAnnouncements)
</script>

<template>
    <div class="rounded-xl border bg-card text-card-foreground shadow p-5 sm:p-6 lg:col-span-4">
        <div class="flex items-center gap-3 mb-4" data-tsd-source="/src/routes/_app.feed.tsx:168:5">
            <div class="h-9 w-9 rounded-xl bg-primary/10 text-primary grid place-items-center shrink-0"
                data-tsd-source="/src/routes/_app.feed.tsx:169:7">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-megaphone h-4 w-4" aria-hidden="true"
                    data-tsd-source="/src/routes/_app.feed.tsx:170:9">
                    <path
                        d="M11 6a13 13 0 0 0 8.4-2.8A1 1 0 0 1 21 4v12a1 1 0 0 1-1.6.8A13 13 0 0 0 11 14H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z">
                    </path>
                    <path d="M6 14a12 12 0 0 0 2.4 7.2 2 2 0 0 0 3.2-2.4A8 8 0 0 1 10 14"></path>
                    <path d="M8 6v8"></path>
                </svg>
            </div>
            <div class="min-w-0" data-tsd-source="/src/routes/_app.feed.tsx:172:7">
                <h2 class="font-display text-lg leading-tight truncate"
                    data-tsd-source="/src/routes/_app.feed.tsx:173:9">News &amp; Announcements</h2>
                <p class="text-xs text-muted-foreground truncate" data-tsd-source="/src/routes/_app.feed.tsx:174:22">
                    What's happening this week</p>
            </div>
        </div>
        <ul v-if="announcements.length" class="space-y-4">
            <li v-for="announcement in announcements" :key="announcement.id" class="border-l-2 border-primary/30 pl-3">
                <div class="text-sm leading-relaxed whitespace-pre-wrap">
                    {{ announcement.content }}
                </div>

                <div class="mt-1 flex items-center gap-2 text-xs text-muted-foreground">
                    <span v-if="announcement.is_pinned" class="text-primary font-medium">
                        📌 Pinned
                    </span>

                    <span>{{ announcement.author }}</span>
                    <span>·</span>
                    <span>{{ announcement.published_at }}</span>
                </div>
            </li>
        </ul>
        <div v-else class="py-8 text-center text-sm text-muted-foreground">
            No news & announcements available.
        </div>
    </div>
</template>
