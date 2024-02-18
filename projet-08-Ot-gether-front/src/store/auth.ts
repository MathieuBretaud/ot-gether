import { defineStore } from 'pinia'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/store/user.js'
import { clientAxios } from '@/services'

export const useAuthStore = defineStore({
	id: 'auth',

	state: () =>
		JSON.parse(localStorage.getItem('AUTH_STATE') ?? '') ?? {
			email: null,
			isLoggedIn: false,
		},

	actions: {
		updateState(payload: any) {
			let newUserState = { ...this.$state, ...payload }
			localStorage.removeItem('AUTH_STATE')
			localStorage.setItem('AUTH_STATE', JSON.stringify(newUserState))
			this.$reset()
		},

		async forgotPassword({ email }: any) {
			try {
				await clientAxios.post('/forgot-password', { email })
			} catch (error: any) {
				console.log('ERROR WITH FORGOT-PASSWORD ENDPOINT: ', error.message)
				throw error
			}
		},

		async logout() {
			const user = useUserStore()
			const router = useRouter()
			localStorage.clear() // always clean localStorage before reset the state
			this.$reset()
			user.$reset()

			try {
				await clientAxios.post('/api/logout')
				await router.push({ name: 'login' })
			} catch (error) {
				window.location.pathname = '/login'
			}
		},
	},
})