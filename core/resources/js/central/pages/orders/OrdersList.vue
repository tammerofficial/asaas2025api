<template>
    <div class="orders-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">📦 Orders</h1>
                <p class="page-subtitle">View and manage all orders</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search orders..."
                    class="search-input"
                    @input="debounceSearch"
                />
            </div>
            <select v-model="statusFilter" class="filter-select" @change="loadOrders(1)">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <!-- Orders Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading orders...</p>
            </div>
            
            <table v-else class="orders-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tenant</th>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="orders.length === 0">
                        <td colspan="7" class="empty-cell">
                            No orders found
                        </td>
                    </tr>
                    <tr v-else v-for="order in orders" :key="order.id">
                        <td>{{ order.id }}</td>
                        <td>
                            <span class="tenant-id">Tenant #{{ order.tenant_id }}</span>
                        </td>
                        <td>
                            <span class="plan-id">Plan #{{ order.plan_id }}</span>
                        </td>
                        <td>
                            <strong class="amount-text">{{ formatPrice(order.total_amount || order.amount) }}</strong>
                        </td>
                        <td>
                            <StatusBadge :status="order.status || 'pending'" />
                        </td>
                        <td>{{ formatDate(order.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="viewOrder(order)"
                                    title="View"
                                >
                                    👁️
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="!loading && orders.length > 0 && pagination.last_page > 1" class="pagination">
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page === 1"
                @click="loadOrders(pagination.current_page - 1)"
            >
                ← Previous
            </button>
            <span class="pagination-info">
                Page {{ pagination.current_page }} of {{ pagination.last_page }}
                ({{ pagination.total }} total)
            </span>
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page >= pagination.last_page"
                @click="loadOrders(pagination.current_page + 1)"
            >
                Next →
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

const router = useRouter()

const orders = ref([])
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const pagination = ref({ 
    current_page: 1, 
    last_page: 1, 
    per_page: 20, 
    total: 0 
})

let searchTimeout = null

const loadOrders = async (page = 1) => {
    loading.value = true
    try {
        const params = {
            page,
            per_page: pagination.value.per_page
        }
        
        if (searchQuery.value) {
            params.search = searchQuery.value
        }
        
        if (statusFilter.value) {
            params.status = statusFilter.value
        }
        
        const response = await api.orders.list(params)
        
        if (response.data.success) {
            orders.value = response.data.data || []
            if (response.data.meta) {
                pagination.value = response.data.meta
            }
        }
    } catch (error) {
        console.error('Error loading orders:', error)
        orders.value = []
    } finally {
        loading.value = false
    }
}

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        loadOrders(1)
    }, 500)
}

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', { 
        style: 'currency', 
        currency: 'KWD',
        minimumFractionDigits: 3,
        maximumFractionDigits: 3
    }).format(price || 0)
}

const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const viewOrder = (order) => {
    router.push(`/orders/${order.id}`)
}

onMounted(() => {
    loadOrders()
})
</script>

<style scoped>
.orders-page {
    padding: 24px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.page-subtitle {
    color: #64748b;
    font-size: 15px;
    margin: 0;
}

/* Filters */
.filters-bar {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.search-box {
    flex: 1;
}

.search-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
}

.filter-select {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    background: white;
}

/* Table */
.table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    position: relative;
    min-height: 400px;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
}

.orders-table thead {
    background: #f8fafc;
}

.orders-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.orders-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.tenant-id,
.plan-id {
    color: #64748b;
    font-size: 14px;
}

.amount-text {
    color: #059669;
    font-size: 16px;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-icon {
    background: #f1f5f9;
    border: none;
    padding: 8px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.2s;
}

.btn-icon:hover {
    background: #e2e8f0;
}

.empty-cell {
    text-align: center;
    padding: 40px !important;
    color: #64748b;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e5e7eb;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-bottom: 16px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-top: 30px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.pagination-btn {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
}

.pagination-btn:hover:not(:disabled) {
    background: #2563eb;
}

.pagination-btn:disabled {
    background: #e5e7eb;
    color: #94a3b8;
    cursor: not-allowed;
}

.pagination-info {
    color: #64748b;
    font-weight: 500;
}
</style>
