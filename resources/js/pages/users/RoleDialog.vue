<script setup>
import { ref, watch, computed, onMounted } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    role: {
        type: Object,
        default: null,
    },
})

const form = ref({
    name: '',
    description: '',
    permissions: [],
})

const saving = ref(false)

const permissions = ref([])
const isEditing = computed(() => !!props.role)
const fetchPermissions = async () => {
    const { data } = await axios.get('/api/permissions')

    permissions.value = data
}

watch(
    () => props.role,
    (role) => {
        if (role) {
            form.value = {
                name: role.name,
                description: role.description,
                permissions: role.permissions.map(p => p.name),
            }
        } else {
            form.value = {
                name: '',
                description: '',
                permissions: [],
            }
        }
    },
    { immediate: true }
)

const emit = defineEmits([
    'close',
    'saved',
])

const save = async () => {
    saving.value = true

    try {
        if (props.role) {
            await axios.put(
                `/api/roles/${props.role.id}`,
                form.value
            )
        } else {
            await axios.post(
                '/api/roles',
                form.value
            )
        }
    } finally {
        saving.value = false
    }

    emit('saved')
    emit('close')
}

onMounted(fetchPermissions)

const groupedPermissions = computed(() => {
    return permissions.value.reduce((groups, permission) => {
        if (!groups[permission.module]) {
            groups[permission.module] = []
        }

        groups[permission.module].push(permission)

        return groups
    }, {})
})
</script>

<template>
    <Dialog
        :open="open"
        :title="isEditing ? 'Edit Role' : 'New Role'"
        @close="emit('close')"
    >
        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Name</label>
                <input 
                    v-model="form.name"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Description</label>
                <textarea
                    v-model="form.description"
                    class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"></textarea>
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Permissions</label>
                <div class="mt-2 max-h-80 space-y-4 overflow-y-auto rounded-md border p-3">
                    <div v-for="(group, module) in groupedPermissions" :key="module">
                            <h4 class="mb-3 text-sm font-semibold">
                                {{ module }}
                            </h4>

                            <div class="space-y-2">

                                <label
                                    v-for="permission in group"
                                    :key="permission.id"
                                    class="flex items-start gap-2"
                                >
                                    <input
                                        type="checkbox"
                                        :value="permission.name"
                                        v-model="form.permissions"
                                        class="mt-2"
                                    >

                                    <div>
                                        <div class="text-sm">
                                            {{ permission.action }}
                                        </div>

                                        <div class="text-xs text-muted-foreground font-mono">
                                            {{ permission.name }}
                                        </div>
                                    </div>

                                </label>

                            </div>
                        </div>

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
                    {{ props.role ? 'Save Changes' : 'Create Role' }}
                </Button>
            </div>
        </div>
    </Dialog>
</template>