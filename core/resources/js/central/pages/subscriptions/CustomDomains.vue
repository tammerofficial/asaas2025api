<template>
    <div class="custom-domains-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">🌐 Custom Domains</h1>
                <p class="page-subtitle">Manage tenant custom domains</p>
            </div>
        </div>

        <DataTable
            :columns="columns"
            :data="domains"
            :loading="loading"
            :pagination="pagination"
            @page-change="loadDomains"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DataTable from '../../components/DataTable.vue'

const domains = ref([])
const loading = ref(false)
const pagination = ref({ current_page: 1, last_page: 1, per_page: 20, total: 0 })

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'custom_domain', label: 'Custom Domain' },
    { key: 'old_domain', label: 'Old Domain' },
    { key: 'status', label: 'Status' },
    { key: 'tenant_id', label: 'Tenant ID' },
    { key: 'created_at', label: 'Created At' },
]

const loadDomains = async (page = 1) => {
    loading.value = true
    try {
        const response = await axios.get('/subscriptions/custom-domains', { params: { page } })
        if (response.data.success) {
            domains.value = response.data.data
            pagination.value = response.data.meta
        }
    } catch (error) {
        console.error('Error:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => loadDomains())
</script>

<style scoped>
.custom-domains-page { padding: 24px; }
.page-header { margin-bottom: 24px; }
.page-title { font-size: 24px; font-weight: 600; margin: 0 0 4px 0; }
.page-subtitle { color: #64748b; margin: 0; }
</style>
