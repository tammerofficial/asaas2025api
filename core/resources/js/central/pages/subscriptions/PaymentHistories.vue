<template>
    <div class="payment-histories-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">💳 Payment Histories</h1>
                <p class="page-subtitle">View all payment transactions</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-content">
                    <div class="stat-value">{{ formatPrice(totalRevenue) }}</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-content">
                    <div class="stat-value">{{ successfulPayments }}</div>
                    <div class="stat-label">Successful</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">❌</div>
                <div class="stat-content">
                    <div class="stat-value">{{ failedPayments }}</div>
                    <div class="stat-label">Failed</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-content">
                    <div class="stat-value">{{ pendingPayments }}</div>
                    <div class="stat-label">Pending</div>
                </div>
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
                <option value="success">Success</option>
                <option value="failed">Failed</option>
                <option value="pending">Pending</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <input 
                type="date" 
                v-model="dateFrom" 
                class="date-input"
                @change="loadPayments(1)"
                placeholder="From Date"
            />
            <input 
                type="date" 
                v-model="dateTo" 
                class="date-input"
                @change="loadPayments(1)"
                placeholder="To Date"
            />
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
                        <th>User</th>
                        <th>Tenant</th>
                        <th>Package</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="payments.length === 0">
                        <td colspan="10" class="empty-cell">
                            No payments found
                        </td>
                    </tr>
                    <tr v-else v-for="payment in payments" :key="payment.id">
                        <td>{{ payment.id }}</td>
                        <td>
                            <code class="order-badge">{{ payment.order_id || payment.track || 'N/A' }}</code>
                        </td>
                        <td>{{ payment.user_name || payment.name || 'N/A' }}</td>
                        <td>
                            <span class="tenant-badge">{{ payment.tenant_id || 'N/A' }}</span>
                        </td>
                        <td>{{ payment.package_name || 'N/A' }}</td>
                        <td>
                            <strong class="amount">{{ formatPrice(payment.amount || payment.package_price || 0) }}</strong>
                        </td>
                        <td>{{ payment.payment_gateway || payment.method || 'N/A' }}</td>
                        <td>
                            <StatusBadge :status="payment.payment_status || payment.status || 'pending'" />
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
                                <button 
                                    v-if="payment.status === 'pending'"
                                    class="btn-icon btn-success" 
                                    @click="approvePayment(payment)"
                                    title="Approve"
                                >
                                    ✅
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
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

const router = useRouter()

const payments = ref([])
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const dateFrom = ref('')
const dateTo = ref('')
const pagination = ref({ 
    current_page: 1, 
    last_page: 1, 
    per_page: 20, 
    total: 0 
})

let searchTimeout = null

const totalRevenue = computed(() => {
    return payments.value
        .filter(p => p.payment_status === 'success' || p.status === 'success')
        .reduce((sum, p) => sum + (parseFloat(p.amount || p.package_price || 0)), 0)
})

const successfulPayments = computed(() => {
    return payments.value.filter(p => 
        p.payment_status === 'success' || p.status === 'success'
    ).length
})

const failedPayments = computed(() => {
    return payments.value.filter(p => 
        p.payment_status === 'failed' || p.status === 'failed'
    ).length
})

const pendingPayments = computed(() => {
    return payments.value.filter(p => 
        p.payment_status === 'pending' || p.status === 'pending'
    ).length
})

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
        
        if (dateFrom.value) {
            params.date_from = dateFrom.value
        }
        
        if (dateTo.value) {
            params.date_to = dateTo.value
        }
        
        const response = await api.subscriptions.paymentHistories(params)
        
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
        minimumFractionDigits: 3
    }).format(price || 0)
}

const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const viewPayment = (payment) => {
    router.push(`/payments/${payment.id}`)
}

const approvePayment = async (payment) => {
    if (!confirm(`Approve payment #${payment.id}?`)) {
        return
    }
    
    try {
        await api.payments.update(payment.id, { status: 'success' })
        await loadPayments(pagination.value.current_page)
    } catch (error) {
        console.error('Error approving payment:', error)
        alert('Failed to approve payment')
    }
}

onMounted(() => {
    loadPayments()
})
</script>

<style scoped>
.payment-histories-page {
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

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.stat-icon {
    font-size: 40px;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border-radius: 12px;
    flex-shrink: 0;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
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
    flex-wrap: wrap;
}

.search-box {
    flex: 1;
    min-width: 250px;
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

.filter-select,
.date-input {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    background: white;
}

.date-input {
    min-width: 150px;
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

.order-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    font-family: monospace;
}

.tenant-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.amount {
    color: #10b981;
    font-weight: 600;
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

.btn-icon.btn-success:hover {
    background: #dcfce7;
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
