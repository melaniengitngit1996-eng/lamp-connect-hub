<script setup>
import { ref, watch } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    folder: Object,
})

const emit = defineEmits([
    'close',
    'deleted',
])

const deleteFolder = async () => {
    await fetch(`/api/drive/folders/${props.folder.id}`, {
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
        title="Delete folder"
        @close="emit('close')"
    >
        <div class="space-y-4">
            <p class="text-sm text-muted-foreground">
                Delete
                <span class="font-medium text-foreground">
                    "{{ folder?.name }}"
                </span>
                and everything inside?
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
                    @click="deleteFolder"
                >
                    Delete
                </Button>
            </div>
        </div>
    </Dialog>
</template>