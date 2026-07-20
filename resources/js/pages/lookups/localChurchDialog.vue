<script setup>
import { ref, watch, computed } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    localChurch: {
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

const defaultForm = () => ({
    name: '',
    code: '',
})

const form = ref(defaultForm())

const isEditing = computed(() => !!props.localChurch)

watch(
    () => props.open,
    (open) => {
        if (!open) return

        errors.value = {}

        if (!props.localChurch) {
            Object.assign(form.value, defaultForm())
            return
        }

        Object.assign(form.value, {
            name: props.localChurch.name,
            code: props.localChurch.code,
        })
    },
    { immediate: true }
)

const save = async () => {
    saving.value = true
    errors.value = {}

    try {
        if (props.localChurch) {
            await axios.put(
                `/api/local-churches/${props.localChurch.id}`,
                form.value
            )
        } else {
            await axios.post(
                '/api/local-churches',
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
</script>

<template>
    <Dialog
        :open="open"
        :title="isEditing ? 'Edit Local Church' : 'New Local Church'"
        @close="emit('close')"
    >
        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Name</label>
                <input 
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" 
                    v-model="form.name">
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Code</label>
                <input 
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" 
                    v-model="form.code">
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
                    {{ props.localChurch ? 'Save Changes' : 'Create Local Church' }}
                </Button>
            </div>
        </div>
    </Dialog>
</template>