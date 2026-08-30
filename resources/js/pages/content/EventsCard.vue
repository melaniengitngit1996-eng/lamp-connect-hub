<script setup>
import { onMounted, ref } from 'vue'
import { useAuth } from '../../stores/auth'
const { can } = useAuth()

import Button from '@/components/Button.vue'
import TrashIcon from '../../icons/TrashIcon.vue'
import PencilIcon from '../../icons/PencilIcon.vue'

import ConfirmDialog from '@/components/ConfirmDialog.vue'
import eventDialog from '../../pages/content/eventDialog.vue'

const events = ref([])
const loading = ref(false)
const selectedEvent = ref(null)
const deleteDialogOpen = ref(false)
const deleting = ref(false)
const dialogOpen = ref(false)

const openDeleteDialog = (event) => {
    selectedEvent.value = event
    deleteDialogOpen.value = true
}

const editEvent = (event) => {
    selectedEvent.value = event
    dialogOpen.value = true
}

const closeDialog = () => {
    dialogOpen.value = false
    selectedEvent.value = null
}

const deleteEvent = async () => {
    deleting.value = true

    try {
        await axios.delete(`/api/events/${selectedEvent.value.id}`)

        await loadEvents()

        deleteDialogOpen.value = false
        selectedEvent.value = null
    } catch (error) {
        alert(error.response?.data?.message ?? 'Unable to delete content.')
    } finally {
        deleting.value = false
    }
}

const newEvent = () => {
    selectedEvent.value = null
    dialogOpen.value = true
}

async function loadEvents() {
    loading.value = true

    try {
        const { data } = await axios.get('/api/events')

        events.value = data
    } finally {
        loading.value = false
    }
}

onMounted(loadEvents)
</script>

<template>
    <div class="rounded-xl border bg-card text-card-foreground shadow p-0">
        <div class="flex items-center justify-between p-4 border-b gap-3">
            <div class="min-w-0" data-tsd-source="/src/routes/_app.admin.content.tsx:122:9">
                <div class="text-sm font-medium" data-tsd-source="/src/routes/_app.admin.content.tsx:123:11">Highlights
                </div>
                <div class="text-xs text-muted-foreground truncate"
                    data-tsd-source="/src/routes/_app.admin.content.tsx:124:11">Gatherings, conferences, and camps. · {{
                    events.length }} {{ events.length === 1 ? 'entry' : 'entries' }}</div>
            </div>
            <button v-if="can('content.create')" @click="newEvent"
                class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs"
                data-tsd-source="/src/routes/_app.admin.content.tsx:126:9">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-plus h-4 w-4 mr-1" aria-hidden="true"
                    data-tsd-source="/src/routes/_app.admin.content.tsx:127:11">
                    <path d="M5 12h14"></path>
                    <path d="M12 5v14"></path>
                </svg>
                New Event
            </button>
        </div>
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&amp;_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th
                            class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">
                            Content</th>
                        <th
                            class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">
                            Venue</th>
                        <th
                            class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">
                            Status</th>
                        <th
                            class="h-10 px-2 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="event in events" :key="event.id" class="border-b transition-colors hover:bg-muted/50">
                        <td class="p-2">
                            <div class="flex items-start gap-3">
                                <img v-if="event.cover_image" :src="event.cover_image_url"
                                    class="h-14 w-20 rounded-md object-cover border">

                                <div class="min-w-0">
                                    <div class="font-medium truncate">
                                        {{ event.title }}
                                    </div>

                                    <div class="text-xs text-muted-foreground line-clamp-2">
                                        {{ event.description }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="p-2 text-sm">
                            {{ event.venue || '—' }}
                        </td>

                        <td class="p-2">
                            <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs">
                                {{ event.status }}
                            </span>
                        </td>

                        <td class="p-2 text-right" width="100">
                            <Button v-if="can('content.update')" @click="editEvent(event)" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('content.delete')" @click="openDeleteDialog(event)" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
                        </td>
                    </tr>

                    <tr v-if="!loading && !events.length">
                        <td colspan="5" class="text-center py-8 text-muted-foreground">
                            No events found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <eventDialog :open="dialogOpen" :event="selectedEvent" @saved="loadEvents()" @close="closeDialog" />

    <ConfirmDialog :open="deleteDialogOpen" title="Delete Content"
        :message="`Delete '${selectedEvent?.title}'? This action cannot be undone.`" confirm-text="Delete"
        :loading="deleting" @close="deleteDialogOpen = false" @confirm="deleteEvent" />
</template>