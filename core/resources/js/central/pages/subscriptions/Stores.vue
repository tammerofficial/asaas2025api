<template>
    <div class="stores-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">🏪 All Stores</h1>
                <p class="page-subtitle">Manage all tenant stores</p>
            </div>
        </div>

        <DataTable
            :columns="columns"
            :data="stores"
            :loading="loading"
            :pagination="pagination"
            @page-change="loadStores"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DataTable from '../../components/DataTable.vue'

const stores = ref([])
const loading = ref(false)
const pagination = ref({ current_page: 1, last_page: 1, per_page: 20, total: 0 })

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'domain', label: 'Domain' },
    { key: 'plan_name', label: 'Plan' },
    { key: 'status', label: 'Status' },
    { key: 'created_at', label: 'Created At' },
]

const loadStores = async (page = 1) => {
    loading.value = true
    try {
        const response = await axios.get('/subscriptions/stores', { params: { page } })
        if (response.data.success) {
            stores.value = response.data.data
            pagination.value = response.data.meta
        }
    } catch (error) {
        console.error('Error:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => loadStores())
</script>

<style scoped>
.stores-page { padding: 24px; }
.page-header { margin-bottom: 24px; }
.page-title { font-size: 24px; font-weight: 600; margin: 0 0 4px 0; }
.page-subtitle { color: #64748b; margin: 0; }
</style>
