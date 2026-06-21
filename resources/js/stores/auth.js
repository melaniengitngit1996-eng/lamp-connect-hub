import { ref } from 'vue'
import { useRouter } from 'vue-router'

const user = ref(null)
const loading = ref(false)

export const useAuth = () => {
    const router = useRouter()

    const fetchUser = async () => {
        try {
            const res = await fetch('/me', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                },
            })

            if (!res.ok) {
                user.value = null
                return null
            }

            user.value = await res.json()
            return user.value
        } catch (e) {
            user.value = null
            return null
        }
    }

    const logout = async () => {
        console.log(document.querySelector('meta[name="csrf-token"]')?.content)

        const response = await fetch('/logout', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute('content'),
            },
        })

        console.log(await response.json())

        window.location.href = '/login'
    }

    return {
        user,
        fetchUser,
        logout,
        loading,
    }
}