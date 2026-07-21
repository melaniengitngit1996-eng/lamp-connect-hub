<script setup>
import { ref, watch, computed, onMounted } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const localChurches = ref([])
const ministries = ref([])
const clusters = ref([])
const roles = ref([])
const errors = ref({})
const saving = ref(false)
const initializing = ref(false)

const defaultForm = () => ({
    name: '',
    email: '',
    local_church_id: null,
    ministry_ids: [],
    cluster_ids: [],
    role_ids: [],
    status: 'pending',
})

const form = ref(defaultForm())

const fetchChurches = async () => {
    const { data } = await axios.get('/api/local-churches')

    localChurches.value = data
}

const fetchRoles = async () => {
    const { data } = await axios.get('/api/roles')

    roles.value = data
}

const props = defineProps({
    open: Boolean,
    user: {
        type: Object,
        default: null,
    },
})

const loadChurchData = async (churchId) => {
    const [ministriesRes, clustersRes] = await Promise.all([
        axios.get(`/api/local-churches/${churchId}/ministries`),
        axios.get(`/api/local-churches/${churchId}/clusters`)
    ])

    ministries.value = ministriesRes.data
    clusters.value = clustersRes.data
}

watch(
    () => form.value.local_church_id,
    async (churchId) => {
        if (!churchId) return

        if (!initializing.value) {
            form.value.ministry_ids = []
            form.value.cluster_ids = []
        }

        await loadChurchData(churchId)
    }
)

watch(
    () => props.open,
    async (open) => {
        if (!open) return

        if (!props.user) {
            Object.assign(form.value, defaultForm())
            ministries.value = []
            clusters.value = []
            return
        }

        initializing.value = true

        Object.assign(form.value, defaultForm(), {
            name: props.user.name,
            email: props.user.email,
            local_church_id: props.user.local_church_id,
            ministry_ids: props.user.ministries?.map(m => m.id) ?? [],
            cluster_ids: props.user.clusters?.map(c => c.id) ?? [],
            role_ids: props.user.roles?.map(r => r.id) ?? [],
            status: props.user.status,
        })

        await loadChurchData(props.user.local_church_id)

        initializing.value = false
    },
    { immediate: true }
)

const isEditing = computed(() => !!props.user)

const emit = defineEmits([
    'close',
    'saved',
])

const selectedMinistries = computed(() =>
    ministries.value
        .filter(m => form.value.ministry_ids.includes(m.id))
        .map(m => m.name)
)

const selectedClusters = computed(() =>
    clusters.value
        .filter(m => form.value.cluster_ids.includes(m.id))
        .map(m => m.name)
)

const save = async () => {
    saving.value = true
    errors.value = {}

    try {
        if (props.user) {
            await axios.put(
                `/api/users/${props.user.id}`,
                form.value
            )
        } else {
            await axios.post(
                '/api/users',
                form.value
            )
        }

        emit('saved')
        emit('close')
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors
        }

        throw error
    } finally {
        saving.value = false
    }
}

onMounted(async () => {
    await Promise.all([
        fetchChurches(),
        fetchRoles(),
    ])
})
</script>

<template>
    <Dialog
        :open="open"
        :title="isEditing ? 'Edit User' : 'New User'"
        @close="emit('close')"
    >
        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Display name</label>
                <input 
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" 
                    v-model="form.name">
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    Local church
                </label>
                <select
                    v-model="form.local_church_id"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                >
                    <option :value="null">
                        Select local church
                    </option>

                    <option
                        v-for="church in localChurches"
                        :key="church.id"
                        :value="church.id"
                    >
                        {{ church.name }}
                    </option>
                </select>
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    Ministries
                </label>

                <details class="relative mt-2">
                    <summary
                        class="list-none inline-flex items-center gap-2 whitespace-nowrap rounded-md text-sm cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-full justify-between font-normal"
                    >
                        <span class="truncate text-left">
                            {{
                                form.ministry_ids.length
                                    ? `${form.ministry_ids.length} selected`
                                    : 'Select ministries'
                            }}
                        </span>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-chevron-down h-4 w-4 opacity-60"
                        >
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </summary>

                    <div
                        class="absolute z-50 mt-2 w-full rounded-md border bg-popover p-2 shadow-md max-h-60 overflow-y-auto"
                    >
                        <label
                            v-for="ministry in ministries"
                            :key="ministry.id"
                            class="flex items-center gap-2 rounded px-2 py-2 hover:bg-accent cursor-pointer"
                        >
                            <input
                                v-model="form.ministry_ids"
                                :value="ministry.id"
                                type="checkbox"
                                class="h-4 w-4"
                            >

                            <span class="text-sm">
                                {{ ministry.name }} <small class="text-[10px] text-muted-foreground">{{ ministry?.local_church?.name ?? 'National' }}</small>
                            </span>
                        </label>

                        <div
                            v-if="!ministries.length"
                            class="px-2 py-3 text-sm text-muted-foreground"
                        >
                            No ministries available.
                        </div>
                    </div>
                </details>

                <div
                    v-if="form.ministry_ids.length"
                    class="flex flex-wrap gap-1 mt-2"
                >
                    <div
                        v-for="ministry in ministries.filter(m => form.ministry_ids.includes(m.id))"
                        :key="ministry.id"
                        class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 gap-1"
                    >
                        {{ ministry.name }}

                        <button
                            type="button"
                            class="ml-1 text-muted-foreground hover:text-foreground"
                            :aria-label="`Remove ${ministry.name}`"
                            @click.stop="
                                form.ministry_ids = form.ministry_ids.filter(
                                    id => id !== ministry.id
                                )
                            "
                        >
                            ×
                        </button>
                    </div>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    Cluster Groups
                </label>

                <details class="relative mt-2">
                    <summary
                        class="list-none inline-flex items-center gap-2 whitespace-nowrap rounded-md text-sm cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-full justify-between font-normal"
                    >
                        <span class="truncate text-left">
                            {{
                                form.cluster_ids.length
                                    ? `${form.cluster_ids.length} selected`
                                    : 'Select cluster groups'
                            }}
                        </span>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-chevron-down h-4 w-4 opacity-60"
                        >
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </summary>

                    <div
                        class="absolute z-50 mt-2 w-full rounded-md border bg-popover p-2 shadow-md max-h-60 overflow-y-auto"
                    >
                        <label
                            v-for="cluster in clusters"
                            :key="cluster.id"
                            class="flex items-center gap-2 rounded px-2 py-2 hover:bg-accent cursor-pointer"
                        >
                            <input
                                v-model="form.cluster_ids"
                                :value="cluster.id"
                                type="checkbox"
                                class="h-4 w-4"
                            >

                            <span class="text-sm">
                                {{ cluster.name }}
                            </span>
                        </label>

                        <div
                            v-if="!clusters.length"
                            class="px-2 py-3 text-sm text-muted-foreground"
                        >
                            No cluster groups available.
                        </div>
                    </div>
                </details>

                <div
                    v-if="form.cluster_ids.length"
                    class="flex flex-wrap gap-1 mt-2"
                >
                    <div
                        v-for="cluster in clusters.filter(m => form.cluster_ids.includes(m.id))"
                        :key="cluster.id"
                        class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 gap-1"
                    >
                        {{ cluster.name }}

                        <button
                            type="button"
                            class="ml-1 text-muted-foreground hover:text-foreground"
                            :aria-label="`Remove ${cluster.name}`"
                            @click.stop="
                                form.cluster_ids = form.cluster_ids.filter(
                                    id => id !== cluster.id
                                )
                            "
                        >
                            ×
                        </button>
                    </div>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Status</label>
                <div class="flex gap-2 mt-2">
                    <button
                        v-for="status in ['pending','approved','rejected']"
                        :key="status"
                        type="button"
                        @click="form.status = status"
                        :class="[
                            form.status === status
                                ? 'bg-primary text-primary-foreground shadow'
                                : 'border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground',
                            'inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 h-8 rounded-md px-3 text-xs'
                        ]"
                    >
                        {{ status }}
                    </button>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Roles</label>
                <div class="mt-2 space-y-2 max-h-52 overflow-y-auto border rounded-md p-3">
                    <label
                        v-for="role in roles"
                        :key="role.id"
                        class="flex items-center gap-2 text-sm"
                    >
                        <button
                            type="button"
                            role="checkbox"
                            :aria-checked="form.role_ids.includes(role.id)"
                            :data-state="form.role_ids.includes(role.id) ? 'checked' : 'unchecked'"
                            :class="[
                                'grid place-content-center h-4 w-4 shrink-0 rounded-sm border border-primary shadow cursor-pointer',
                                form.role_ids.includes(role.id)
                                    ? 'bg-primary text-primary-foreground'
                                    : ''
                            ]"
                            @click.prevent="
                                form.role_ids.includes(role.id)
                                    ? form.role_ids = form.role_ids.filter(id => id !== role.id)
                                    : form.role_ids.push(role.id)
                            "
                        >
                            <span
                                v-if="form.role_ids.includes(role.id)"
                                class="grid place-content-center text-current"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path d="M20 6L9 17l-5-5"/>
                                </svg>
                            </span>
                        </button>

                        <span>{{ role.name }}</span>

                        <div
                            v-if="role.is_system"
                            class="inline-flex items-center rounded-md border px-2.5 py-0.5 font-semibold border-transparent bg-secondary text-secondary-foreground text-[10px]"
                        >
                            System
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                <Button
                    type="plain"
                    @click="emit('close')"
                >
                    Cancel
                </Button>

                <Button
                    type="primary"
                    :disabled="saving"
                    @click="save"
                >
                    {{ props.user ? 'Save Changes' : 'Create User' }}
                </Button>
            </div>
        </div>
    </Dialog>
</template>