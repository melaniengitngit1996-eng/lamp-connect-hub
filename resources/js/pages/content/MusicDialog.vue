<script setup>
import { ref, watch, computed } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    compositions: {
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
    description: '',
    type: 'song',
    file: null,
    status: 'draft',
    is_featured: false,
    published_at: '',
})

const form = ref(defaultForm())

const isEditing = computed(() => !!props.compositions)

watch(
    () => props.open,
    (open) => {
        if (!open) return

        errors.value = {}

        if (!props.compositions) {
            Object.assign(form.value, defaultForm())
            return
        }

        Object.assign(form.value, {
            title: props.compositions.title,
            description: props.compositions.description,
            type: props.compositions.type,
            status: props.compositions.status,
            is_featured: props.compositions.is_featured,
            published_at: props.compositions.published_at
                ? props.compositions.published_at.substring(0, 16)
                : '',
            file: null,
        })

        console.log(form.value);
    },
    { immediate: true }
)

const save = async () => {
    saving.value = true
    errors.value = {}

    const payload = new FormData()

    payload.append('title', form.value.title)
    payload.append('description', form.value.description ?? '')
    payload.append('type', form.value.type)
    payload.append('status', form.value.status)
    payload.append('is_featured', form.value.is_featured ? 1 : 0)

    if (form.value.published_at) {
        payload.append('published_at', form.value.published_at)
    }

    if (form.value.file instanceof File) {
        payload.append('file', form.value.file)
    }

    try {
        if (props.compositions) {
            payload.append('_method', 'PUT')

            await axios.post(
                `/api/compositions/${props.compositions.id}`,
                payload,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                }
            )
        } else {
            await axios.post(
                '/api/compositions',
                payload,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                }
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
        :title="isEditing ? 'Edit Composition' : 'New Composition'"
        @close="emit('close')"
    >
        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Title</label>
                <input 
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" 
                    v-model="form.title">
            </div>
            <div>
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Description</label>
                <textarea v-model="form.description" class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" rows="3" placeholder="Optional" spellcheck="false"></textarea>
            </div>

            <div>
                <label class="text-sm font-medium leading-none">Type</label>

                <select
                    v-model="form.type"
                    class="mt-2 flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                >
                    <option value="song">Song</option>
                    <option value="setlist">Setlist</option>
                    <option value="chord_chart">Chord Chart</option>
                    <option value="lead_sheet">Lead Sheet</option>
                    <option value="lyrics">Lyrics</option>
                    <option value="sheet_music">Sheet Music</option>
                    <option value="audio">Audio</option>
                    <option value="backing_track">Backing Track</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium leading-none">File</label>

                <input
                    type="file"
                    class="mt-2 block w-full text-sm"
                    @change="form.file = $event.target.files?.[0] ?? null"
                />

                <p
                    v-if="props.compositions?.file_name"
                    class="mt-2 text-xs text-muted-foreground"
                >
                    Current file:
                    <strong>{{ props.compositions.file_name }}</strong>
                </p>
            </div>

            <div>
                <label class="text-sm font-medium leading-none">Status</label>

                <select
                    v-model="form.status"
                    class="mt-2 flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                >
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium leading-none">
                    Published At
                </label>

                <input
                    type="datetime-local"
                    v-model="form.published_at"
                    class="mt-2 flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                />
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
                    {{ props.compositions ? 'Save Changes' : 'Create Composition' }}
                </Button>
            </div>
        </div>
    </Dialog>
</template>