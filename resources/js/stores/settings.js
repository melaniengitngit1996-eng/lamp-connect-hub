import { defineStore } from 'pinia'
import axios from 'axios'

export const useSettings = defineStore('settings', {
    state: () => ({
        loaded: false,
        loading: false,

        general: {},
        chat: {},
        feed: {},
        drive: {},
    }),

    actions: {
        async load(force = false) {
            if (this.loaded && !force) {
                return
            }

            this.loading = true

            try {
                const { data } = await axios.get('/api/settings')

                Object.assign(this, data)

                this.loaded = true
            } finally {
                this.loading = false
            }
        },

        async save(key, value) {
            await axios.patch('/api/settings', {
                key,
                value,
            })
        }
    }
})