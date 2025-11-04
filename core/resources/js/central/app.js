import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'
import App from './App.vue'
import DashboardLayout from './layouts/DashboardLayout.vue'

// Import pages
import DashboardPage from './pages/DashboardPage.vue'
import TenantsPage from './pages/TenantsPage.vue'

// Configure axios
axios.defaults.baseURL = '/api/central/v1'
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'application/json'

// Get token from localStorage
const token = localStorage.getItem('central_token')
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

// Router configuration
const routes = [
    {
        path: '/',
        component: DashboardLayout,
        children: [
            {
                path: '',
                name: 'dashboard',
                component: DashboardPage,
                meta: { title: 'Dashboard' }
            },
            {
                path: '/tenants',
                name: 'tenants',
                component: TenantsPage,
                meta: { title: 'Tenants' }
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory('/central'),
    routes
})

// Navigation guard - check authentication
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('central_token')
    
    // If no token and not going to login, redirect to login
    if (!token && to.path !== '/login') {
        // For now, allow access - we'll add login later
        next()
    } else {
        next()
    }
})

// Create Vue app
const app = createApp(App)

// Use router
app.use(router)

// Global properties
app.config.globalProperties.$http = axios

// Mount app
app.mount('#central-app')

