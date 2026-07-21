<script setup>
import { ref, watch } from 'vue'

import Dialog from '@/components/Dialog.vue'

const props = defineProps({
    open: Boolean,

    file: {
        type: Object,
        default: null,
    },
})

const activeTab = ref('views')
const views = ref([])
const downloads = ref([])
const viewsCount = ref(0)
const downloadsCount = ref(0)

const emit = defineEmits([
    'close',
])

const loadActivities = async () => {
    const response = await fetch(
        `/api/drive/files/${props.file.id}/activities`
    )

    const data = await response.json()

    views.value = data.views
    downloads.value = data.downloads

    viewsCount.value = data.views_count
    downloadsCount.value = data.downloads_count
}

watch(
    () => props.open,
    async (open) => {
        if (!open || !props.file) {
            return
        }

        await loadActivities()
    }
)
</script>
<template>
    <Dialog
        :open="open"
        @close="emit('close')"
    >
        <div class="flex flex-col space-y-1.5 text-center sm:text-left">
            <h2 class="text-lg font-semibold leading-none tracking-tight truncate">Activity · {{ file.original_name }}</h2>
        </div>
        <div dir="ltr" data-orientation="horizontal">
            <div 
                role="tablist" 
                aria-orientation="horizontal" 
                class="h-9 items-center justify-center rounded-lg bg-muted p-1 text-muted-foreground grid grid-cols-2" 
                tabindex="0" 
                data-orientation="horizontal" 
                style="outline: none;"
            >
                <button
                    @click="activeTab = 'views'"
                    :class="[
                        'inline-flex items-center justify-center px-3 py-1 text-sm rounded-md',
                        activeTab === 'views'
                            ? 'bg-background shadow text-foreground'
                            : 'text-muted-foreground'
                    ]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-3.5 w-3.5 mr-2" aria-hidden="true" data-tsd-source="/src/components/drive-activity-dialog.tsx:76:17">
                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    Views ({{ viewsCount }})
                </button>
                <button
                    @click="activeTab = 'downloads'"
                    :class="[
                        'inline-flex items-center justify-center px-3 py-1 text-sm rounded-md',
                        activeTab === 'downloads'
                            ? 'bg-background shadow text-foreground'
                            : 'text-muted-foreground'
                    ]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download h-3.5 w-3.5 mr-2" aria-hidden="true" data-tsd-source="/src/components/drive-activity-dialog.tsx:79:17">
                        <path d="M12 15V3"></path>
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <path d="m7 10 5 5 5-5"></path>
                    </svg>
                    Downloads ({{ downloadsCount }})
                </button>
            </div>
            
            
            <div data-state="active" v-if="activeTab === 'views'" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4" style="">
                <div v-if="views.length" class="max-h-[50vh] overflow-auto divide-y">
                    <div
                        v-for="activity in views"
                        :key="activity.id"
                        class="flex items-center gap-3 py-2.5"
                    >
                        <span class="relative flex shrink-0 overflow-hidden rounded-full h-8 w-8"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted">{{ activity.user.initials }}</span></span>

                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium truncate">
                                {{ activity.user.name }}
                            </div>

                            <div class="text-xs text-muted-foreground">
                                {{ activity.created_human }}
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="py-8 text-center text-sm text-muted-foreground">No one has viewed this file yet.</div>
            </div>
            <div data-state="inactive" v-if="activeTab === 'downloads'" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4">
                <div v-if="downloads.length" class="max-h-[50vh] overflow-auto divide-y">
                    <div
                        v-for="activity in downloads"
                        :key="activity.id"
                        class="flex items-center gap-3 py-2.5"
                    >
                        <span class="relative flex shrink-0 overflow-hidden rounded-full h-8 w-8"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted">{{ activity.user.initials }}</span></span>

                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium truncate">
                                {{ activity.user?.name ?? 'Unknown User' }}
                            </div>

                            <div class="text-xs text-muted-foreground">
                                {{ activity.created_human }}
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="py-8 text-center text-sm text-muted-foreground">No one has downloaded this file yet.</div>
            </div>
        </div>
    </Dialog>
</template>