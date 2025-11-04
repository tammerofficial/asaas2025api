<template>
    <div class="payments-page">
        <div class="page-header">
            <h2>💳 Payments</h2>
            <p class="page-subtitle">View and manage all payments</p>
        </div>

        <DataTable
            :columns="columns"
            :data="payments"
            :loading="loading"
            :pagination="true"
            :current-page="currentPage"
            :total-pages="totalPages"
            :total="total"
            :per-page="perPage"
            @update:current-page="currentPage = $event"
        >
            <template #cell-status="{ value }">
                <StatusBadge :status="value || 'pending'" />
            </template>
            <template #cell-amount="{ value }">
                {{ formatPrice(value) }}
            </template>
            <template #cell-actions="{ row }">
                <router-link :to="`/payments/${row.id}`" class="btn-sm btn-primary">View</router-link>
            </template>
        </DataTable>
    </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import api from '../../services/api'
import DataTable from '../../components/DataTable.vue'
import StatusBadge from '../../components/StatusBadge.vue'

export default {
    name: 'PaymentsList',
    components: { DataTable, StatusBadge },
    setup() {
        const loading = ref(false)
        const payments = ref([])
        const currentPage = ref(1)
        const totalPages = ref(1)
        const total = ref(0)
        const perPage = ref(20)

        const columns = [
            { key: 'id', label: 'ID', sortable: true },
            { key: 'order_id', label: 'Order ID', sortable: true },
            { key: 'amount', label: 'Amount', sortable: true },
            { key: 'payment_method', label: 'Method', sortable: false },
            { key: 'status', label: 'Status', sortable: true },
            { key: 'created_at', label: 'Created', sortable: true },
            { key: 'actions', label: 'Actions', sortable: false }
        ]

        const fetchPayments = async () => {
            loading.value = true
            try {
                const response = await api.payments.list({ page: currentPage.value, per_page: perPage.value })
                if (response.data.success) {
                    payments.value = response.data.data.data || response.data.data || []
                    total.value = response.data.data.total || payments.value.length
                    totalPages.value = response.data.data.last_page || 1
                }
            } catch (error) {
                console.error('Error fetching payments:', error)
            } finally {
                loading.value = false
            }
        }

        const formatPrice = (price) => {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'KWD' }).format(price || 0)
        }

        watch(currentPage, () => {
            fetchPayments()
        })

        onMounted(() => {
            fetchPayments()
        })

        return { loading, payments, columns, currentPage, totalPages, total, perPage, formatPrice }
    }
}
</script>

<style scoped>
.payments-page {
    padding: 20px;
}

.page-header {
    margin-bottom: 24px;
}

.page-header h2 {
    margin: 0 0 4px 0;
    font-size: 24px;
    color: #1e293b;
}

.page-subtitle {
    color: #64748b;
    font-size: 14px;
    margin: 0;
}

.btn-sm {
    padding: 6px 12px;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    font-size: 12px;
}
</style>



