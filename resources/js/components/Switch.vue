<script setup>
const props = defineProps({
    modelValue: Boolean,
    disabled: Boolean,
})

const emit = defineEmits([
    'update:modelValue',
    'change',
])

function toggle() {
    if (props.disabled) return

    const value = !props.modelValue

    emit('update:modelValue', value)
    emit('change', value)
}
</script>

<style scoped>
.bg-input {
    background-color: lab(93.0316% -1.09106 -3.56977);
}
</style>

<template>
    <button type="button" role="switch" :aria-checked="modelValue" :disabled="disabled" @click="toggle" :class="[
        'inline-flex h-5 w-9 items-center rounded-full transition-colors duration-200',
        modelValue ? 'bg-primary' : 'bg-input'
    ]">
        <span class="h-4 w-4 rounded-full bg-background shadow transition-all duration-200" :style="{
            transform: modelValue ? 'translateX(16px)' : 'translateX(0px)'
        }" />
    </button>
</template>