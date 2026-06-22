<script setup>
import { ref } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    folderId: {
        type: Number,
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

const handleFileChange = (event) => {
    file.value = event.target.files?.[0] ?? null
}

const uploadFile = async () => {
    if (!file.value) {
        return
    }

    loading.value = true

    try {
        const formData = new FormData()

        formData.append('file', file.value)
        formData.append('folder_id', props.folderId ?? '')

        await fetch('/api/drive/files', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    ?.content,
            },
            body: formData,
        })

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

const close = () => {
    file.value = null

    if (fileInput.value) {
        fileInput.value = ''
    }

    emit('close')
}
</script>

<template>
    <Dialog
        :open="open"
        title="Upload file"
        @close="close"
    >
        <div class="space-y-4">
            <input
                type="file"
                ref="fileInput"
                @change="handleFileChange"
            >

            <div
                v-if="file"
                class="text-sm text-muted-foreground"
            >
                {{ file.name }}
            </div>

            <div class="flex justify-end gap-2">
                <Button
                    type="plain"
                    @click="close"
                >
                    Cancel
                </Button>

                <Button
                    type="primary"
                    :disabled="!file || loading"
                    @click="uploadFile"
                >
                    Upload
                </Button>
            </div>
        </div>
    </Dialog>
</template>