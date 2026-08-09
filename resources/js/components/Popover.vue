<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const open = ref(false)

const root = ref(null)
const trigger = ref(null)
const content = ref(null)

const toggle = () => {
    open.value = !open.value
}

const close = () => {
    open.value = false
}

const onPointerDown = (event) => {
    if (!root.value?.contains(event.target)) {
        close()
    }
}

const onFocusIn = (event) => {
    if (!root.value?.contains(event.target)) {
        close()
    }
}

const onKeyDown = (event) => {
    if (event.key === 'Escape') {
        close()
        trigger.value?.focus()
    }
}

onMounted(() => {
    document.addEventListener('pointerdown', onPointerDown)
    document.addEventListener('focusin', onFocusIn)
    document.addEventListener('keydown', onKeyDown)
})

onBeforeUnmount(() => {
    document.removeEventListener('pointerdown', onPointerDown)
    document.removeEventListener('focusin', onFocusIn)
    document.removeEventListener('keydown', onKeyDown)
})
</script>

<template>
    <div
        ref="root"
        class="relative inline-block"
    >
        <div
            ref="trigger"
            @click.stop="toggle"
        >
            <slot name="trigger" />
        </div>

        <Transition
            enter-active-class="transition duration-150"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-100"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="open"
                ref="content"
                class="absolute right-0 top-full z-50 mt-2 rounded-md border bg-popover p-1 shadow-lg"
                style="min-width: 120px;"
            >
                <slot
                    name="content"
                    :close="close"
                />
            </div>
        </Transition>
    </div>
</template>