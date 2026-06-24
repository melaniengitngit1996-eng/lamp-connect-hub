<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'

import DownloadIcon from '../../icons/DownloadIcon.vue'
import Button from '../../components/Button.vue'

const route = useRoute()

const loading = ref(true)

const file = ref(null)

const uploadedAt = computed(() => {
    if (!file.value) {
        return ''
    }

    return new Date(
        file.value.created_at
    ).toLocaleString()

})

const icon = computed(() => {
    const extension = file.value?.extension

    switch (extension) {

        case 'pdf':
            return '📕'

        case 'xlsx':
        case 'xls':
            return '📊'

        case 'doc':
        case 'docx':
            return '📘'

        case 'png':
        case 'jpg':
        case 'jpeg':
            return '🖼️'

        default:
            return '📄'
    }

})

onMounted(async () => {
    try {
        const { data } = await axios.get(
            `/api/shared/files/${route.params.token}`
        )

        file.value = data.file
    } finally {
        loading.value = false
    }
})

const isImage = computed(() => {
    return file.value?.mime_type?.startsWith('image/')
})

const isPdf = computed(() => {
    return file.value?.mime_type === 'application/pdf'
})
</script>

<template>
    <div class="max-w-4xl mx-auto py-12 px-6">

        <div
            v-if="loading"
            class="text-center text-muted-foreground"
        >
            Loading...
        </div>

        <div
            v-else-if="file"
            class="bg-card border rounded-xl p-8"
        >

            <div class="flex gap-4 items-center px-6 py-4">

                <div
                    class="h-14 w-14 rounded-lg bg-muted flex items-center justify-center"
                >
                    {{ icon }}
                </div>

                <div>
                    <h1 class="text-xl font-semibold">
                        {{ file.original_name }}
                    </h1>

                    <p class="text-sm text-muted-foreground">
                        Shared by {{ file.uploader?.name }}
                    </p>
                </div>

                <a
                    :href="file.url"
                    target="_blank"
                    class="ml-auto"
                    download
                >
                    <Button type="primary">
                        <DownloadIcon />
                    </Button>
                </a>

            </div>

            <div class="border-t mt-8 pt-6 px-6 py-4">

                <div class="grid grid-cols-2 gap-4 text-sm">

                    <div>
                        <div class="text-muted-foreground">
                            File Name
                        </div>

                        <div>
                            {{ file.original_name }}
                        </div>
                    </div>

                    <div>
                        <div class="text-muted-foreground">
                            Uploaded
                        </div>

                        <div>
                            {{ uploadedAt }}
                        </div>
                    </div>

                </div>

            </div>

            <div
                v-if="isImage"
                class="pt-4"
            >
                <img
                    :src="file.url"
                    :alt="file.original_name"
                    class="mx-auto max-h-[75vh]"
                >
            </div>

            <div
                v-else-if="isPdf"
                class="pt-4"
            >
                <iframe
                    :src="file.url"
                    class="border h-[75vh] rounded w-full"
                />
            </div>

            <div
                v-else
                class="border p-16 rounded-lg text-center"
            >
                <p class="text-muted-foreground">
                    Preview not available for this file type.
                </p>
            </div>
        </div>

    </div>
</template>