<template>
    <div class="report-page">
        <div class="page-header">
            <h2>📦 Plans Report</h2>
            <p class="page-subtitle">Package plans statistics</p>
        </div>

        <div v-if="loading" class="loading">Loading report...</div>
        <div v-else-if="report" class="report-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ report.total_plans || 0 }}</div>
                    <div class="stat-label">Total Plans</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ report.active_plans || 0 }}</div>
                    <div class="stat-label">Active Plans</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ report.total_subscribers || 0 }}</div>
                    <div class="stat-label">Total Subscribers</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

export default {
    name: 'PlansReport',
    setup() {
        const loading = ref(false)
        const report = ref(null)

        const fetchReport = async () => {
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

        onMounted(() => {
            fetchReport()
        })

        return { loading, report }
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
</style>



