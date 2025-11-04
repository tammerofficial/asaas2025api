<template>
    <div class="subscribers-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">📮 All Subscribers</h1>
                <p class="page-subtitle">Manage all users with active subscriptions</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary">
                    ➕ Add New
                </button>
            </div>
        </div>

        <DataTable
            :columns="columns"
            :data="subscribers"
            :loading="loading"
            :pagination="pagination"
            @page-change="loadSubscribers"
            @search="handleSearch"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DataTable from '../../components/DataTable.vue'

const subscribers = ref([])
const loading = ref(false)
const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 20,
    total: 0
})

const columns = [
    { key: 'id', label: 'ID', sortable: true },
    { key: 'name', label: 'Name', sortable: true },
    { key: 'email', label: 'Email', sortable: true },
    { key: 'tenant_id', label: 'Tenant ID', sortable: true },
    { key: 'created_at', label: 'Created At', sortable: true },
    { key: 'actions', label: 'Actions', sortable: false }
]

const loadSubscribers = async (page = 1) => {
    loading.value = true
    try {
        const response = await axios.get('/subscriptions/subscribers', {
            params: { page, per_page: pagination.value.per_page }
        })
        
        if (response.data.success) {
            subscribers.value = response.data.data
            pagination.value = response.data.meta
        }
    } catch (error) {
        console.error('Error loading subscribers:', error)
    } finally {
        loading.value = false
    }
}

const handleSearch = (searchTerm) => {
    // Implement search if needed
    loadSubscribers(1)
}

onMounted(() => {
    loadSubscribers()
})
</script>

<style scoped>
.subscribers-page {
    padding: 24px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 4px 0;
}

.page-subtitle {
    color: #64748b;
    margin: 0;
}

.page-actions {
    display: flex;
    gap: 12px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}
</style>
