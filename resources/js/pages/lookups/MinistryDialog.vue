<script setup>
import { ref, watch, computed } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    ministry: {
        type: Object,
        default: null,
    },
})

const emit = defineEmits([
    'close',
    'saved',
])

const errors = ref({})
const saving = ref(false)
const localChurches = ref([])

const defaultForm = () => ({
    local_church_id: null,
    name: '',
    description: '',
})

const form = ref(defaultForm())

const isEditing = computed(() => !!props.ministry)

watch(
    () => props.open,
    (open) => {
        if (!open) return

        errors.value = {}

        if (!props.ministry) {
            Object.assign(form.value, defaultForm())
            return
        }

        Object.assign(form.value, {
            name: props.ministry.name,
            description: props.ministry.description,
        })
    },
    { immediate: true }
)

const save = async () => {
    saving.value = true
    errors.value = {}

    try {
        if (props.ministry) {
            await axios.put(
                `/api/ministries/${props.ministry.id}`,
                form.value
            )
        } else {
            await axios.post(
                '/api/ministries',
                form.value
            )
        }

        emit('saved')
        emit('close')
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors
            return
        }

        throw error
    } finally {
        saving.value = false
    }
}

const fetchLocalChurches = async () => {
    const { data } = await axios.get('/api/local-churches')

    localChurches.value = data
}

watch(
    () => props.open,
    async (open) => {
        if (!open) return

        errors.value = {}

        await fetchLocalChurches()

        if (!props.ministry) {
            Object.assign(form.value, defaultForm())
            return
        }

        Object.assign(form.value, {
            local_church_id: props.ministry.local_church_id,
            name: props.ministry.name,
            description: props.ministry.description,
        })
    },
    { immediate: true }
)
</script>

<template>
    <Dialog
        :open="open"
        :title="isEditing ? 'Edit Local Church' : 'New Local Church'"
        @close="emit('close')"
    >
        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium leading-none">
                    Local Church
                </label>

                <select
                    v-model="form.local_church_id"
                    class="mt-2 flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                >
                    <option :value="null" disabled>
                        Select a local church
                    </option>

                    <option
                        v-for="church in localChurches"
                        :key="church.id"
                        :value="church.id"
                    >
                        {{ church.name }}
                    </option>
                </select>

                <p
                    v-if="errors.local_church_id"
                    class="mt-1 text-sm text-destructive"
                >
                    {{ errors.local_church_id[0] }}
                </p>
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Name</label>
                <input 
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" 
                    v-model="form.name">
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Description</label>
                <textarea v-model="form.description" class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" rows="3" placeholder="Optional" spellcheck="false"></textarea>
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
                    {{ props.ministry ? 'Save Changes' : 'Create Local Church' }}
                </Button>
            </div>
        </div>
    </Dialog>
</template>