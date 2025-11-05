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
        get: (id) => axios.get(`/orders/${id}`)
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
        roles: (params) => axios.get('/users/roles', { params }),
        permissions: (params) => axios.get('/users/permissions', { params }),
        activityLogs: (params) => axios.get('/users/activity-logs', { params })
    },

    // Appearances
    appearances: {
        themes: (params) => axios.get('/appearances/themes', { params }),
        menus: (params) => axios.get('/appearances/menus', { params }),
        widgets: (params) => axios.get('/appearances/widgets', { params })
    },

    // System
    system: {
        languages: (params) => axios.get('/system/languages', { params })
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
        get: (params) => axios.get('/settings', { params }),
        update: (data) => axios.put('/settings', { settings: data })
    }
}

export default api



