<template>
    <div class="dashboard-page">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">🏢</div>
                <div class="stat-content">
                    <div class="stat-value">{{ stats.totalTenants || 0 }}</div>
                    <div class="stat-label">Total Tenants</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-content">
                    <div class="stat-value">{{ stats.activeTenants || 0 }}</div>
                    <div class="stat-label">Active Tenants</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-content">
                    <div class="stat-value">{{ stats.totalRevenue || '0.000' }}</div>
                    <div class="stat-label">Total Revenue (KWD)</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-content">
                    <div class="stat-value">{{ stats.totalOrders || 0 }}</div>
                    <div class="stat-label">Total Orders</div>
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="content-section">
            <div class="section-header">
                <h2>📦 Recent Orders</h2>
                <p class="section-subtitle">Latest orders and transactions</p>
            </div>
            
            <div v-if="recentOrders.length === 0 && !loading" class="info-card">
                <p>No recent orders found</p>
            </div>
            
            <div v-else-if="recentOrders.length > 0" class="orders-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Tenant</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order in recentOrders.slice(0, 10)" :key="order.id">
                            <td>{{ order.id }}</td>
                            <td>{{ order.tenant_id || '-' }}</td>
                            <td>{{ formatPrice(order.total_amount || order.amount) }}</td>
                            <td><StatusBadge :status="order.status || 'pending'" /></td>
                            <td>{{ formatDate(order.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="content-section">
            <div class="section-header">
                <h2>⚡ Quick Actions</h2>
            </div>
            
            <div class="quick-actions">
                <router-link to="/tenants/create" class="action-card">
                    <div class="action-icon">➕</div>
                    <div class="action-text">Add Tenant</div>
                </router-link>
                <router-link to="/packages/create" class="action-card">
                    <div class="action-icon">📦</div>
                    <div class="action-text">Add Package</div>
                </router-link>
                <router-link to="/admins/create" class="action-card">
                    <div class="action-icon">👥</div>
                    <div class="action-text">Add Admin</div>
                </router-link>
                <router-link to="/reports/tenants" class="action-card">
                    <div class="action-icon">📊</div>
                    <div class="action-text">View Reports</div>
                </router-link>
            </div>
        </div>
        
        <!-- Loading State -->
        <div v-if="loading" class="loading-overlay">
            <div class="spinner"></div>
            <p>Loading dashboard data...</p>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import api from '../services/api'
import StatusBadge from '../components/StatusBadge.vue'

export default {
    name: 'DashboardPage',
    components: { StatusBadge },
    setup() {
        const loading = ref(false)
        const stats = ref({
            totalTenants: 0,
            activeTenants: 0,
            totalRevenue: '0.000',
            totalOrders: 0
        })
        const recentOrders = ref([])
        const chartData = ref(null)
        
        const fetchDashboardData = async () => {
            loading.value = true
            try {
                const [statsRes, ordersRes, chartRes] = await Promise.all([
                    api.dashboard.stats(),
                    api.dashboard.recentOrders(),
                    api.dashboard.chartData()
                ])
                
                if (statsRes.data.success) {
                    const data = statsRes.data.data
                    stats.value = {
                        totalTenants: data.total_tenants || data.totalTenants || 0,
                        activeTenants: data.active_tenants || data.activeTenants || 0,
                        totalRevenue: formatPrice(data.total_revenue || data.totalRevenue || 0),
                        totalOrders: data.total_orders || data.totalOrders || 0
                    }
                }
                
                if (ordersRes.data.success) {
                    recentOrders.value = ordersRes.data.data || []
                }
                
                if (chartRes.data.success) {
                    chartData.value = chartRes.data.data
                }
            } catch (error) {
                console.error('Error fetching dashboard data:', error)
                // Use fallback data on error
                stats.value = {
                    totalTenants: 0,
                    activeTenants: 0,
                    totalRevenue: '0.000',
                    totalOrders: 0
                }
            } finally {
                loading.value = false
            }
        }
        
        const formatPrice = (price) => {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'KWD' }).format(price || 0)
        }
        
        const formatDate = (date) => {
            if (!date) return '-'
            return new Date(date).toLocaleDateString()
        }
        
        onMounted(() => {
            fetchDashboardData()
        })
        
        return {
            loading,
            stats,
            recentOrders,
            chartData,
            formatPrice,
            formatDate
        }
    }
}
</script>

<style scoped>
.dashboard-page {
    position: relative;
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
    font-size: 48px;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
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

/* Content Section */
.content-section {
    margin-bottom: 30px;
}

.section-header {
    margin-bottom: 20px;
}

.section-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.section-subtitle {
    color: #64748b;
    font-size: 15px;
}

.info-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.info-content h3 {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 12px;
}

.info-content p {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 24px;
}

.features-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
}

.feature-icon {
    font-size: 20px;
}

/* Loading */
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

/* Orders Table */
.orders-table {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead {
    background: #f9fafb;
}

.table th {
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table tbody tr {
    border-bottom: 1px solid #e5e7eb;
}

.table tbody tr:hover {
    background: #f9fafb;
}

.table td {
    padding: 12px 16px;
    font-size: 14px;
    color: #374151;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
}

.action-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s, box-shadow 0.2s;
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.action-icon {
    font-size: 32px;
    margin-bottom: 8px;
}

.action-text {
    font-size: 14px;
    font-weight: 500;
    color: #1e293b;
}
</style>

