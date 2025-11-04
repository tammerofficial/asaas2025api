import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'
import App from './App.vue'
import DashboardLayout from './layouts/DashboardLayout.vue'

// Import existing pages
import DashboardPage from './pages/DashboardPage.vue'
import TenantsPage from './pages/TenantsPage.vue'

// Configure axios
axios.defaults.baseURL = '/admin-home/v1/api'
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'application/json'

// Set CSRF token if available
if (window.csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken
}

// Router configuration
const routes = [
    {
        path: '/',
        component: DashboardLayout,
        children: [
            // Dashboard
            {
                path: '',
                name: 'dashboard',
                component: DashboardPage,
                meta: { title: 'Dashboard' }
            },
            
            // Media
            {
                path: '/media',
                name: 'media',
                component: () => import('./pages/media/MediaLibrary.vue'),
                meta: { title: 'Media' }
            },
            
            // Blog
            {
                path: '/blog',
                name: 'blog',
                component: () => import('./pages/blog/BlogList.vue'),
                meta: { title: 'All Blogs' }
            },
            {
                path: '/blog/create',
                name: 'blog-create',
                component: () => import('./pages/blog/BlogCreate.vue'),
                meta: { title: 'Add New Blog' }
            },
            {
                path: '/blog/categories',
                name: 'blog-categories',
                component: () => import('./pages/blog/Categories.vue'),
                meta: { title: 'Blog Categories' }
            },
            {
                path: '/blog/tags',
                name: 'blog-tags',
                component: () => import('./pages/blog/Tags.vue'),
                meta: { title: 'Blog Tags' }
            },
            {
                path: '/blog/comments',
                name: 'blog-comments',
                component: () => import('./pages/blog/Comments.vue'),
                meta: { title: 'Blog Comments' }
            },
            {
                path: '/blog/settings',
                name: 'blog-settings',
                component: () => import('./pages/blog/Settings.vue'),
                meta: { title: 'Blog Settings' }
            },
            
            // Pages
            {
                path: '/pages',
                name: 'pages',
                component: () => import('./pages/pages/PagesList.vue'),
                meta: { title: 'All Pages' }
            },
            {
                path: '/pages/create',
                name: 'pages-create',
                component: () => import('./pages/pages/PageCreate.vue'),
                meta: { title: 'Add New Page' }
            },
            
            // Packages
            {
                path: '/packages',
                name: 'packages',
                component: () => import('./pages/packages/PackagesList.vue'),
                meta: { title: 'All Packages' }
            },
            {
                path: '/packages/create',
                name: 'packages-create',
                component: () => import('./pages/packages/PackageCreate.vue'),
                meta: { title: 'Add New Package' }
            },
            {
                path: '/packages/:id/edit',
                name: 'packages-edit',
                component: () => import('./pages/packages/PackageEdit.vue'),
                meta: { title: 'Edit Package' }
            },
            {
                path: '/packages/plans',
                name: 'packages-plans',
                component: () => import('./pages/packages/Plans.vue'),
                meta: { title: 'Package Plans' }
            },
            
            // Coupons
            {
                path: '/coupons',
                name: 'coupons',
                component: () => import('./pages/coupons/CouponsList.vue'),
                meta: { title: 'All Coupons' }
            },
            {
                path: '/coupons/create',
                name: 'coupons-create',
                component: () => import('./pages/coupons/CouponCreate.vue'),
                meta: { title: 'Create New Coupon' }
            },
            
            // Payments
            {
                path: '/payments/methods',
                name: 'payments-methods',
                component: () => import('./pages/payments/PaymentMethods.vue'),
                meta: { title: 'Payment Methods' }
            },
            {
                path: '/payments/saas-settings',
                name: 'payments-saas-settings',
                component: () => import('./pages/payments/SaasSettings.vue'),
                meta: { title: 'SAAS Settings' }
            },
            {
                path: '/payments/currencies',
                name: 'payments-currencies',
                component: () => import('./pages/payments/Currencies.vue'),
                meta: { title: 'Currencies' }
            },
            {
                path: '/payments/general',
                name: 'payments-general',
                component: () => import('./pages/payments/GeneralSettings.vue'),
                meta: { title: 'Payment General Settings' }
            },
            {
                path: '/payments/notifications',
                name: 'payments-notifications',
                component: () => import('./pages/payments/Notifications.vue'),
                meta: { title: 'Payment Notification Settings' }
            },
            
            // Subscriptions
            {
                path: '/subscriptions/subscribers',
                name: 'subscriptions-subscribers',
                component: () => import('./pages/subscriptions/Subscribers.vue'),
                meta: { title: 'All Subscribers' }
            },
            {
                path: '/subscriptions/stores',
                name: 'subscriptions-stores',
                component: () => import('./pages/subscriptions/Stores.vue'),
                meta: { title: 'All Stores' }
            },
            {
                path: '/subscriptions/payment-histories',
                name: 'subscriptions-payment-histories',
                component: () => import('./pages/subscriptions/PaymentHistories.vue'),
                meta: { title: 'Payment Histories' }
            },
            {
                path: '/subscriptions/custom-domains',
                name: 'subscriptions-custom-domains',
                component: () => import('./pages/subscriptions/CustomDomains.vue'),
                meta: { title: 'Custom Domains' }
            },
            
            // Support Tickets
            {
                path: '/support',
                name: 'support-tickets',
                component: () => import('./pages/support/TicketsList.vue'),
                meta: { title: 'All Tickets' }
            },
            {
                path: '/support/create',
                name: 'support-create',
                component: () => import('./pages/support/TicketCreate.vue'),
                meta: { title: 'Create Ticket' }
            },
            {
                path: '/support/:id',
                name: 'support-view',
                component: () => import('./pages/support/TicketView.vue'),
                meta: { title: 'View Ticket' }
            },
            {
                path: '/support/categories',
                name: 'support-categories',
                component: () => import('./pages/support/Categories.vue'),
                meta: { title: 'Support Ticket Categories' }
            },
            
            // Appearances
            {
                path: '/appearances/themes',
                name: 'appearances-themes',
                component: () => import('./pages/appearances/Themes.vue'),
                meta: { title: 'Themes' }
            },
            {
                path: '/appearances/menus',
                name: 'appearances-menus',
                component: () => import('./pages/appearances/Menus.vue'),
                meta: { title: 'Menus' }
            },
            {
                path: '/appearances/theme-options',
                name: 'appearances-theme-options',
                component: () => import('./pages/appearances/ThemeOptions.vue'),
                meta: { title: 'Theme Options' }
            },
            {
                path: '/appearances/general',
                name: 'appearances-general',
                component: () => import('./pages/appearances/GeneralSettings.vue'),
                meta: { title: 'Appearance General Settings' }
            },
            {
                path: '/appearances/widgets',
                name: 'appearances-widgets',
                component: () => import('./pages/appearances/Widgets.vue'),
                meta: { title: 'Widgets' }
            },
            
            // Plugins
            {
                path: '/plugins',
                name: 'plugins',
                component: () => import('./pages/plugins/PluginsList.vue'),
                meta: { title: 'Plugins' }
            },
            
            // Settings
            {
                path: '/settings/general',
                name: 'settings-general',
                component: () => import('./pages/settings/GeneralSettings.vue'),
                meta: { title: 'General Settings' }
            },
            {
                path: '/settings/email',
                name: 'settings-email',
                component: () => import('./pages/settings/EmailSettings.vue'),
                meta: { title: 'Email Settings' }
            },
            {
                path: '/settings/email-templates',
                name: 'settings-email-templates',
                component: () => import('./pages/settings/EmailTemplates.vue'),
                meta: { title: 'Email Templates' }
            },
            {
                path: '/settings/languages',
                name: 'settings-languages',
                component: () => import('./pages/settings/Languages.vue'),
                meta: { title: 'Languages' }
            },
            {
                path: '/settings/media',
                name: 'settings-media',
                component: () => import('./pages/settings/MediaSettings.vue'),
                meta: { title: 'Media Settings' }
            },
            {
                path: '/settings/seo',
                name: 'settings-seo',
                component: () => import('./pages/settings/SeoSettings.vue'),
                meta: { title: 'SEO Settings' }
            },
            
            // Users
            {
                path: '/users',
                name: 'users',
                component: () => import('./pages/users/UsersList.vue'),
                meta: { title: 'Users' }
            },
            {
                path: '/users/roles',
                name: 'users-roles',
                component: () => import('./pages/users/Roles.vue'),
                meta: { title: 'Roles' }
            },
            {
                path: '/users/permissions',
                name: 'users-permissions',
                component: () => import('./pages/users/Permissions.vue'),
                meta: { title: 'Permissions' }
            },
            {
                path: '/users/activity-logs',
                name: 'users-activity-logs',
                component: () => import('./pages/users/ActivityLogs.vue'),
                meta: { title: 'Activity Logs' }
            },
            {
                path: '/users/login-activity',
                name: 'users-login-activity',
                component: () => import('./pages/users/LoginActivity.vue'),
                meta: { title: 'Login Activity' }
            },
            
            // System
            {
                path: '/system/sitemap',
                name: 'system-sitemap',
                component: () => import('./pages/system/Sitemap.vue'),
                meta: { title: 'Sitemap' }
            },
            {
                path: '/system/update',
                name: 'system-update',
                component: () => import('./pages/system/Update.vue'),
                meta: { title: 'Update' }
            },
            {
                path: '/system/backups',
                name: 'system-backups',
                component: () => import('./pages/system/Backups.vue'),
                meta: { title: 'Backups' }
            },
            
            // Tenants (existing)
            {
                path: '/tenants',
                name: 'tenants',
                component: TenantsPage,
                meta: { title: 'Tenants' }
            },
            {
                path: '/tenants/create',
                name: 'tenants-create',
                component: () => import('./pages/tenants/TenantCreate.vue'),
                meta: { title: 'Create Tenant' }
            },
            {
                path: '/tenants/:id/edit',
                name: 'tenants-edit',
                component: () => import('./pages/tenants/TenantEdit.vue'),
                meta: { title: 'Edit Tenant' }
            },
            
            // Orders
            {
                path: '/orders',
                name: 'orders',
                component: () => import('./pages/orders/OrdersList.vue'),
                meta: { title: 'Orders' }
            },
            {
                path: '/orders/:id',
                name: 'orders-view',
                component: () => import('./pages/orders/OrderView.vue'),
                meta: { title: 'View Order' }
            },
            
            // Payments List
            {
                path: '/payments',
                name: 'payments',
                component: () => import('./pages/payments/PaymentsList.vue'),
                meta: { title: 'Payments' }
            },
            {
                path: '/payments/:id',
                name: 'payments-view',
                component: () => import('./pages/payments/PaymentView.vue'),
                meta: { title: 'View Payment' }
            },
            
            // Admins
            {
                path: '/admins',
                name: 'admins',
                component: () => import('./pages/admins/AdminsList.vue'),
                meta: { title: 'Admins' }
            },
            {
                path: '/admins/create',
                name: 'admins-create',
                component: () => import('./pages/admins/AdminCreate.vue'),
                meta: { title: 'Create Admin' }
            },
            {
                path: '/admins/:id/edit',
                name: 'admins-edit',
                component: () => import('./pages/admins/AdminEdit.vue'),
                meta: { title: 'Edit Admin' }
            },
            
            // Reports
            {
                path: '/reports/tenants',
                name: 'reports-tenants',
                component: () => import('./pages/reports/TenantsReport.vue'),
                meta: { title: 'Tenants Report' }
            },
            {
                path: '/reports/revenue',
                name: 'reports-revenue',
                component: () => import('./pages/reports/RevenueReport.vue'),
                meta: { title: 'Revenue Report' }
            },
            {
                path: '/reports/subscriptions',
                name: 'reports-subscriptions',
                component: () => import('./pages/reports/SubscriptionsReport.vue'),
                meta: { title: 'Subscriptions Report' }
            },
            {
                path: '/reports/plans',
                name: 'reports-plans',
                component: () => import('./pages/reports/PlansReport.vue'),
                meta: { title: 'Plans Report' }
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory('/admin-home/v1'),
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
app.mount('#central-v1-app')
