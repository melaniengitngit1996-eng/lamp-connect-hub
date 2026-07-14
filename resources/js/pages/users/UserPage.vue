<script setup>
import { useAuth } from '../../stores/auth'

import { ref, onMounted } from 'vue'

const { can } = useAuth();

import UsersCard from '../../pages/users/UsersCard.vue'
import RolesCard from '../../pages/users/RolesCard.vue'

const activeTab = ref('users')
</script>

<template>
    <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">
        <header>
            <h1 class="font-display text-3xl flex items-center gap-2">
                Users Management
            </h1>
            <p class="text-sm text-muted-foreground">Manage users, roles, and permissions across the portal.</p>
        </header>
        <div dir="ltr" data-orientation="horizontal">
            <div 
                v-if="can('roles.view')"
                role="tablist" 
                aria-orientation="horizontal" 
                class="inline-flex h-9 items-center justify-center rounded-lg bg-muted p-1 text-muted-foreground" 
                tabindex="0" 
                data-orientation="horizontal" 
                style="outline: none;"
            >
                <button
                    @click="activeTab = 'users'"
                    :class="[
                        'px-3 py-1 text-sm rounded-md',
                        activeTab === 'users'
                            ? 'bg-background shadow text-foreground'
                            : 'text-muted-foreground'
                    ]"
                >
                    Users
                </button>
                <button
                    @click="activeTab = 'roles'"
                    :class="[
                        'px-3 py-1 text-sm rounded-md',
                        activeTab === 'roles'
                            ? 'bg-background shadow text-foreground'
                            : 'text-muted-foreground'
                    ]"
                >
                    Roles
                </button>
            </div>
            
            
            <div data-state="active" v-if="activeTab === 'users'" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4" style="">
                <UsersCard />
            </div>
            <div data-state="inactive" v-if="activeTab === 'roles'" class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-4">
                <RolesCard />
            </div>
        </div>
    </div>
</template>