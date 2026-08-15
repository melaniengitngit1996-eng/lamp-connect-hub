<script setup>
import { useAuth } from '../../stores/auth'

const { can } = useAuth();

import { ref, onMounted, watch } from 'vue'

import Button from '@/components/Button.vue'
import TrashIcon from '../../icons/TrashIcon.vue'
import PencilIcon from '../../icons/PencilIcon.vue'

import ConfirmDialog from '@/components/ConfirmDialog.vue'
import clusterDialog from '../../pages/lookups/ClusterDialog.vue'

const loading = ref(false)
const clusters = ref([])
const localChurches = ref([])
const selectedChurchId = ref('')

const dialogOpen = ref(false)
const deleteDialogOpen = ref(false)

const selectedCluster = ref(null)
const deleting = ref(false)

const editCluster = (cluster) => {
	selectedCluster.value = cluster
	dialogOpen.value = true
}

const newCluster = () => {
	selectedCluster.value = null
	dialogOpen.value = true
}

const closeDialog = () => {
	dialogOpen.value = false
	selectedCluster.value = null
}

const openDeleteDialog = (cluster) => {
	selectedCluster.value = cluster
	deleteDialogOpen.value = true
}

const deleteCluster = async () => {
	deleting.value = true

	try {
		await axios.delete(
			`/api/clusters/${selectedCluster.value.id}`
		)

		await fetchClusters()

		deleteDialogOpen.value = false
		selectedCluster.value = null
	} catch (error) {
		alert(error.response?.data?.message ?? 'Unable to delete cluster.')
	} finally {
		deleting.value = false
	}
}

const fetchLocalChurches = async () => {
	const { data } = await axios.get('/api/local-churches')
	localChurches.value = data
}

const fetchClusters = async () => {
	loading.value = true

	try {
		const { data } = await axios.get('/api/clusters', {
			params: {
				local_church_id: selectedChurchId.value || undefined,
			},
		})

		clusters.value = data
	} finally {
		loading.value = false
	}
}

onMounted(async () => {
	await fetchLocalChurches()
	await fetchClusters()
})

watch(selectedChurchId, fetchClusters)
</script>

<template>
	<div data-state="active" data-orientation="horizontal" role="tabpanel"
		aria-labelledby="radix-_r_u_-trigger-cluster_groups" id="radix-_r_u_-content-cluster_groups" tabindex="0"
		class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4"
		data-tsd-source="/src/routes/_app.admin.lookups.tsx:65:9">
		<div class="rounded-xl border bg-card text-card-foreground shadow p-0"
			data-tsd-source="/src/routes/_app.admin.lookups.tsx:116:5">
			<div class="flex items-center justify-between p-4 border-b gap-3"
				data-tsd-source="/src/routes/_app.admin.lookups.tsx:117:7">
				<div class="flex items-center gap-3" data-tsd-source="/src/routes/_app.admin.lookups.tsx:118:9">
					<div class="text-sm text-muted-foreground">
						{{ clusters.length }} entr{{ clusters.length === 1 ? 'y' : 'ies' }}
					</div>
					<select v-model="selectedChurchId"
						class="flex h-8 w-56 rounded-md border border-input bg-transparent px-3 text-sm">
						<option value="">All churches</option>

						<option v-for="church in localChurches" :key="church.id" :value="church.id">
							{{ church.name }}
						</option>
					</select>
				</div>
				<Button v-if="can('lookups.create')" type="primary" @click="newCluster">
					New Cluster Group
				</Button>
			</div>
			<div class="relative w-full overflow-auto" data-tsd-source="/src/components/ui/table.tsx:7:5">
				<table class="w-full caption-bottom text-sm" data-tsd-source="/src/routes/_app.admin.lookups.tsx:144:9">
					<thead class="[&amp;_tr]:border-b" data-tsd-source="/src/routes/_app.admin.lookups.tsx:145:11">
						<tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted"
							data-tsd-source="/src/routes/_app.admin.lookups.tsx:146:13">
							<th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]"
								data-tsd-source="/src/routes/_app.admin.lookups.tsx:147:15">Name</th>
							<th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]"
								data-tsd-source="/src/routes/_app.admin.lookups.tsx:148:26">Local Church</th>
							<th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]"
								data-tsd-source="/src/routes/_app.admin.lookups.tsx:149:15">Description</th>
							<th class="h-10 px-2 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right"
								data-tsd-source="/src/routes/_app.admin.lookups.tsx:150:15">Actions</th>
						</tr>
					</thead>
					<tbody class="[&amp;_tr:last-child]:border-0"
						data-tsd-source="/src/routes/_app.admin.lookups.tsx:153:11">
						<tr v-for="clusterGroup in clusters" :key="clusterGroup.id"
							class="border-b transition-colors hover:bg-muted/50">
							<td class="p-2 font-medium">
								{{ clusterGroup.name }}
							</td>

							<td class="p-2 text-sm">
								{{ clusterGroup.local_church?.name ?? '—' }}
							</td>

							<td class="p-2 text-sm text-muted-foreground">
								{{ clusterGroup.description || '—' }}
							</td>

							<td class="p-2 text-right">
								<Button v-if="can('lookups.update')" type="icon" @click="editCluster(clusterGroup)">
									<PencilIcon />
								</Button>

								<Button v-if="can('lookups.delete')" type="icon"
									@click="openDeleteDialog(clusterGroup)">
									<TrashIcon class="text-destructive" />
								</Button>
							</td>
						</tr>
						<tr v-if="!loading && clusters.length === 0">
							<td colspan="4" class="p-8 text-center text-muted-foreground">
								No cluster groups found.
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<clusterDialog :open="dialogOpen" :cluster="selectedCluster" @saved="fetchClusters" @close="closeDialog" />

	<ConfirmDialog :open="deleteDialogOpen" title="Delete Cluster"
		:message="`Delete '${selectedCluster?.name}'? This action cannot be undone.`" confirm-text="Delete"
		:loading="deleting" @close="deleteDialogOpen = false" @confirm="deleteCluster" />
</template>