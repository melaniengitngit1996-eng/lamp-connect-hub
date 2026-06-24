<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const loading = ref(true)

const folder = ref(null)
const files = ref([])

onMounted(async () => {
    try {
        const { data } = await axios.get(
            `/api/shared/folders/${route.params.token}`
        )

        folder.value = data.folder
        files.value = data.files ?? []
    } finally {
        loading.value = false
    }
})
</script>

<template>
    <div class="max-w-5xl mx-auto p-6">

        <h1 class="text-2xl font-semibold">
            {{ folder?.name }}
        </h1>

        <p class="text-muted-foreground">
            Shared by {{ folder?.owner?.name }}
        </p>

        <div class="mt-6">

            <div
                v-for="file in files"
                :key="file.id"
                class="border rounded-lg p-3 mb-2"
            >
                {{ file.original_name }}
            </div>

        </div>

    </div>
</template>