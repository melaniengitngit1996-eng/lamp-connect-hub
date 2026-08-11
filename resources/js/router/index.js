import { createRouter, createWebHistory } from 'vue-router'

import { useAuth } from '../stores/auth'

import AppLayout from '../layouts/AppLayout.vue'
import LoginPage from '../pages/auth/LoginPage.vue'
import DashboardPage from '../pages/dashboard/DashboardPage.vue'
import DrivePage from '../pages/drive/DrivePage.vue'
import ChatPage from '../pages/chat/ChatPage.vue'
import SharedFolderPage from '../pages/drive/SharedFolderPage.vue'
import SharedFilePage from '../pages/drive/SharedFilePage.vue'
import MemberPage from '../pages/members/MemberPage.vue'
import SignupInvitationPage from '../pages/auth/SignupInvitationPage.vue'
import UserPage from '../pages/users/UserPage.vue'
import NotFoundPage from '../pages/auth/403Page.vue'
import LookupPage from '../pages/lookups/LookupPage.vue'
import ContentPage from '../pages/content/ContentPage.vue'
import SettingsPage from '../pages/settings/SettingsPage.vue'

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
                meta: {
                    permission: 'drive.view',
                },
            },
            {
                path: 'chat',
                name: 'chat',
                component: ChatPage,
                meta: {
                    permission: 'chat.view',
                },
            },
            {
                path: 'signups',
                name: 'signups',
                component: MemberPage,
                meta: {
                    permission: 'members.view',
                },
            },
            {
                path: 'users',
                name: 'users',
                component: UserPage,
                meta: {
                    permission: 'users.view',
                },
            },
            {
                path: 'content',
                name: 'content',
                component: ContentPage,
                meta: {
                    permission: 'content.view',
                },
            },
            {
                path: 'lookups',
                name: 'lookups',
                component: LookupPage,
                meta: {
                    permission: 'lookups.view',
                },
            },
            {
                path: 'settings',
                name: 'settings',
                component: SettingsPage,
                meta: {
                    permission: 'settings.update',
                },
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
        path: '/signup/:token',
        component: SignupInvitationPage,
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: '/login',
        name: 'login',
        component: LoginPage,
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: '/403',
        name: '403',
        component: NotFoundPage,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach(async (to) => {
    const { user, fetchUser, can } = useAuth()

    if (!user.value) {
        await fetchUser()
    }

    if (to.meta.requiresAuth && !user.value) {
        return '/login'
    }

    if (to.meta.permission && !can(to.meta.permission)) {
        return '/403'
    }

    // allow navigation
    return true
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

router.beforeEach(async (to) => {
    const res = await fetch('/me', {
        credentials: 'include',
        headers: {
            Accept: 'application/json',
        },
    })

    const loggedIn = res.ok

    if (to.meta.requiresAuth === false) {
        return true
    }

    if (!loggedIn) {
        return { name: 'login' }
    }

    if (to.name === 'login') {
        return { name: 'dashboard' }
    }

    return true
});

export default router