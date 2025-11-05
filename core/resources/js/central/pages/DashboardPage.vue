<template>
    <div class="dashboard-page">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ stats.totalTenants || 0 }}</div>
                    <div class="stat-label">Total Tenants</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ stats.activeTenants || 0 }}</div>
                    <div class="stat-label">Active Tenants</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"/>
                        <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z" clip-rule="evenodd"/>
                        <path d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 00-.75-.75H2.25z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ stats.totalRevenue || '0.000' }}</div>
                    <div class="stat-label">Total Revenue (KWD)</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375z"/>
                        <path fill-rule="evenodd" d="M3.087 9l.54 9.176A3 3 0 006.62 21h10.757a3 3 0 002.995-2.824L20.913 9H3.087zm6.163 3.75A.75.75 0 0110 12h4a.75.75 0 010 1.5h-4a.75.75 0 01-.75-.75z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ stats.totalOrders || 0 }}</div>
                    <div class="stat-label">Total Orders</div>
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="content-section">
            <div class="section-header">
                <h2>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="header-icon">
                        <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-.05A3 3 0 0015 1.5h-1.5a3 3 0 00-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zM6 12a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V12zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 15a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V15zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 18a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V18zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75z" clip-rule="evenodd"/>
                    </svg>
                    Recent Orders
                </h2>
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
                <h2>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="header-icon">
                        <path fill-rule="evenodd" d="M14.615 1.595a.75.75 0 01.359.852L12.982 9.75h7.268a.75.75 0 01.548 1.262l-10.5 11.25a.75.75 0 01-1.272-.71l1.992-7.302H3.75a.75.75 0 01-.548-1.262l10.5-11.25a.75.75 0 01.913-.143z" clip-rule="evenodd"/>
                    </svg>
                    Quick Actions
                </h2>
            </div>
            
            <div class="quick-actions">
                <router-link to="/tenants/create" class="action-card">
                    <div class="action-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="action-text">Add Tenant</div>
                </router-link>
                <router-link to="/packages/create" class="action-card">
                    <div class="action-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375z"/>
                            <path fill-rule="evenodd" d="M3.087 9l.54 9.176A3 3 0 006.62 21h10.757a3 3 0 002.995-2.824L20.913 9H3.087zm6.163 3.75A.75.75 0 0110 12h4a.75.75 0 010 1.5h-4a.75.75 0 01-.75-.75z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="action-text">Add Package</div>
                </router-link>
                <router-link to="/admins/create" class="action-card">
                    <div class="action-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z"/>
                        </svg>
                    </div>
                    <div class="action-text">Add Admin</div>
                </router-link>
                <router-link to="/reports/tenants" class="action-card">
                    <div class="action-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"/>
                        </svg>
                    </div>
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
    display: flex;
    align-items: center;
    gap: 8px;
}

.header-icon {
    width: 24px;
    height: 24px;
    color: #7f1625;
}

.section-subtitle {
    color: #64748b;
    font-size: 15px;
}

.info-card {
    background: white;
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    color: #64748b;
}

/* Orders Table */
.orders-table {
    background: white;
    border-radius: 12px;
    padding: 20px;
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead th {
    text-align: left;
    padding: 12px;
    font-weight: 600;
    color: #1e293b;
    border-bottom: 2px solid #e5e7eb;
    font-size: 14px;
}

.table tbody td {
    padding: 12px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 14px;
    color: #64748b;
}

.table tbody tr:hover {
    background: #f9fafb;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.action-card {
    background: white;
    border-radius: 12px;
    padding: 30px 20px;
    text-align: center;
    text-decoration: none;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(127, 22, 37, 0.2);
    background: #7f1625;
}

.action-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #7f1625;
    border-radius: 12px;
    transition: all 0.2s;
}

.action-card:hover .action-icon {
    background: white;
}

.action-icon svg {
    width: 32px;
    height: 32px;
    color: white;
    transition: all 0.2s;
}

.action-card:hover .action-icon svg {
    color: #7f1625;
}

.action-text {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    transition: all 0.2s;
}

.action-card:hover .action-text {
    color: white;
}

/* Loading Overlay */
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
    border: 4px solid #f3f4f6;
    border-top-color: #7f1625;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 15px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.loading-overlay p {
    color: #64748b;
    font-weight: 500;
}
</style>
