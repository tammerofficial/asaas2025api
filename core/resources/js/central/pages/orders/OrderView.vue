<template>
    <div class="order-view-page">
        <div class="page-header">
            <h2>📦 Order Details</h2>
            <router-link to="/orders" class="btn-secondary">← Back to Orders</router-link>
        </div>

        <div v-if="loading" class="loading">Loading order...</div>
        <div v-else-if="order" class="order-details">
            <div class="detail-section">
                <h3>Order Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Order ID:</label>
                        <span>{{ order.id }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Tenant ID:</label>
                        <span>{{ order.tenant_id }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Plan ID:</label>
                        <span>{{ order.plan_id }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Total Amount:</label>
                        <span>{{ formatPrice(order.total_amount) }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Status:</label>
                        <StatusBadge :status="order.status || 'pending'" />
                    </div>
                    <div class="detail-item">
                        <label>Created At:</label>
                        <span>{{ formatDate(order.created_at) }}</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h3>Payment Logs</h3>
                <div v-if="paymentLogs.length === 0" class="empty">No payment logs found</div>
                <table v-else class="logs-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="log in paymentLogs" :key="log.id">
                            <td>{{ log.id }}</td>
                            <td>{{ formatPrice(log.amount) }}</td>
                            <td><StatusBadge :status="log.status || 'pending'" /></td>
                            <td>{{ formatDate(log.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

export default {
    name: 'OrderView',
    components: { StatusBadge },
    setup() {
        const route = useRoute()
        const loading = ref(false)
        const order = ref(null)
        const paymentLogs = ref([])

        const fetchOrder = async () => {
            loading.value = true
            try {
                const [orderRes, logsRes] = await Promise.all([
                    api.orders.get(route.params.id),
                    api.orders.paymentLogs(route.params.id)
                ])
                if (orderRes.data.success) {
                    order.value = orderRes.data.data
                }
                if (logsRes.data.success) {
                    paymentLogs.value = logsRes.data.data || []
                }
            } catch (error) {
                console.error('Error fetching order:', error)
            } finally {
                loading.value = false
            }
        }

        const formatPrice = (price) => {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'KWD' }).format(price || 0)
        }

        const formatDate = (date) => {
            if (!date) return '-'
            return new Date(date).toLocaleString()
        }

        onMounted(() => {
            fetchOrder()
        })

        return { loading, order, paymentLogs, formatPrice, formatDate }
    }
}
</script>

<style scoped>
.order-view-page {
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.order-details {
    background: white;
    border-radius: 8px;
    padding: 24px;
}

.detail-section {
    margin-bottom: 32px;
}

.detail-section h3 {
    margin: 0 0 16px 0;
    font-size: 18px;
    color: #1e293b;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.detail-item label {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
}

.detail-item span {
    font-size: 14px;
    color: #1e293b;
}

.logs-table {
    width: 100%;
    border-collapse: collapse;
}

.logs-table th,
.logs-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.logs-table th {
    background: #f9fafb;
    font-weight: 600;
    font-size: 12px;
    color: #6b7280;
}

.empty {
    padding: 40px;
    text-align: center;
    color: #9ca3af;
}
</style>



