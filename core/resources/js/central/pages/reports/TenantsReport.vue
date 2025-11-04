<template>
    <div class="report-page">
        <div class="page-header">
            <h2>📊 Tenants Report</h2>
            <p class="page-subtitle">Tenants statistics and analytics</p>
        </div>

        <div v-if="loading" class="loading">Loading report...</div>
        <div v-else-if="report" class="report-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ report.total_tenants || 0 }}</div>
                    <div class="stat-label">Total Tenants</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ report.active_tenants || 0 }}</div>
                    <div class="stat-label">Active Tenants</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ report.inactive_tenants || 0 }}</div>
                    <div class="stat-label">Inactive Tenants</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ report.new_tenants_this_month || 0 }}</div>
                    <div class="stat-label">New This Month</div>
                </div>
            </div>

            <div class="report-section">
                <h3>Tenant Details</h3>
                <DataTable
                    :columns="columns"
                    :data="report.tenants || []"
                    :loading="false"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import DataTable from '../../components/DataTable.vue'

export default {
    name: 'TenantsReport',
    components: { DataTable },
    setup() {
        const loading = ref(false)
        const report = ref(null)

        const columns = [
            { key: 'id', label: 'ID', sortable: true },
            { key: 'name', label: 'Name', sortable: true },
            { key: 'status', label: 'Status', sortable: true },
            { key: 'created_at', label: 'Created', sortable: true }
        ]

        const fetchReport = async () => {
            loading.value = true
            try {
                const response = await api.reports.tenants()
                if (response.data.success) {
                    report.value = response.data.data
                }
            } catch (error) {
                console.error('Error fetching report:', error)
            } finally {
                loading.value = false
            }
        }

        onMounted(() => {
            fetchReport()
        })

        return { loading, report, columns }
    }
}
</script>

<style scoped>
.report-page {
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card {
    background: white;
    padding: 24px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 14px;
    color: #64748b;
}

.report-section {
    background: white;
    padding: 24px;
    border-radius: 8px;
}

.report-section h3 {
    margin: 0 0 16px 0;
    font-size: 18px;
    color: #1e293b;
}
</style>



