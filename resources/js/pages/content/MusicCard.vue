<script setup>
import { ref, onMounted } from 'vue'
import { useAuth } from '../../stores/auth'
const { can } = useAuth()

import Button from '@/components/Button.vue'
import TrashIcon from '../../icons/TrashIcon.vue'
import PencilIcon from '../../icons/PencilIcon.vue'

import ConfirmDialog from '@/components/ConfirmDialog.vue'
import MusicDialog from '../../pages/content/MusicDialog.vue'

const loading = ref(false)
const compositions = ref([])
const selectedMusic = ref(null)
const deleteDialogOpen = ref(false)
const deleting = ref(false)
const dialogOpen = ref(false)

const openDeleteDialog = (event) => {
    selectedMusic.value = event
    deleteDialogOpen.value = true
}

const editMusic = (event) => {
    selectedMusic.value = event
    dialogOpen.value = true
}

const closeDialog = () => {
    dialogOpen.value = false
    selectedMusic.value = null
}

const deleteMusic = async () => {
    deleting.value = true

    try {
        await axios.delete(`/api/compositions/${selectedMusic.value.id}`)

        await loadCompositions()

        deleteDialogOpen.value = false
        selectedMusic.value = null
    } catch (error) {
        alert(error.response?.data?.message ?? 'Unable to delete music.')
    } finally {
        deleting.value = false
    }
}

const newMusic = () => {
    selectedMusic.value = null
    dialogOpen.value = true
}

const loadCompositions = async () => {
    loading.value = true

    try {
        const { data } = await axios.get('/api/compositions')
        compositions.value = data
    } catch (error) {
        console.error('Failed to load compositions.', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    loadCompositions()
})
</script>

<template>
<div class="rounded-xl border bg-card text-card-foreground shadow p-0" data-tsd-source="/src/routes/_app.admin.content.tsx:120:5">
    <div class="flex items-center justify-between p-4 border-b gap-3" data-tsd-source="/src/routes/_app.admin.content.tsx:121:7">
        <div class="min-w-0" data-tsd-source="/src/routes/_app.admin.content.tsx:122:9">
            <div class="text-sm font-medium" data-tsd-source="/src/routes/_app.admin.content.tsx:123:11">LAMP Music</div>
            <div class="text-xs text-muted-foreground truncate" data-tsd-source="/src/routes/_app.admin.content.tsx:124:11">Chord charts, setlists, and worship resources. · {{ compositions.length }} {{ compositions.length === 1 ? 'resource' : 'resources' }}</div>
        </div>
        <button v-if="can('content.create')" @click="newMusic" class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.admin.content.tsx:126:9">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.content.tsx:127:11">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
            </svg>
            New LAMP Music
        </button>
    </div>
    <div class="relative w-full overflow-auto" data-tsd-source="/src/components/ui/table.tsx:7:5">
        <table class="w-full caption-bottom text-sm" data-tsd-source="/src/routes/_app.admin.content.tsx:135:9">
            <thead class="[&amp;_tr]:border-b" data-tsd-source="/src/routes/_app.admin.content.tsx:136:11">
                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted" data-tsd-source="/src/routes/_app.admin.content.tsx:137:13">
                    <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]" data-tsd-source="/src/routes/_app.admin.content.tsx:138:15">Content</th>
                    <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]" data-tsd-source="/src/routes/_app.admin.content.tsx:139:15">Type</th>
                    <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]" data-tsd-source="/src/routes/_app.admin.content.tsx:140:15">Author</th>
                    <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]" data-tsd-source="/src/routes/_app.admin.content.tsx:141:15">Status</th>
                    <th class="h-10 px-2 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right" data-tsd-source="/src/routes/_app.admin.content.tsx:142:15">Actions</th>
                </tr>
            </thead>
            <tbody class="[&_tr:last-child]:border-0">
                <tr
                    v-for="composition in compositions"
                    :key="composition.id"
                    class="border-b transition-colors hover:bg-muted/50"
                >
                    <td class="p-2 max-w-md">
                        <div class="font-medium flex items-center gap-2">
                            {{ composition.title }}

                            <span
                                v-if="composition.is_featured"
                                class="inline-flex items-center rounded-md bg-muted px-2 py-0.5 text-xs"
                            >
                                Featured
                            </span>
                        </div>

                        <div
                            class="text-sm text-muted-foreground line-clamp-2"
                        >
                            {{ composition.description }}
                        </div>

                        <div
                            class="text-xs text-muted-foreground mt-1 truncate"
                        >
                            {{ composition.file_name }}
                            •
                            {{ composition.file_size }}
                        </div>
                    </td>

                    <td class="p-2">
                        {{ composition.type }}
                    </td>

                    <td class="p-2">
                        {{ composition.author }}
                    </td>

                    <td class="p-2">
                        <span
                            class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs"
                        >
                            {{ composition.status }}
                        </span>
                    </td>

                    <td class="p-2 text-right">
                        <Button v-if="can('content.update')" @click="editMusic(composition)" type="icon">
                            <PencilIcon />
                        </button>
                        <Button v-if="can('content.delete')" @click="openDeleteDialog(composition)" type="icon">
                            <TrashIcon class="text-destructive" />
                        </button>
                    </td>
                </tr>

                <tr v-if="!loading && !compositions.length">
                    <td
                        colspan="6"
                        class="text-center py-8 text-muted-foreground"
                    >
                        No music resources found.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

    <MusicDialog
        :open="dialogOpen"
        :compositions="selectedMusic"
        @saved="loadCompositions()"
        @close="closeDialog"
    />

    <ConfirmDialog
        :open="deleteDialogOpen"
        title="Delete Content"
        :message="`Delete '${selectedMusic?.title}'? This action cannot be undone.`"
        confirm-text="Delete"
        :loading="deleting"
        @close="deleteDialogOpen = false"
        @confirm="deleteMusic"
/>
</template>