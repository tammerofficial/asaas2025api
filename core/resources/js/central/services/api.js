import axios from 'axios'

/**
 * Centralized API Service
 * All API calls for Central Dashboard
 */

const api = {
    // Authentication
    auth: {
        login: (credentials) => axios.post('/auth/login', credentials),
        register: (data) => axios.post('/auth/register', data),
        me: () => axios.get('/auth/me'),
        logout: () => axios.post('/auth/logout'),
        refresh: () => axios.post('/auth/refresh')
    },

    // Dashboard
    dashboard: {
        index: () => axios.get('/dashboard'),
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
        delete: (id) => axios.delete(`/tenants/${id}`),
        activate: (id) => axios.post(`/tenants/${id}/activate`),
        deactivate: (id) => axios.post(`/tenants/${id}/deactivate`)
    },

    // Price Plans
    plans: {
        list: (params) => axios.get('/plans', { params }),
        get: (id) => axios.get(`/plans/${id}`),
        create: (data) => axios.post('/plans', data),
        update: (id, data) => axios.put(`/plans/${id}`, data),
        delete: (id) => axios.delete(`/plans/${id}`)
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
        delete: (id) => axios.delete(`/admins/${id}`),
        activate: (id) => axios.post(`/admins/${id}/activate`),
        deactivate: (id) => axios.post(`/admins/${id}/deactivate`)
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

    // Settings
    settings: {
        list: () => axios.get('/settings'),
        get: (key) => axios.get(`/settings/${key}`),
        update: (data) => axios.put('/settings', data),
        delete: (key) => axios.delete(`/settings/${key}`)
    },

    // Support Tickets
    supportTickets: {
        list: (params) => axios.get('/support-tickets', { params }),
        get: (id) => axios.get(`/support-tickets/${id}`),
        update: (id, data) => axios.put(`/support-tickets/${id}`, data),
        addMessage: (id, message) => axios.post(`/support-tickets/${id}/add-message`, { message })
    },

    // Reports
    reports: {
        tenants: () => axios.get('/reports/tenants'),
        revenue: () => axios.get('/reports/revenue'),
        subscriptions: () => axios.get('/reports/subscriptions'),
        plans: () => axios.get('/reports/plans')
    }
}

export default api



