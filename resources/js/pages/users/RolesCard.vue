<script setup>
import { ref, onMounted } from 'vue'

import RoleDialog from '../../pages/users/RoleDialog.vue'
import ConfirmDialog from '@/components/ConfirmDialog.vue'

const roles = ref([]);
const loading = ref(false);

const dialogOpen = ref(false)
const selectedRole = ref(null)

const deleting = ref(false)

const deleteDialogOpen = ref(false)

const createRole = () => {
    selectedRole.value = null
    dialogOpen.value = true
}

const editRole = (role) => {
    selectedRole.value = role
    dialogOpen.value = true
}

const closeDialog = () => {
    dialogOpen.value = false
    selectedRole.value = null
}

const openDeleteDialog = (role) => {
    selectedRole.value = role
    deleteDialogOpen.value = true
}

const fetchRoles = async () => {
    loading.value = true

    try {
        const { data } = await axios.get('/api/roles')
        roles.value = data
    } finally {
        loading.value = false
    }
}

const deleteRole = async () => {
    deleting.value = true

    try {
        await axios.delete(`/api/roles/${selectedRole.value.id}`)

        await fetchRoles()

        deleteDialogOpen.value = false
        selectedRole.value = null
    } catch (error) {
        alert(error.response?.data?.message ?? 'Unable to delete role.')
    } finally {
        deleting.value = false
    }
}

onMounted(fetchRoles);
</script>

<template>
<div class="rounded-xl border bg-card text-card-foreground shadow p-0">
    <div class="flex justify-between items-center p-4 border-b">
        <div class="text-sm text-muted-foreground">{{ roles.length }} role(s)</div>
        <button @click="createRole" class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-1" aria-hidden="true">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
            </svg>
            New role
        </button>
    </div>
    <div class="relative w-full overflow-auto">
        <table class="w-full caption-bottom text-sm">
            <thead class="[&amp;_tr]:border-b">
                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                    <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">Name</th>
                    <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">Description</th>
                    <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">Users</th>
                    <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">Permissions</th>
                    <th class="h-10 px-2 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="[&amp;_tr:last-child]:border-0">
                <tr v-for="role in roles"
                    :key="role.id" 
                    class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">{{ role.name }}</span>
                            <div v-if="role.is_system" class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 text-[10px]">system</div>
                        </div>
                    </td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm text-muted-foreground">{{ role.description || '—' }}</td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-center text-xs">{{ role.users_count || '—' }}</td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">
                        <div class="flex flex-wrap gap-1">
                            <template v-for="(permission, index) in role.permissions" :key="permission.id">
                                <div
                                    v-if="index < 5"
                                    class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-[10px] font-semibold"
                                >
                                    {{ permission.name }}
                                </div>
                            </template>

                            <div
                                v-if="role.permissions.length > 5"
                                class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-[10px] font-semibold"
                            >
                                +{{ role.permissions.length - 5 }}
                            </div>
                        </div>
                    </td>
                    <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right flex">
                        <button @click="editRole(role)" class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil h-4 w-4" aria-hidden="true">
                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                <path d="m15 5 4 4"></path>
                            </svg>
                        </button>
                        <button @click="openDeleteDialog(role)"
                                :disabled="role.is_system"
                                :class="[
                                    'inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 h-8 rounded-md px-3 text-xs',
                                    role.is_system
                                        ? 'opacity-50 cursor-not-allowed pointer-events-none'
                                        : 'cursor-pointer hover:bg-accent hover:text-accent-foreground'
                                ]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-4 w-4 text-destructive" aria-hidden="true">
                                <path d="M10 11v6"></path>
                                <path d="M14 11v6"></path>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                <path d="M3 6h18"></path>
                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<RoleDialog
    :open="dialogOpen"
    :role="selectedRole"
    @saved="fetchRoles()"
    @close="closeDialog"
/>

<ConfirmDialog
    :open="deleteDialogOpen"
    title="Delete Role"
    :message="`Delete '${selectedRole?.name}'? This action cannot be undone.`"
    confirm-text="Delete"
    :loading="deleting"
    @close="deleteDialogOpen = false"
    @confirm="deleteRole"
/>
</template>