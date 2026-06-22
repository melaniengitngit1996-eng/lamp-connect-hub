<template>
<div v-if="open" data-state="open" class="no-margin bg-black/80 data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:animate-in data-[state=open]:fade-in-0 fixed inset-0 z-50" style="pointer-events: auto;" data-aria-hidden="true" aria-hidden="true"></div>
<div v-if="open" role="dialog" id="radix-_r_0_" aria-describedby="radix-_r_2_" aria-labelledby="radix-_r_1_" data-state="open" class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg duration-200 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 sm:rounded-lg" tabindex="-1" style="pointer-events: auto;">
	<div class="flex flex-col space-y-1.5 text-center sm:text-left">
		<h2 id="radix-_r_1_" class="text-lg font-semibold leading-none tracking-tight">{{ title }}</h2>
	</div>

    <slot />

    <button type="button" @click="$emit('close')" class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background cursor-pointer transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none data-[state=open]:bg-accent data-[state=open]:text-muted-foreground">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4" aria-hidden="true">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
        </svg>
        <span class="sr-only">Close</span>
    </button>
</div>
</template>

<script setup>
defineProps({
    open: Boolean,
    title: String
})

defineEmits(['close'])
</script>