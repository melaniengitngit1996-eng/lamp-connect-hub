<script setup>
import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

defineProps({
    open: Boolean,

    title: {
        type: String,
        default: 'Confirm',
    },

    message: {
        type: String,
        required: true,
    },

    confirmText: {
        type: String,
        default: 'Confirm',
    },

    cancelText: {
        type: String,
        default: 'Cancel',
    },

    confirmType: {
        type: String,
        default: 'destructive',
    },

    loading: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits([
    'close',
    'confirm',
])
</script>

<template>
    <Dialog
        :open="open"
        :title="title"
        @close="emit('close')"
    >
        <div class="space-y-6">
            <p class="text-sm text-muted-foreground">
                {{ message }}
            </p>

            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <Button
                    type="plain"
                    @click="emit('close')"
                >
                    {{ cancelText }}
                </Button>

                <Button
                    :type="confirmType"
                    :disabled="loading"
                    @click="emit('confirm')"
                >
                    {{ confirmText }}
                </Button>
            </div>
        </div>
    </Dialog>
</template>