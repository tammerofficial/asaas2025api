import axios from 'axios'

/**
 * Centralized API Service
 * All API calls for Central Dashboard V1
 * Base URL: /admin-home/v1/api
 */

const api = {
    // Dashboard
    dashboard: {
        stats: () => axios.get('/dashboard/stats'),
        recentOrders: () => axios.get('/dashboard/recent-orders'),
        chartData: () => axios.get('/dashboard/chart-data')
    },

    // Tenants
    tenants: {
        list: (params) => axios.get('/tenants', { params }),
        get: (id) => axios.get(`/tenants/${id}`),
        create: (data) => axios.post('/tenants', data),
        update: (id, data) => axios.put(`/tenants/${id}`, data),
        delete: (id) => axios.delete(`/tenants/${id}`)
    },

    // Packages (Price Plans)
    packages: {
        list: (params) => axios.get('/packages', { params }),
        get: (id) => axios.get(`/packages/${id}`),
        create: (data) => axios.post('/packages', data),
        update: (id, data) => axios.put(`/packages/${id}`, data),
        delete: (id) => axios.delete(`/packages/${id}`)
    },

    // Orders
    orders: {
        list: (params) => axios.get('/orders', { params }),
        get: (id) => axios.get(`/orders/${id}`),
        paymentLogs: (id) => axios.get(`/orders/${id}/payment-logs`)
    },

    // Payments
    payments: {
        list: (params) => axios.get('/payments', { params }),
        get: (id) => axios.get(`/payments/${id}`),
        update: (id, data) => axios.put(`/payments/${id}`, data)
    },

    // Admins
    admins: {
        list: (params) => axios.get('/admins', { params }),
        get: (id) => axios.get(`/admins/${id}`),
        create: (data) => axios.post('/admins', data),
        update: (id, data) => axios.put(`/admins/${id}`, data),
        delete: (id) => axios.delete(`/admins/${id}`)
    },

    // Blog
    blog: {
        list: (params) => axios.get('/blogs', { params }),
        get: (id) => axios.get(`/blogs/${id}`),
        create: (data) => axios.post('/blogs', data),
        update: (id, data) => axios.put(`/blogs/${id}`, data),
        delete: (id) => axios.delete(`/blogs/${id}`),
        categories: (params) => axios.get('/blog/categories', { params }),
        tags: (params) => axios.get('/blog/tags', { params }),
        comments: (params) => axios.get('/blog/comments', { params })
    },

    // Users
    users: {
        list: (params) => axios.get('/users', { params }),
        get: (id) => axios.get(`/users/${id}`),
        create: (data) => axios.post('/users', data),
        update: (id, data) => axios.put(`/users/${id}`, data),
        delete: (id) => axios.delete(`/users/${id}`),
        roles: (params) => axios.get('/users/roles', { params }),
        getRole: (id) => axios.get(`/users/roles/${id}`),
        createRole: (data) => axios.post('/users/roles', data),
        updateRole: (id, data) => axios.put(`/users/roles/${id}`, data),
        deleteRole: (id) => axios.delete(`/users/roles/${id}`),
        permissions: (params) => axios.get('/users/permissions', { params }),
        getPermission: (id) => axios.get(`/users/permissions/${id}`),
        createPermission: (data) => axios.post('/users/permissions', data),
        updatePermission: (id, data) => axios.put(`/users/permissions/${id}`, data),
        deletePermission: (id) => axios.delete(`/users/permissions/${id}`),
        activityLogs: (params) => axios.get('/users/activity-logs', { params }),
        loginActivity: (params) => axios.get('/users/login-activity', { params })
    },

    // Appearances
    appearances: {
        themes: (params) => axios.get('/appearances/themes', { params }),
        getTheme: (id) => axios.get(`/appearances/themes/${id}`),
        activateTheme: (id) => axios.put(`/appearances/themes/${id}/activate`),
        deleteTheme: (id) => axios.delete(`/appearances/themes/${id}`),
        menus: (params) => axios.get('/appearances/menus', { params }),
        getMenu: (id) => axios.get(`/appearances/menus/${id}`),
        createMenu: (data) => axios.post('/appearances/menus', data),
        updateMenu: (id, data) => axios.put(`/appearances/menus/${id}`, data),
        deleteMenu: (id) => axios.delete(`/appearances/menus/${id}`),
        widgets: (params) => axios.get('/appearances/widgets', { params }),
        getWidget: (id) => axios.get(`/appearances/widgets/${id}`),
        createWidget: (data) => axios.post('/appearances/widgets', data),
        updateWidget: (id, data) => axios.put(`/appearances/widgets/${id}`, data),
        activateWidget: (id) => axios.put(`/appearances/widgets/${id}/activate`),
        deactivateWidget: (id) => axios.put(`/appearances/widgets/${id}/deactivate`),
        deleteWidget: (id) => axios.delete(`/appearances/widgets/${id}`),
        themeOptions: () => axios.get('/appearances/theme-options'),
        updateThemeOptions: (options) => axios.put('/appearances/theme-options', { options }),
        generalSettings: () => axios.get('/appearances/general'),
        updateGeneralSettings: (settings) => axios.put('/appearances/general', { settings })
    },

    // System
    system: {
        languages: (params) => axios.get('/system/languages', { params }),
        getLanguage: (id) => axios.get(`/system/languages/${id}`),
        createLanguage: (data) => axios.post('/system/languages', data),
        updateLanguage: (id, data) => axios.put(`/system/languages/${id}`, data),
        deleteLanguage: (id) => axios.delete(`/system/languages/${id}`),
        setDefaultLanguage: (id) => axios.put(`/system/languages/${id}/set-default`),
        backups: (params) => axios.get('/system/backups', { params }),
        createBackup: (data) => axios.post('/system/backups', data),
        restoreBackup: (id) => axios.post(`/system/backups/${id}/restore`),
        deleteBackup: (id) => axios.delete(`/system/backups/${id}`),
        sitemap: () => axios.get('/system/sitemap'),
        generateSitemap: () => axios.post('/system/sitemap/generate'),
        updateSitemap: (data) => axios.put('/system/sitemap', data)
    },

    // Pages
    pages: {
        list: (params) => axios.get('/pages', { params }),
        get: (id) => axios.get(`/pages/${id}`),
        create: (data) => axios.post('/pages', data),
        update: (id, data) => axios.put(`/pages/${id}`, data),
        delete: (id) => axios.delete(`/pages/${id}`)
    },

    // Coupons
    coupons: {
        list: (params) => axios.get('/coupons', { params }),
        get: (id) => axios.get(`/coupons/${id}`),
        create: (data) => axios.post('/coupons', data),
        update: (id, data) => axios.put(`/coupons/${id}`, data),
        delete: (id) => axios.delete(`/coupons/${id}`)
    },

    // Subscriptions
    subscriptions: {
        subscribers: (params) => axios.get('/subscriptions/subscribers', { params }),
        stores: (params) => axios.get('/subscriptions/stores', { params }),
        paymentHistories: (params) => axios.get('/subscriptions/payment-histories', { params }),
        customDomains: (params) => axios.get('/subscriptions/custom-domains', { params })
    },

    // Support Tickets
    support: {
        tickets: (params) => axios.get('/support/tickets', { params }),
        ticket: (id) => axios.get(`/support/tickets/${id}`),
        create: (data) => axios.post('/support/tickets', data),
        update: (id, data) => axios.put(`/support/tickets/${id}`, data),
        delete: (id) => axios.delete(`/support/tickets/${id}`),
        departments: (params) => axios.get('/support/departments', { params }),
        addMessage: (id, message) => axios.post(`/support/tickets/${id}/messages`, { message })
    },

    // Media
    media: {
        list: (params) => axios.get('/media', { params }),
        get: (id) => axios.get(`/media/${id}`),
        upload: (formData) => axios.post('/media/upload', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        }),
        update: (id, data) => axios.put(`/media/${id}`, data),
        delete: (id) => axios.delete(`/media/${id}`),
        bulkDelete: (ids) => axios.post('/media/bulk-delete', { ids })
    },

    // Reports
    reports: {
        tenants: () => axios.get('/reports/tenants'),
        revenue: () => axios.get('/reports/revenue'),
        subscriptions: () => axios.get('/reports/subscriptions'),
        plans: () => axios.get('/reports/plans')
    },

    // Settings
    settings: {
        list: (params) => axios.get('/settings', { params }),
        get: (params) => axios.get('/settings', { params }),
        update: (data) => axios.put('/settings', { settings: data }),
        emailTemplates: (params) => axios.get('/settings/email-templates', { params }),
        getEmailTemplate: (id) => axios.get(`/settings/email-templates/${id}`),
        createEmailTemplate: (data) => axios.post('/settings/email-templates', data),
        updateEmailTemplate: (id, data) => axios.put(`/settings/email-templates/${id}`, data),
        deleteEmailTemplate: (id) => axios.delete(`/settings/email-templates/${id}`)
    },
    
    // Plans (alias for packages)
    plans: {
        list: (params) => axios.get('/packages', { params }),
        get: (id) => axios.get(`/packages/${id}`),
        create: (data) => axios.post('/packages', data),
        update: (id, data) => axios.put(`/packages/${id}`, data),
        delete: (id) => axios.delete(`/packages/${id}`)
    },

    // Plugins
    plugins: {
        list: (params) => axios.get('/plugins', { params }),
        get: (id) => axios.get(`/plugins/${id}`),
        create: (data) => axios.post('/plugins', data),
        update: (id, data) => axios.put(`/plugins/${id}`, data),
        activate: (id) => axios.put(`/plugins/${id}/activate`),
        deactivate: (id) => axios.put(`/plugins/${id}/deactivate`),
        delete: (id) => axios.delete(`/plugins/${id}`)
    }
}

export default api



