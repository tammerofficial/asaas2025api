<template>
    <div class="orders-page">
        <div class="page-header">
            <h2>📦 Orders</h2>
            <p class="page-subtitle">View and manage all orders</p>
        </div>

        <DataTable
            :columns="columns"
            :data="orders"
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
            <template #cell-actions="{ row }">
                <router-link :to="`/orders/${row.id}`" class="btn-sm btn-primary">View</router-link>
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
    name: 'OrdersList',
    components: { DataTable, StatusBadge },
    setup() {
        const loading = ref(false)
        const orders = ref([])
        const currentPage = ref(1)
        const totalPages = ref(1)
        const total = ref(0)
        const perPage = ref(20)

        const columns = [
            { key: 'id', label: 'ID', sortable: true },
            { key: 'tenant_id', label: 'Tenant ID', sortable: true },
            { key: 'plan_id', label: 'Plan ID', sortable: true },
            { key: 'total_amount', label: 'Amount', sortable: true },
            { key: 'status', label: 'Status', sortable: true },
            { key: 'created_at', label: 'Created', sortable: true },
            { key: 'actions', label: 'Actions', sortable: false }
        ]

        const fetchOrders = async () => {
            loading.value = true
            try {
                const response = await api.orders.list({ page: currentPage.value, per_page: perPage.value })
                if (response.data.success) {
                    orders.value = response.data.data.data || response.data.data || []
                    total.value = response.data.data.total || orders.value.length
                    totalPages.value = response.data.data.last_page || 1
                }
            } catch (error) {
                console.error('Error fetching orders:', error)
            } finally {
                loading.value = false
            }
        }

        watch(currentPage, () => {
            fetchOrders()
        })

        onMounted(() => {
            fetchOrders()
        })

        return { loading, orders, columns, currentPage, totalPages, total, perPage }
    }
}
</script>

<style scoped>
.orders-page {
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



