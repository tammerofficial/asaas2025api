<template>
    <div class="report-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">💰 Revenue Report</h1>
                <p class="page-subtitle">Revenue statistics and analytics</p>
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
                <div class="stat-icon success">
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
            
            <div class="stat-card">
                <div class="stat-icon info">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.75 12.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM7.5 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM8.25 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM9.75 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM10.5 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM12.75 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM14.25 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM15 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM16.5 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM15 12.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM16.5 13.5a.75.75 0 100-1.5.75.75 0 000 1.5z"/>
                        <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ formatPrice(report?.monthly_revenue || 0) }}</div>
                    <div class="stat-label">Monthly Revenue</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon warning">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ formatPrice(report?.yearly_revenue || 0) }}</div>
                    <div class="stat-label">Yearly Revenue</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-.05A3 3 0 0015 1.5h-1.5a3 3 0 00-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zM6 12a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V12zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 15a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V15zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 18a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V18zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ report?.total_transactions || 0 }}</div>
                    <div class="stat-label">Total Transactions</div>
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
        
        <!-- Revenue Details Table -->
        <div v-else-if="report" class="report-content">
            <div class="report-section">
                <h3>Revenue Details</h3>
                <div class="table-container">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transactions</th>
                                <th>Revenue</th>
                                <th>Average</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="report.revenue_details && report.revenue_details.length === 0">
                                <td colspan="4" class="empty-cell">
                                    No revenue data found
                                </td>
                            </tr>
                            <tr v-else v-for="(detail, index) in report.revenue_details || []" :key="index">
                                <td>{{ detail.date || 'N/A' }}</td>
                                <td>{{ detail.transactions || 0 }}</td>
                                <td>
                                    <strong class="amount">{{ formatPrice(detail.revenue || 0) }}</strong>
                                </td>
                                <td>{{ formatPrice(detail.average || 0) }}</td>
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
import api from '../../services/api'

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
        
        const response = await api.reports.revenue()
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

.amount {
    color: #10b981;
    font-weight: 600;
}

.empty-cell {
    text-align: center;
    padding: 40px !important;
    color: #64748b;
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
