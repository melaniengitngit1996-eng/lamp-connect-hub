<script setup>
import { ref, watch } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    file: Object,
})

const emit = defineEmits([
    'close',
    'deleted',
])

const deleteFile = async () => {
    await fetch(`/api/drive/files/${props.file.id}`, {
        method: 'DELETE',
        credentials: 'include',
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                ?.content,
        },
    })

    emit('deleted')
    emit('close')
}
</script>

<template>
    <Dialog
        :open="open"
        title="Delete file"
        @close="emit('close')"
    >
        <div class="space-y-4">
            <p class="text-sm text-muted-foreground">
                Delete
                <span class="font-medium text-foreground">
                    "{{ file?.original_name }}"
                </span>
                ?
            </p>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                <Button
                    type="plain"
                    @click="emit('close')"
                >
                    Cancel
                </Button>

                <Button
                    type="destructive"
                    :disabled="loading"
                    @click="deleteFile"
                >
                    Delete
                </Button>
            </div>
        </div>
    </Dialog>
</template>