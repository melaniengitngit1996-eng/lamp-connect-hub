<script setup>
import { ref, onMounted } from 'vue'
import { useAuth } from '../../stores/auth'

import Button from '@/components/Button.vue'
import TrashIcon from '../../icons/TrashIcon.vue'
import PencilIcon from '../../icons/PencilIcon.vue'

import ConfirmDialog from '@/components/ConfirmDialog.vue'
import localChurchDialog from '../../pages/lookups/localChurchDialog.vue'

const { can } = useAuth()

const loading = ref(false)
const localChurches = ref([])
const deleteDialogOpen = ref(false)
const selectedLocalChurch = ref(null)
const deleting = ref(false)
const dialogOpen = ref(false)

const openDeleteDialog = (localChurch) => {
    selectedLocalChurch.value = localChurch
    deleteDialogOpen.value = true
}

const editLocalChurch = (localChurch) => {
    selectedLocalChurch.value = localChurch
    dialogOpen.value = true
}

const closeDialog = () => {
    dialogOpen.value = false
    selectedLocalChurch.value = null
}

const fetchLocalChurches = async () => {
    loading.value = true

    try {
        const { data } = await axios.get('/api/local-churches')

        localChurches.value = data
    } finally {
        loading.value = false
    }
}

const deleteLocalChurch = async () => {
    deleting.value = true

    try {
        await axios.delete(`/api/local-churches/${selectedLocalChurch.value.id}`)

        await fetchLocalChurches()

        deleteDialogOpen.value = false
        selectedLocalChurch.value = null
    } catch (error) {
        alert(error.response?.data?.message ?? 'Unable to delete local church.')
    } finally {
        deleting.value = false
    }
}

const newLocalChurch = () => {
    selectedLocalChurch.value = null
    dialogOpen.value = true
}

onMounted(fetchLocalChurches)
</script>

<template>
<div data-state="active" data-orientation="horizontal" role="tabpanel" aria-labelledby="radix-_r_u_-trigger-local_churches" id="radix-_r_u_-content-local_churches" tabindex="0" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4" data-tsd-source="/src/routes/_app.admin.lookups.tsx:59:9" style="">
	<div class="rounded-xl border bg-card text-card-foreground shadow p-0" data-tsd-source="/src/routes/_app.admin.lookups.tsx:116:5">
		<div class="flex items-center justify-between p-4 border-b gap-3" data-tsd-source="/src/routes/_app.admin.lookups.tsx:117:7">
			<div class="flex items-center gap-3" data-tsd-source="/src/routes/_app.admin.lookups.tsx:118:9">
				<div class="text-sm text-muted-foreground">
                    {{ localChurches.length }} entr{{ localChurches.length === 1 ? 'y' : 'ies' }}
                </div>
			</div>
			<button v-if="can('lookups.create')" @click="newLocalChurch" class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs" data-tsd-source="/src/routes/_app.admin.lookups.tsx:135:9">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-1" aria-hidden="true" data-tsd-source="/src/routes/_app.admin.lookups.tsx:136:11">
					<path d="M5 12h14"></path>
					<path d="M12 5v14"></path>
				</svg>
				New Local Church
			</button>
		</div>
		<div class="relative w-full overflow-auto">
			<table class="w-full caption-bottom text-sm">
				<thead class="[&amp;_tr]:border-b">
					<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
						<th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">Name</th>
						<th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">Code</th>
						<th class="h-10 px-2 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right">Actions</th>
					</tr>
				</thead>
				<tbody class="[&amp;_tr:last-child]:border-0">
                    <tr
                        v-for="church in localChurches"
                        :key="church.id"
                        class="border-b transition-colors hover:bg-muted/50"
                    >
                        <td class="p-2 align-middle font-medium">
                            {{ church.name }}
                        </td>

                        <td class="p-2 align-middle text-sm text-muted-foreground">
                            {{ church.code || '—' }}
                        </td>

                        <td class="p-2 align-middle text-right">
                            <Button v-if="can('lookups.update')" @click="editLocalChurch(church)" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('lookups.delete')" @click="openDeleteDialog(church)" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
                        </td>
                    </tr>

                    <tr v-if="!loading && !localChurches.length">
                        <td
                            colspan="3"
                            class="p-6 text-center text-sm text-muted-foreground"
                        >
                            No local churches found.
                        </td>
                    </tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

    <localChurchDialog
        :open="dialogOpen"
        :localChurch="selectedLocalChurch"
        @saved="fetchLocalChurches()"
        @close="closeDialog"
    />

    <ConfirmDialog
        :open="deleteDialogOpen"
        title="Delete Local Church"
        :message="`Delete '${selectedLocalChurch?.name}'? This action cannot be undone.`"
        confirm-text="Delete"
        :loading="deleting"
        @close="deleteDialogOpen = false"
        @confirm="deleteLocalChurch"
/>
</template>