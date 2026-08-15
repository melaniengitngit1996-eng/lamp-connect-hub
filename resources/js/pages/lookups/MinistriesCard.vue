<script setup>
import { useAuth } from '../../stores/auth'

const { can } = useAuth();

import { ref, onMounted, watch } from 'vue'

import Button from '@/components/Button.vue'
import TrashIcon from '../../icons/TrashIcon.vue'
import PencilIcon from '../../icons/PencilIcon.vue'

import ConfirmDialog from '@/components/ConfirmDialog.vue'
import MinistryDialog from '../../pages/lookups/MinistryDialog.vue'

const loading = ref(false)
const ministries = ref([])
const localChurches = ref([])
const selectedChurchId = ref('')

const dialogOpen = ref(false)
const deleteDialogOpen = ref(false)

const selectedMinistry = ref(null)
const deleting = ref(false)

const editMinistry = (ministry) => {
    selectedMinistry.value = ministry
    dialogOpen.value = true
}

const newMinistry = () => {
    selectedMinistry.value = null
    dialogOpen.value = true
}

const closeDialog = () => {
    dialogOpen.value = false
    selectedMinistry.value = null
}

const openDeleteDialog = (ministry) => {
    selectedMinistry.value = ministry
    deleteDialogOpen.value = true
}

const deleteMinistry = async () => {
    deleting.value = true

    try {
        await axios.delete(
            `/api/ministries/${selectedMinistry.value.id}`
        )

        await fetchMinistries()

        deleteDialogOpen.value = false
        selectedMinistry.value = null
    } catch (error) {
        alert(error.response?.data?.message ?? 'Unable to delete ministry.')
    } finally {
        deleting.value = false
    }
}

const fetchLocalChurches = async () => {
    const { data } = await axios.get('/api/local-churches')
    localChurches.value = data
}

const fetchMinistries = async () => {
    loading.value = true

    try {
        const params = {}

        if (selectedChurchId.value === 'national') {
            params.national = true
        } else if (selectedChurchId.value) {
            params.local_church_id = selectedChurchId.value
        }

        const { data } = await axios.get('/api/ministries', {
            params,
        })

        ministries.value = data
    } finally {
        loading.value = false
    }
}

onMounted(async () => {
    await fetchLocalChurches()
    await fetchMinistries()
})

watch(selectedChurchId, fetchMinistries)
</script>

<template>
    <div data-state="active" data-orientation="horizontal" role="tabpanel" id="radix-_r_u_-content-ministries"
        tabindex="0"
        class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4"
        data-tsd-source="/src/routes/_app.admin.lookups.tsx:62:9">
        <div class="rounded-xl border bg-card text-card-foreground shadow p-0">
            <div class="flex items-center justify-between p-4 border-b gap-3">
                <div class="flex items-center gap-3">
                    <div class="text-sm text-muted-foreground">{{ ministries.length }} entr{{ ministries.length === 1 ?
                        'y' : 'ies' }}</div>
                    <select v-model="selectedChurchId"
                        class="flex h-8 w-56 rounded-md border border-input bg-transparent px-3 text-sm">
                        <option value="">All churches</option>
                        <option value="national">National Ministries</option>

                        <option v-for="church in localChurches" :key="church.id" :value="church.id">
                            {{ church.name }}
                        </option>
                    </select>
                </div>
                <button v-if="can('lookups.create')"
                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs"
                    @click="newMinistry">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>

                    New Ministry
                </button>
            </div>
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom text-sm">
                    <thead class="[&amp;_tr]:border-b">
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <th
                                class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">
                                Name</th>
                            <th
                                class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">
                                Local Church</th>
                            <th
                                class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">
                                Description</th>
                            <th
                                class="h-10 px-2 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="[&amp;_tr:last-child]:border-0">
                        <tr v-for="ministry in ministries" :key="ministry.id"
                            class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td
                                class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] font-medium">
                                {{ ministry.name }}</td>
                            <td
                                class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm">
                                {{ ministry.local_church?.name ?? 'National' }}</td>
                            <td
                                class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground">
                                {{ ministry.description || '—' }}</td>
                            <td
                                class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right">
                                <Button v-if="can('lookups.update')" type="icon" @click="editMinistry(ministry)">
                                    <PencilIcon />
                                </Button>

                                <Button v-if="can('lookups.delete')" type="icon" @click="openDeleteDialog(ministry)">
                                    <TrashIcon class="text-destructive" />
                                </Button>
                            </td>
                        </tr>
                        <tr v-if="!loading && ministries.length === 0">
                            <td colspan="4" class="p-8 text-center text-muted-foreground">
                                No ministries found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <MinistryDialog :open="dialogOpen" :ministry="selectedMinistry" @saved="fetchMinistries" @close="closeDialog" />

    <ConfirmDialog :open="deleteDialogOpen" title="Delete Ministry"
        :message="`Delete '${selectedMinistry?.name}'? This action cannot be undone.`" confirm-text="Delete"
        :loading="deleting" @close="deleteDialogOpen = false" @confirm="deleteMinistry" />
</template>