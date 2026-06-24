<script setup>
import { ref, watch } from 'vue'

const emit = defineEmits([
    'close',
])

import Dialog from '@/components/Dialog.vue'
import Button from '@/components/Button.vue'
</script>

<template>
    <Dialog
        :open="open"
        title="New direct message"
        @close="emit('close')"
    >
        <div class="space-y-3">
            <div class="space-y-1.5">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Choose a person</label>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-2.5 top-2.5 h-3.5 w-3.5 text-muted-foreground" aria-hidden="true">
                        <path d="m21 21-4.34-4.34"></path>
                        <circle cx="11" cy="11" r="8"></circle>
                    </svg>
                    <input class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-8" placeholder="Search members" value="">
                </div>
            </div>
            <div class="max-h-64 overflow-y-auto border rounded-md divide-y"><label class="flex items-center gap-3 px-3 py-2 hover:bg-accent cursor-pointer"><button type="button" role="checkbox" aria-checked="false" data-state="unchecked" value="on" class="grid place-content-center peer h-4 w-4 shrink-0 rounded-sm border border-primary shadow cursor-pointer focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground"></button><span class="relative flex shrink-0 overflow-hidden rounded-full h-7 w-7"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-[10px]">DK</span></span><span class="text-sm">David Kim</span></label><label class="flex items-center gap-3 px-3 py-2 hover:bg-accent cursor-pointer"><button type="button" role="checkbox" aria-checked="false" data-state="unchecked" value="on" class="grid place-content-center peer h-4 w-4 shrink-0 rounded-sm border border-primary shadow cursor-pointer focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground"></button><span class="relative flex shrink-0 overflow-hidden rounded-full h-7 w-7"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-[10px]">PM</span></span><span class="text-sm">Pastor Mike</span></label><label class="flex items-center gap-3 px-3 py-2 hover:bg-accent cursor-pointer"><button type="button" role="checkbox" aria-checked="false" data-state="unchecked" value="on" class="grid place-content-center peer h-4 w-4 shrink-0 rounded-sm border border-primary shadow cursor-pointer focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground"></button><span class="relative flex shrink-0 overflow-hidden rounded-full h-7 w-7"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-[10px]">RB</span></span><span class="text-sm">Rachel Brooks</span></label><label class="flex items-center gap-3 px-3 py-2 hover:bg-accent cursor-pointer"><button type="button" role="checkbox" aria-checked="false" data-state="unchecked" value="on" class="grid place-content-center peer h-4 w-4 shrink-0 rounded-sm border border-primary shadow cursor-pointer focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground"></button><span class="relative flex shrink-0 overflow-hidden rounded-full h-7 w-7"><span class="flex h-full w-full items-center justify-center rounded-full bg-muted text-[10px]">SC</span></span><span class="text-sm">Sarah Chen</span></label></div>
        </div>
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
            <Button type="plain" @click="emit('close')">Cancel</Button>
            <Button type="primary" disabled="">Start Chat</Button>
        </div>
    </Dialog>
</template>
