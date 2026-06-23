<script setup>
import { ref, watch, computed } from 'vue'
import debounce from 'lodash/debounce'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,

    item: {
        type: Object,
        default: null,
    },

    type: {
        type: String,
        default: null,
    },
})

const loading = ref(false)

const owner = ref(null)
const permissions = ref([])

const search = ref('')
const results = ref([])
const selected = ref(null)
const role = ref('viewer')
const selecting = ref(false)

const endpoint = computed(() => {
    if (props.type === 'folder') {
        return `/api/drive/folders/${props.item.id}/permissions`
    }

    return `/api/drive/files/${props.item.id}/permissions`
})

const permissionIcon = (type) => {
    switch (type) {
        case 'user':
            return '👤'

        case 'church':
            return '⛪'

        case 'cluster':
            return '👥'

        case 'ministry':
            return '🎵'

        default:
            return '📄'
    }
}

const permissionLabel = (type) => {
    switch (type) {
        case 'user':
            return 'User'

        case 'church':
            return 'Local Church'

        case 'cluster':
            return 'Cluster'

        case 'ministry':
            return 'Ministry'

        default:
            return ''
    }
}

const dialogTitle = computed(() => {

    let name = props.item?.name ?? ''

    if (props.type === 'file') {
        name = props.item?.original_name ?? ''
    }

    const truncated =
        name.length > 30
            ? `${name.substring(0, 30)}...`
            : name

    return `Share "${truncated}"`
})

const performSearch = debounce(async () => {

    if (!search.value.trim()) {
        results.value = []
        return
    }

    const { data } = await axios.get(
        '/api/drive/share-search',
        {
            params: {
                q: search.value,
            },
        }
    )

    results.value = data

}, 300)

watch(
    () => props.open,
    async (open) => {

        if (!open || !props.item) {
            return
        }

        loading.value = true

        try {

            await loadPermissions()

        } finally {

            loading.value = false

        }
    }
)

const selectResult = (result) => {
    selecting.value = true
    selected.value = result
    search.value = result.label
    results.value = []
}

watch(search, (value) => {
    if (selecting.value) {
        selecting.value = false
        return
    }

    performSearch(value)
})

const addPermission = async () => {
    if (!selected.value) {
        return
    }

    await axios.post(
        endpoint.value,
        {
            principal_type: selected.value.type,
            principal_id: selected.value.id,
            role: role.value,
        }
    )

    await loadPermissions()

    selected.value = null
    search.value = ''
}

const loadPermissions = async () => {
    const { data } = await axios.get(
        endpoint.value
    )

    owner.value = data.owner
    permissions.value = data.permissions
}

const updatePermission = async (permission, role) => {
    try {
        await axios.patch(
            `/api/drive/folder-permissions/${permission.id}`, {
                role,
            }
        )

        permission.role = role
    } catch (error) {
        console.error(error)

    }
}

const emit = defineEmits([
    'close',
])
</script>
<template>
    <Dialog
        :open="open"
        @close="emit('close')"
        :title="dialogTitle"
    >
        <div class="flex gap-2">
            <div class="relative flex-1">
                <input 
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" 
                    placeholder="Search people, churches, clusters, ministries..." 
                    v-model="search"
                >
                <div
                    v-if="results.length"
                    class="absolute z-50 mt-1 w-full overflow-hidden rounded-lg border bg-background shadow-lg"
                >
                    <button
                        v-for="result in results"
                        :key="`${result.type}-${result.id}`"
                        @click="selectResult(result)"
                        class="flex w-full items-center gap-3 px-3 py-3 text-left hover:bg-muted transition-colors"
                    >
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-muted"
                        >
                            <span v-if="result.type === 'user'">👤</span>
                            <span v-else-if="result.type === 'church'">⛪</span>
                            <span v-else-if="result.type === 'cluster'">👥</span>
                            <span v-else-if="result.type === 'ministry'">🎵</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="truncate text-sm font-medium">
                                {{ result.label }}
                            </div>

                            <div class="truncate text-xs text-muted-foreground">
                                {{ result.subtitle }}
                            </div>
                        </div>
                    </button>
                </div>
            </div>
            
            <select v-model="role" class="flex h-9 items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background cursor-pointer data-[placeholder]:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 [&amp;&gt;span]:line-clamp-1 w-28">
                <option value="viewer">Viewer</option>
                <option value="contributor">Contributor</option>
                <option value="manager">Manager</option>
            </select>
            <Button type="primary" @click.stop="addPermission">Add</button>
        </div>
        <div>
            <div class="text-sm font-medium mb-2">People with access</div>
            <div class="space-y-2">
                <div v-if="owner" class="flex items-center gap-3">
                    <span class="relative flex shrink-0 overflow-hidden rounded-full h-8 w-8"><img class="aspect-square h-full w-full" src="https://lh3.googleusercontent.com/a/ACg8ocJvyec3OlWtfxjG3D5RG9t3tain8GGu6KW5yqx3Jg5V6uoylENt=s96-c"></span>

                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium truncate">
                            {{ owner.name }}
                        </div>

                        <div class="text-xs text-muted-foreground truncate">
                            {{ owner.email }}
                        </div>
                    </div>

                    <span class="text-sm text-muted-foreground">
                        Owner
                    </span>
                </div>
                <div
                    v-for="permission in permissions"
                    :key="permission.id"
                    class="flex items-center gap-3 py-2"
                >
                    <div
                        class="h-8 w-8 rounded-full bg-muted flex items-center justify-center"
                    >
                        {{ permissionIcon(permission.principal_type) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium truncate">
                            {{ permission.principal_name }}
                        </div>

                        <div class="text-xs text-muted-foreground">
                            {{ permissionLabel(permission.principal_type) }}
                        </div>
                    </div>

                    <select
                        @change="updatePermission(
                            permission,
                            $event.target.value
                        )"
                        class="text-sm border rounded-md px-2 py-1"
                        :value="permission.role"
                    >
                        <option value="viewer">Viewer</option>
                        <option value="contributor">Contributor</option>
                        <option value="manager">Manager</option>
                    </select>
                </div>
            </div>
        </div>
        <div>
            <div class="text-sm font-medium mb-2">General access</div>
            <div class="flex items-start gap-3 rounded-md border p-3">
                <div class="h-9 w-9 rounded-full bg-muted grid place-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock h-4 w-4" aria-hidden="true">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <select class="flex items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 shadow-sm ring-offset-background cursor-pointer data-[placeholder]:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 [&amp;&gt;span]:line-clamp-1 w-44 text-sm">
                            <option>Restricted</option>
                            <option>Anyone with the link</option>
                        </select>
                    </div>
                    <p class="text-xs text-muted-foreground mt-1">Only people with access can open.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between pt-2">
            <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-link h-4 w-4 mr-2" aria-hidden="true">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                </svg>
                Copy link
            </button>
        </div>
    </Dialog>
</template>