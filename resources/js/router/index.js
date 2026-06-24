import { createRouter, createWebHistory } from 'vue-router'

import AppLayout from '../layouts/AppLayout.vue'
import LoginPage from '../pages/auth/LoginPage.vue'
import DashboardPage from '../pages/dashboard/DashboardPage.vue'
import DrivePage from '../pages/drive/DrivePage.vue'
import SharedFolderPage from '../pages/drive/SharedFolderPage.vue'
import SharedFilePage from '../pages/drive/SharedFilePage.vue'

const routes = [
    {
        path: '/',
        component: AppLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'feed',
                component: DashboardPage,
            },
            {
                path: 'drive',
                name: 'drive',
                component: DrivePage,
            },
        ],
    },
    {
        path: '/shared/folders/:token',
        component: SharedFolderPage,
        meta: {
            requiresAuth: true,
        },
    },

    {
        path: '/shared/files/:token',
        component: SharedFilePage,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/login',
        name: 'login',
        component: LoginPage,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

async function getUser() {
    try {
        const res = await fetch('/me', {
            credentials: 'include',
            headers: { 'Accept': 'application/json' },
        })

        if (!res.ok) return null

        return await res.json()
    } catch {
        return null
    }
}

// Route Guard
router.beforeEach(async (to) => {
    const res = await fetch('/me', {
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
        },
    })

    const loggedIn = res.ok

    if (to.name !== 'login' && !loggedIn) {
        return { name: 'login' }
    }

    if (to.name === 'login' && loggedIn) {
        return { name: 'dashboard' }
    }

    return true
})

export default router