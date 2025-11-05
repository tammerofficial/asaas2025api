<template>
    <div class="report-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">📮 Subscriptions Report</h1>
                <p class="page-subtitle">Subscriptions statistics and analytics</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-secondary" @click="exportReport">
                    📥 Export Report
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ report?.total_subscriptions || 0 }}</div>
                    <div class="stat-label">Total Subscriptions</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon success">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ report?.active_subscriptions || 0 }}</div>
                    <div class="stat-label">Active Subscriptions</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon warning">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ report?.expired_subscriptions || 0 }}</div>
                    <div class="stat-label">Expired Subscriptions</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon info">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ report?.new_subscriptions_this_month || 0 }}</div>
                    <div class="stat-label">New This Month</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <select v-model="dateRange" class="filter-select" @change="loadReport">
                <option value="all">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
            </select>
            <button class="btn btn-primary" @click="loadReport">
                🔄 Refresh
            </button>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading-overlay">
            <div class="spinner"></div>
            <p>Loading report...</p>
        </div>
        
        <!-- Subscriptions Details -->
        <div v-else-if="report" class="report-content">
            <div class="report-section">
                <h3>Subscription Details</h3>
                <div class="table-container">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Tenant</th>
                                <th>Plan</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>Expiry Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="report.subscriptions && report.subscriptions.length === 0">
                                <td colspan="8" class="empty-cell">
                                    No subscriptions found
                                </td>
                            </tr>
                            <tr v-else v-for="subscription in report.subscriptions || []" :key="subscription.id">
                                <td>{{ subscription.id }}</td>
                                <td>{{ subscription.user_name || 'N/A' }}</td>
                                <td>
                                    <span class="tenant-badge">{{ subscription.tenant_id || 'N/A' }}</span>
                                </td>
                                <td>{{ subscription.plan_name || subscription.package_name || 'N/A' }}</td>
                                <td>
                                    <StatusBadge :status="subscription.status || 'active'" />
                                </td>
                                <td>{{ formatDate(subscription.start_date || subscription.created_at) }}</td>
                                <td>{{ formatDate(subscription.expiry_date || subscription.expire_date) }}</td>
                                <td>
                                    <button class="btn-icon" @click="viewSubscription(subscription)" title="View">
                                        👁️
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

const router = useRouter()

const loading = ref(false)
const report = ref(null)
const dateRange = ref('all')

const loadReport = async () => {
    loading.value = true
    try {
        const params = {}
        if (dateRange.value !== 'all') {
            params.range = dateRange.value
        }
        
        const response = await api.reports.subscriptions()
        if (response.data.success) {
            report.value = response.data.data
        }
    } catch (error) {
        console.error('Error fetching report:', error)
    } finally {
        loading.value = false
    }
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

const viewSubscription = (subscription) => {
    console.log('View subscription:', subscription)
}

const exportReport = () => {
    alert('Export functionality will be implemented')
}

onMounted(() => {
    loadReport()
})
</script>

<style scoped>
.report-page {
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

.header-actions {
    display: flex;
    gap: 12px;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #64748b;
    color: white;
}

.btn-secondary:hover {
    background: #475569;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.stat-icon {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #7f1625;
    border-radius: 12px;
    flex-shrink: 0;
}

.stat-icon svg {
    width: 40px;
    height: 40px;
    color: white;
}

.stat-icon.success {
    background: #10b981;
}

.stat-icon.warning {
    background: #f59e0b;
}

.stat-icon.info {
    background: #06b6d4;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 32px;
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
}

.filter-select {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    background: white;
}

/* Report Section */
.report-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.report-section h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 20px 0;
}

.table-container {
    overflow-x: auto;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
}

.report-table thead {
    background: #f8fafc;
}

.report-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e5e7eb;
}

.report-table td {
    padding: 16px;
    border-top: 1px solid #f3f4f6;
    color: #1e293b;
}

.report-table tbody tr:hover {
    background: #f9fafb;
}

.tenant-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.empty-cell {
    text-align: center;
    padding: 40px !important;
    color: #64748b;
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

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 9999;
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

.loading-overlay p {
    color: #64748b;
    font-weight: 500;
}
</style>
