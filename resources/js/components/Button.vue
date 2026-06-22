<script setup>
import { computed } from 'vue'

const props = defineProps({
    type: {
        type: String,
        default: 'plain',
    },
})

const variants = {
    plain:
        'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors h-9 px-4 py-2 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground',

    primary:
        'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors h-9 px-4 py-2 bg-primary text-primary-foreground shadow hover:bg-primary/90',

    danger:
        'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium cursor-pointer transition-colors h-9 px-4 py-2 bg-red-600 text-white hover:bg-red-700',

    icon:
        'inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-accent',

    iconDanger:
        'inline-flex items-center justify-center h-9 w-9 rounded-md text-red-500 hover:bg-red-100',
}

const buttonClass = computed(() => {
    return variants[props.type] ?? variants.plain
})
</script>

<template>
    <button :class="buttonClass">
        <slot />
    </button>
</template>