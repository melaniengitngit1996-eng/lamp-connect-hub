<script setup>
import { ref, watch, computed } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    testimonies: {
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
    title: '',
    content: '',
    is_featured: false,
})

const form = ref(defaultForm())

const isEditing = computed(() => !!props.testimonies)

watch(
    () => props.open,
    (open) => {
        if (!open) return

        errors.value = {}

        if (!props.testimonies) {
            Object.assign(form.value, defaultForm())
            return
        }

        Object.assign(form.value, {
            title: props.testimonies.title,
            content: props.testimonies.content,
            is_featured: props.testimonies.is_featured,
        })
    },
    { immediate: true }
)

const save = async () => {
    saving.value = true
    errors.value = {}

    try {
        if (props.testimonies) {
            await axios.put(
                `/api/testimonies/${props.testimonies.id}`,
                form.value
            )
        } else {
            await axios.post(
                '/api/testimonies',
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
        :title="isEditing ? 'Edit Testimony' : 'New Testimony'"
        @close="emit('close')"
    >
        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Name</label>
                <input 
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" 
                    v-model="form.title">
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Content</label>
                <textarea v-model="form.content" class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" rows="3" placeholder="Optional" spellcheck="false"></textarea>
            </div>

            <div class="flex items-center justify-between rounded-md border p-3">
                <div>
                    <label class="text-sm font-medium">
                        Featured
                    </label>

                    <p class="text-xs text-muted-foreground">
                        Highlight this composition.
                    </p>
                </div>

                <input
                    type="checkbox"
                    v-model="form.is_featured"
                    class="h-4 w-4 rounded border-input"
                />
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
                    {{ props.testimonies ? 'Save Changes' : 'Create Testimony' }}
                </Button>
            </div>
        </div>
    </Dialog>
</template>