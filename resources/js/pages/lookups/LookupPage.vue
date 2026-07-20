<script setup>
import { useAuth } from '../../stores/auth'

import { ref, onMounted } from 'vue'

const { can } = useAuth();

import MinistriesCard from '../../pages/lookups/MinistriesCard.vue'
import ClusterGroupsCard from '../../pages/lookups/ClusterGroupsCard.vue'
import LocalChurchesCard from '../../pages/lookups/LocalChurchesCard.vue';

const activeTab = ref('cluster_groups')
</script>

<template>
    <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">
        <header>
            <h1 class="font-display text-3xl flex items-center gap-2">
                Lookups
            </h1>
            <p class="text-sm text-muted-foreground">Manage local churches, and the ministries and cluster groups scoped under them.</p>
        </header>
        <div dir="ltr" data-orientation="horizontal">
            <div 
                role="tablist" 
                aria-orientation="horizontal" 
                class="inline-flex h-9 items-center justify-center rounded-lg bg-muted p-1 text-muted-foreground" 
                tabindex="0" 
                data-orientation="horizontal" 
                style="outline: none;"
            >
                <button
                    @click="activeTab = 'local_churches'"
                    :class="[
                        'px-3 py-1 text-sm rounded-md',
                        activeTab === 'local_churches'
                            ? 'bg-background shadow text-foreground'
                            : 'text-muted-foreground'
                    ]"
                >
                    Local Churches
                </button>
                <button
                    @click="activeTab = 'ministries'"
                    :class="[
                        'px-3 py-1 text-sm rounded-md',
                        activeTab === 'ministries'
                            ? 'bg-background shadow text-foreground'
                            : 'text-muted-foreground'
                    ]"
                >
                    Ministries
                </button>
                <button
                    @click="activeTab = 'cluster_groups'"
                    :class="[
                        'px-3 py-1 text-sm rounded-md',
                        activeTab === 'cluster_groups'
                            ? 'bg-background shadow text-foreground'
                            : 'text-muted-foreground'
                    ]"
                >
                    Cluster Groups
                </button>
            </div>
            
            
            <div data-state="active" v-if="activeTab === 'local_churches'" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4" style="">
                <LocalChurchesCard />
            </div>
            <div data-state="inactive" v-if="activeTab === 'ministries'" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4">
                <MinistriesCard />
            </div>
            <div data-state="inactive" v-if="activeTab === 'cluster_groups'" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4">
                <ClusterGroupsCard />
            </div>
        </div>
    </div>
</template>