<script setup>
import { computed } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

import OpenIcon from '../../icons/OpenIcon.vue'
import DownloadIcon from '../../icons/DownloadIcon.vue'

const props = defineProps({
    open: Boolean,

    file: {
        type: Object,
        default: null,
    },
})

const emit = defineEmits([
    'close',
])

const isImage = (file) => {
    return file?.mime_type?.startsWith('image/')
}

const isPdf = (file) => {
    return file?.mime_type === 'application/pdf'
}

const isOfficeDocument = (file) => {
    return [
        'ppt',
        'pptx',
        'doc',
        'docx',
        'xls',
        'xlsx',
    ].includes(file.extension)
}

const officeViewerUrl = computed(() => {
    if (!props.file?.url) {
        return null
    }

    return `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(props.file.url)}`
})
</script>

<template>
    <Dialog
        :open="open"
        @close="emit('close')"
    >
        <div
            v-if="file"
            class="max-w-4xl overflow-hidden"
        >
            <div class="flex items-center justify-between gap-4 border-b px-6 py-4">
                <div class="min-w-0">
                    <h2 class="truncate text-lg font-semibold">
                        {{ file.original_name }}
                    </h2>

                    <p class="text-xs text-muted-foreground">
                        {{ file.size_human }}
                    </p>
                </div>

                <div class="flex gap-2">
                    <a
                        :href="file.url"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <Button type="plain">
                            <OpenIcon />
                        </Button>
                    </a>

                    <a
                        :href="file.url"
                        :download="file.original_name"
                    >
                        <Button type="plain">
                            <DownloadIcon />
                        </Button>
                    </a>
                </div>
            </div>

            <div
                class="bg-muted/40 min-h-[300px] max-h-[75vh] overflow-auto"
            >
                <img
                    v-if="isImage(file)"
                    :src="file.url"
                    :alt="file.original_name"
                    class="mx-auto max-h-[75vh]"
                >

                <iframe
                    v-else-if="isPdf(file)"
                    :src="file.url"
                    class="w-full h-[75vh]"
                />

                <iframe
                    v-else-if="isOfficeDocument(file)"
                    :src="officeViewerUrl"
                    class="w-full h-[75vh]"
                />

                <div
                    v-else
                    class="flex items-center justify-center h-[400px] text-sm text-muted-foreground"
                >
                    Preview not available for this file type.
                </div>
            </div>
        </div>
    </Dialog>
</template>