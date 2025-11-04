<template>
    <div class="payment-view-page">
        <div class="page-header">
            <h2>💳 Payment Details</h2>
            <router-link to="/payments" class="btn-secondary">← Back to Payments</router-link>
        </div>

        <div v-if="loading" class="loading">Loading payment...</div>
        <div v-else-if="payment" class="payment-details">
            <div class="detail-section">
                <h3>Payment Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Payment ID:</label>
                        <span>{{ payment.id }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Order ID:</label>
                        <span>{{ payment.order_id }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Amount:</label>
                        <span>{{ formatPrice(payment.amount) }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Payment Method:</label>
                        <span>{{ payment.payment_method || '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Status:</label>
                        <StatusBadge :status="payment.status || 'pending'" />
                    </div>
                    <div class="detail-item">
                        <label>Created At:</label>
                        <span>{{ formatDate(payment.created_at) }}</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h3>Update Status</h3>
                <div class="form-group">
                    <label>Status</label>
                    <select v-model="statusUpdate" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <button @click="updateStatus" class="btn-primary" style="margin-top: 12px;">Update Status</button>
                </div>
            </div>
        </div>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'
import Toast from '../../components/Toast.vue'

export default {
    name: 'PaymentView',
    components: { StatusBadge, Toast },
    setup() {
        const route = useRoute()
        const loading = ref(false)
        const payment = ref(null)
        const statusUpdate = ref('')
        const showToast = ref(false)
        const toastType = ref('success')
        const toastTitle = ref('')
        const toastMessage = ref('')

        const fetchPayment = async () => {
            loading.value = true
            try {
                const response = await api.payments.get(route.params.id)
                if (response.data.success) {
                    payment.value = response.data.data
                    statusUpdate.value = payment.value.status || 'pending'
                }
            } catch (error) {
                console.error('Error fetching payment:', error)
            } finally {
                loading.value = false
            }
        }

        const updateStatus = async () => {
            loading.value = true
            try {
                await api.payments.update(route.params.id, { status: statusUpdate.value })
                showToastMessage('success', 'Success', 'Payment status updated successfully')
                fetchPayment()
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to update payment status')
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

        const showToastMessage = (type, title, message) => {
            toastType.value = type
            toastTitle.value = title
            toastMessage.value = message
            showToast.value = true
        }

        onMounted(() => {
            fetchPayment()
        })

        return { loading, payment, statusUpdate, showToast, toastType, toastTitle, toastMessage, updateStatus, formatPrice, formatDate }
    }
}
</script>

<style scoped>
.payment-view-page {
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.payment-details {
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

.form-group {
    margin-bottom: 20px;
}

.form-select {
    width: 100%;
    max-width: 300px;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
}
</style>



