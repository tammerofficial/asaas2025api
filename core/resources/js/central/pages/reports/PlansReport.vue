<template>
    <div class="report-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">📦 Plans Report</h1>
                <p class="page-subtitle">Package plans statistics</p>
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
                    <div class="stat-value">{{ report?.total_plans || 0 }}</div>
                    <div class="stat-label">Total Plans</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon success">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ report?.active_plans || 0 }}</div>
                    <div class="stat-label">Active Plans</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon info">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ report?.total_subscribers || 0 }}</div>
                    <div class="stat-label">Total Subscribers</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon warning">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"/>
                        <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z" clip-rule="evenodd"/>
                        <path d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 00-.75-.75H2.25z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ formatPrice(report?.total_revenue || 0) }}</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <button class="btn btn-primary" @click="loadReport">
                🔄 Refresh
            </button>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading-overlay">
            <div class="spinner"></div>
            <p>Loading report...</p>
        </div>
        
        <!-- Plans Details -->
        <div v-else-if="report" class="report-content">
            <div class="report-section">
                <h3>Plans Details</h3>
                <div class="table-container">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Plan Name</th>
                                <th>Price</th>
                                <th>Subscribers</th>
                                <th>Revenue</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="report.plans && report.plans.length === 0">
                                <td colspan="7" class="empty-cell">
                                    No plans found
                                </td>
                            </tr>
                            <tr v-else v-for="plan in report.plans || []" :key="plan.id">
                                <td>{{ plan.id }}</td>
                                <td>
                                    <strong>{{ plan.name || plan.title || 'N/A' }}</strong>
                                </td>
                                <td>
                                    <strong class="amount">{{ formatPrice(plan.price || 0) }}</strong>
                                </td>
                                <td>{{ plan.subscribers_count || 0 }}</td>
                                <td>
                                    <strong class="amount">{{ formatPrice(plan.revenue || 0) }}</strong>
                                </td>
                                <td>
                                    <StatusBadge :status="plan.status || 'active'" />
                                </td>
                                <td>
                                    <button class="btn-icon" @click="viewPlan(plan)" title="View">
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

const loadReport = async () => {
    loading.value = true
    try {
        const response = await api.reports.plans()
        if (response.data.success) {
            report.value = response.data.data
        }
    } catch (error) {
        console.error('Error fetching report:', error)
    } finally {
        loading.value = false
    }
}

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', { 
        style: 'currency', 
        currency: 'KWD',
        minimumFractionDigits: 3
    }).format(price || 0)
}

const viewPlan = (plan) => {
    router.push(`/packages/${plan.id}/edit`)
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

.stat-icon.info {
    background: #06b6d4;
}

.stat-icon.warning {
    background: #f59e0b;
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

.amount {
    color: #10b981;
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
