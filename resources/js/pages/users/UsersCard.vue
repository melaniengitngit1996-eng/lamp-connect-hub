<script setup>
import { useAuth } from '../../stores/auth'

const { can } = useAuth();

import { ref, onMounted } from 'vue'

import Button from '@/components/Button.vue'
import TrashIcon from '../../icons/TrashIcon.vue'
import PencilIcon from '../../icons/PencilIcon.vue'

import ConfirmDialog from '@/components/ConfirmDialog.vue'
import UserDialog from '../../pages/users/UserDialog.vue'

const loading = ref(false);
const users = ref([]);
const deleteDialogOpen = ref(false)
const selectedUser = ref(null)
const deleting = ref(false)
const dialogOpen = ref(false)

const openDeleteDialog = (user) => {
    selectedUser.value = user
    deleteDialogOpen.value = true
}

const editUser = (user) => {
    selectedUser.value = user
    dialogOpen.value = true
}

const createUser = () => {
    selectedUser.value = null
    dialogOpen.value = true
}

const closeDialog = () => {
    dialogOpen.value = false
    selectedUser.value = null
}

const fetchUsers = async () => {
    loading.value = true;

    try {
        const { data } = await axios.get('/api/users');

        users.value = data;
    } finally {
        loading.value = false;
    }
};

const deleteUser = async () => {
    deleting.value = true

    try {
        await axios.delete(`/api/users/${selectedUser.value.id}`)

        await fetchUsers()

        deleteDialogOpen.value = false
        selectedUser.value = null
    } catch (error) {
        alert(error.response?.data?.message ?? 'Unable to delete user.')
    } finally {
        deleting.value = false
    }
}

onMounted(fetchUsers);
</script>

<template>
    <div class="rounded-xl border bg-card text-card-foreground shadow p-0">
        <div class="flex justify-between items-center p-4 border-b">
            <div class="text-sm text-muted-foreground">{{ users?.length }} user(s)</div>
            <button v-if="can('users.create')" @click="createUser" class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-1" aria-hidden="true">
                    <path d="M5 12h14"></path>
                    <path d="M12 5v14"></path>
                </svg>
                New user
            </button>
        </div>
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&amp;_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">User</th>
                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">Church</th>
                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">Status</th>
                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">Roles</th>
                        <th v-if="can('users.update') || can('users.delete')" class="h-10 px-2 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="[&amp;_tr:last-child]:border-0">
                    <tr v-for="user in users" :key="user.id" class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">
                            <div class="flex items-center gap-3">
                                <span class="relative flex shrink-0 overflow-hidden rounded-full h-8 w-8"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted">{{ user.initials }}</span></span>
                                <div>
                                    <div class="text-sm font-medium">{{ user.name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ user.email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-sm">{{ user.local_church.name ?? '—' }}</td>
                        <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">
                            <div
                                class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors"
                                :class="{
                                    'border-transparent bg-primary text-primary-foreground shadow hover:bg-primary/80':
                                        user.status === 'approved',

                                    'border-transparent bg-destructive text-destructive-foreground shadow hover:bg-destructive/80':
                                        user.status === 'rejected',

                                    'border-input bg-muted text-muted-foreground':
                                        user.status === 'pending',
                                }"
                            >
                                {{ user.status }}
                            </div>
                        </td>
                        <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">
                            <div class="flex flex-wrap gap-1">
                                <template v-if="user.roles.length">
                                    <template
                                        v-for="(role, index) in user.roles"
                                        :key="role.id"
                                    >
                                        <div
                                            v-if="index < 2"
                                            class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-[10px] font-semibold"
                                        >
                                            {{ role.name }}
                                        </div>
                                    </template>

                                    <div
                                        v-if="user.roles.length > 2"
                                        class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-[10px] font-semibold"
                                    >
                                        +{{ user.roles.length - 2 }}
                                    </div>
                                </template>

                                <span
                                    v-else
                                    class="text-xs text-muted-foreground"
                                >
                                    None
                                </span>
                            </div>
                        </td>
                        <td v-if="can('users.update') || can('users.delete')" class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right">
                            <Button v-if="can('users.update')" @click="editUser(user)" type="icon">
                                <PencilIcon />
                            </button>
                            <Button v-if="can('users.delete')" @click="openDeleteDialog(user)" type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <UserDialog
        :open="dialogOpen"
        :user="selectedUser"
        @saved="fetchUsers()"
        @close="closeDialog"
    />

    <ConfirmDialog
        :open="deleteDialogOpen"
        title="Delete User"
        :message="`Delete '${selectedUser?.name}'? This action cannot be undone.`"
        confirm-text="Delete"
        :loading="deleting"
        @close="deleteDialogOpen = false"
        @confirm="deleteUser"
/>
</template>