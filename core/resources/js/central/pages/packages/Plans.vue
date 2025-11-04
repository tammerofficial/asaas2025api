<template>
    <div class="plans-page">
        <div class="page-header">
            <h2>📋 Package Plans</h2>
            <p class="page-subtitle">View and manage all package plans</p>
        </div>

        <div v-if="loading" class="loading">Loading plans...</div>
        <DataTable
            v-else
            :columns="columns"
            :data="plans"
            :loading="loading"
            :pagination="true"
            :current-page="currentPage"
            :total-pages="totalPages"
            :total="total"
            :per-page="perPage"
            @update:current-page="currentPage = $event"
        >
            <template #cell-status="{ value }">
                <StatusBadge :status="value ? 'active' : 'inactive'" />
            </template>
        </DataTable>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import DataTable from '../../components/DataTable.vue'
import StatusBadge from '../../components/StatusBadge.vue'

export default {
    name: 'Plans',
    components: { DataTable, StatusBadge },
    setup() {
        const loading = ref(false)
        const plans = ref([])
        const currentPage = ref(1)
        const totalPages = ref(1)
        const total = ref(0)
        const perPage = ref(20)

        const columns = [
            { key: 'id', label: 'ID', sortable: true },
            { key: 'title', label: 'Title', sortable: true },
            { key: 'price', label: 'Price', sortable: true },
            { key: 'type', label: 'Type', sortable: false },
            { key: 'status', label: 'Status', sortable: true }
        ]

        const fetchPlans = async () => {
            loading.value = true
            try {
                const response = await api.plans.list({ page: currentPage.value, per_page: perPage.value })
                if (response.data.success) {
                    plans.value = response.data.data.data || response.data.data || []
                    total.value = response.data.data.total || plans.value.length
                    totalPages.value = response.data.data.last_page || 1
                }
            } catch (error) {
                console.error('Error fetching plans:', error)
            } finally {
                loading.value = false
            }
        }

        onMounted(() => {
            fetchPlans()
        })

        return { loading, plans, columns, currentPage, totalPages, total, perPage }
    }
}
</script>

<style scoped>
.plans-page {
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
</style>



