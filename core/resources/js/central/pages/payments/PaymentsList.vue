<template>
    <div class="payments-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">💳 Payments</h1>
                <p class="page-subtitle">View and manage all payments</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search payments..."
                    class="search-input"
                    @input="debounceSearch"
                />
            </div>
            <select v-model="statusFilter" class="filter-select" @change="loadPayments(1)">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select v-model="methodFilter" class="filter-select" @change="loadPayments(1)">
                <option value="">All Methods</option>
                <option value="credit_card">Credit Card</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="paypal">PayPal</option>
                <option value="stripe">Stripe</option>
            </select>
        </div>

        <!-- Payments Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading payments...</p>
            </div>
            
            <table v-else class="payments-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order ID</th>
                        <th>Tenant</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="payments.length === 0">
                        <td colspan="8" class="empty-cell">
                            No payments found
                        </td>
                    </tr>
                    <tr v-else v-for="payment in payments" :key="payment.id">
                        <td>{{ payment.id }}</td>
                        <td>
                            <span class="order-id">#{{ payment.order_id }}</span>
                        </td>
                        <td>
                            <span class="tenant-id">Tenant #{{ payment.tenant_id || '-' }}</span>
                        </td>
                        <td>
                            <strong class="amount-text">{{ formatPrice(payment.amount) }}</strong>
                        </td>
                        <td>
                            <span class="method-badge">{{ payment.payment_method || '-' }}</span>
                        </td>
                        <td>
                            <StatusBadge :status="payment.status || 'pending'" />
                        </td>
                        <td>{{ formatDate(payment.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="viewPayment(payment)"
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
        <div v-if="!loading && payments.length > 0 && pagination.last_page > 1" class="pagination">
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page === 1"
                @click="loadPayments(pagination.current_page - 1)"
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
                @click="loadPayments(pagination.current_page + 1)"
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

const payments = ref([])
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const methodFilter = ref('')
const pagination = ref({ 
    current_page: 1, 
    last_page: 1, 
    per_page: 20, 
    total: 0 
})

let searchTimeout = null

const loadPayments = async (page = 1) => {
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
        
        if (methodFilter.value) {
            params.payment_method = methodFilter.value
        }
        
        const response = await api.payments.list(params)
        
        if (response.data.success) {
            payments.value = response.data.data || []
            if (response.data.meta) {
                pagination.value = response.data.meta
            }
        }
    } catch (error) {
        console.error('Error loading payments:', error)
        payments.value = []
    } finally {
        loading.value = false
    }
}

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        loadPayments(1)
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

const viewPayment = (payment) => {
    router.push(`/payments/${payment.id}`)
}

onMounted(() => {
    loadPayments()
})
</script>

<style scoped>
.payments-page {
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

.payments-table {
    width: 100%;
    border-collapse: collapse;
}

.payments-table thead {
    background: #f8fafc;
}

.payments-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.payments-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.order-id,
.tenant-id {
    color: #64748b;
    font-size: 14px;
}

.amount-text {
    color: #059669;
    font-size: 16px;
}

.method-badge {
    padding: 4px 8px;
    background: #f1f5f9;
    border-radius: 4px;
    font-size: 13px;
    color: #475569;
    text-transform: capitalize;
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
