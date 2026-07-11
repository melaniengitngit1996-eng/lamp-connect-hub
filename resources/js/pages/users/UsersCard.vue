<script setup>
import { ref, onMounted } from 'vue'

import Button from '@/components/Button.vue'
import TrashIcon from '../../icons/TrashIcon.vue'
import PencilIcon from '../../icons/PencilIcon.vue'

const loading = ref(false);
const users = ref([]);

const fetchUsers = async () => {
    loading.value = true;

    try {
        const { data } = await axios.get('/api/users');

        users.value = data;
    } finally {
        loading.value = false;
    }
};

onMounted(fetchUsers);
</script>

<template>
    <div class="rounded-xl border bg-card text-card-foreground shadow p-0">
        <div class="flex justify-between items-center p-4 border-b">
            <div class="text-sm text-muted-foreground">{{ users?.length }} user(s)</div>
        </div>
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&amp;_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">User</th>
                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">Church</th>
                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">Status</th>
                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px]">Roles</th>
                        <th class="h-10 px-2 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right">Actions</th>
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
                        <td class="p-2 align-middle [&amp;:has([role=checkbox])]:pr-0 [&amp;&gt;[role=checkbox]]:translate-y-[2px] text-right">
                            <Button type="icon">
                                <PencilIcon />
                            </button>
                            <Button type="icon">
                                <TrashIcon class="text-destructive" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>