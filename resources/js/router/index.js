import { createRouter, createWebHistory } from 'vue-router'

import AppLayout from '../layouts/AppLayout.vue'
import LoginPage from '../pages/auth/LoginPage.vue'
import DashboardPage from '../pages/dashboard/DashboardPage.vue'

const routes = [
    {
        path: '/',
        component: AppLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'dashboard',
                component: DashboardPage,
            },
            // {
            //     path: 'members',
            //     name: 'members',
            //     component: MembersPage,
            // },
        ],
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