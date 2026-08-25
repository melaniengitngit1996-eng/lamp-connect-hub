<script setup>
import { ref, watch } from 'vue'

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
    open: Boolean,
    conversation: Object,
})

const emit = defineEmits([
    'close',
    'updated',
])

const name = ref('')
const saving = ref(false)
const errors = ref({})

watch(
    () => props.open,
    (open) => {
        if (!open) return

        name.value = props.conversation?.name ?? ''
        errors.value = {}
    }
)

const save = async () => {
    if (!name.value.trim() || saving.value) {
        return
    }

    saving.value = true
    errors.value = {}

    try {
        const { data } = await axios.patch(
            `/api/chat/conversations/${props.conversation.id}/name`,
            {
                name: name.value.trim(),
            }
        )

        emit('updated', data.data)

    } catch (error) {
        errors.value = error.response?.data?.errors ?? {}
    } finally {
        saving.value = false
    }
}
</script>

<template>
    <Dialog :open="open" title="Rename group chat" @close="emit('close')">
        <div class="space-y-4">

            <div class="space-y-1.5">
                <label for="group-name" class="text-sm font-medium">
                    Group name
                </label>

                <input id="group-name" v-model="name" type="text" maxlength="100"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm"
                    @keydown.enter.prevent="save" />

                <p v-if="errors.name" class="text-sm text-destructive">
                    {{ errors.name[0] }}
                </p>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                <Button type="plain" @click="emit('close')">
                    Cancel
                </Button>

                <Button type="primary" :disabled="saving || !name.trim()" @click="save">
                    {{ saving ? 'Saving...' : 'Save' }}
                </Button>
            </div>

        </div>
    </Dialog>
</template>