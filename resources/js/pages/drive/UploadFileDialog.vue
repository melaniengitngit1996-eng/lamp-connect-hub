<script setup>
import { ref, watch } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,

    folderId: {
        type: Number,
        default: null,
    },

    folderVisibility: {
        type: String,
        default: null,
    },
})

const emit = defineEmits([
    'close',
    'uploaded',
])

const file = ref(null)
const loading = ref(false)
const fileInput = ref(null)
const errors = ref({})
const access = ref('private')
const handleFileChange = (event) => {
    file.value = event.target.files?.[0] ?? null
    errors.value = {}
}

const uploadFile = async () => {
    if (!file.value || loading.value) {
        return
    }

    loading.value = true
    errors.value = {}

    try {
        const formData = new FormData()

        formData.append('file', file.value)
        formData.append('folder_id', props.folderId ?? '')
        formData.append('visibility', access.value)

        const response = await fetch('/api/drive/files', {
            method: 'POST',
            credentials: 'include',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    ?.content,
            },
            body: formData,
        })

        if (response.status === 422) {
            const data = await response.json()

            errors.value = data.errors

            return
        }

        if (!response.ok) {
            errors.value = data.errors ?? {}
            return
        }

        file.value = null

        if (fileInput.value) {
            fileInput.value.value = ''
        }

        emit('uploaded')
        emit('close')
    } finally {
        loading.value = false
    }
}

watch(
    () => props.open,
    open => {
        if (!open) {
            return
        }

        errors.value = {}

        access.value = props.folderId
            ? 'inherit'
            : 'private'
    }
)

const close = () => {
    file.value = null

    if (fileInput.value) {
        fileInput.value = ''
    }

    emit('close')
}
</script>

<template>
    <Dialog :open="open" title="Upload file" @close="close">
        <div class="space-y-4">
            <input ref="fileInput" type="file" @change="handleFileChange" class="block w-full rounded-md border border-input bg-background p-2 text-sm text-muted-foreground
                    file:mr-3
                    file:rounded-md
                    file:border-0
                    file:bg-primary
                    file:px-3
                    file:py-2
                    file:text-sm
                    file:font-medium
                    file:text-primary-foreground
                    file:cursor-pointer
                    hover:file:opacity-90
                    cursor-pointer" />

            <p v-if="errors.file" class="text-sm text-destructive">
                {{ errors.file[0] }}
            </p>

            <div class="space-y-2">
                <label class="text-sm font-medium">
                    Access
                </label>

                <select v-model="access"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                    <option v-if="folderId" value="inherit">
                        Same access as folder
                    </option>

                    <option value="private">
                        Restricted
                    </option>

                    <option v-if="!folderVisibility || folderVisibility === 'public'" value="public">
                        Public
                    </option>

                    <option v-if="!folderVisibility || folderVisibility === 'link'" value="link">
                        Anyone with the link
                    </option>
                </select>

                <p v-if="errors.visibility" class="text-sm text-destructive">
                    {{ errors.visibility[0] }}
                </p>
            </div>

            <div class="flex justify-end gap-2">
                <Button type="plain" @click="close">
                    Cancel
                </Button>

                <Button type="primary" :disabled="!file || loading" @click="uploadFile">
                    {{ loading ? 'Uploading...' : 'Upload' }}
                </Button>
            </div>
        </div>
    </Dialog>
</template>