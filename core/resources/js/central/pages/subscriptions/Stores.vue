<template>
    <div class="stores-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🏪 All Stores</h1>
                <p class="page-subtitle">Manage all tenant stores</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search stores..."
                    class="search-input"
                    @input="debounceSearch"
                />
            </div>
            <select v-model="statusFilter" class="filter-select" @change="loadStores(1)">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>

        <!-- Stores Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading stores...</p>
            </div>
            
            <table v-else class="stores-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Store Name</th>
                        <th>Domain</th>
                        <th>Tenant ID</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="stores.length === 0">
                        <td colspan="8" class="empty-cell">
                            No stores found
                        </td>
                    </tr>
                    <tr v-else v-for="store in stores" :key="store.id">
                        <td>{{ store.id }}</td>
                        <td>
                            <div class="store-info">
                                <div class="store-name">{{ store.name || store.store_name || 'N/A' }}</div>
                            </div>
                        </td>
                        <td>
                            <a :href="'http://' + (store.domain || store.old_domain)" target="_blank" class="domain-link">
                                {{ store.domain || store.old_domain || 'N/A' }}
                            </a>
                        </td>
                        <td>
                            <span class="tenant-badge">{{ store.tenant_id || 'N/A' }}</span>
                        </td>
                        <td>{{ store.plan_name || store.package_name || 'N/A' }}</td>
                        <td>
                            <StatusBadge :status="store.status || 'active'" />
                        </td>
                        <td>{{ formatDate(store.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="viewStore(store)"
                                    title="View"
                                >
                                    👁️
                                </button>
                                <button 
                                    class="btn-icon" 
                                    @click="editStore(store)"
                                    title="Edit"
                                >
                                    ✏️
                                </button>
                                <button 
                                    class="btn-icon btn-danger" 
                                    @click="deleteStore(store)"
                                    title="Delete"
                                >
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="!loading && stores.length > 0 && pagination.last_page > 1" class="pagination">
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page === 1"
                @click="loadStores(pagination.current_page - 1)"
            >
                ← Previous
            </button>
            <span class="pagination-info">
                Page {{ pagination.current_page }} of {{ pagination.last_page }}
                ({{ pagination.total }} total)
            </span>
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page >= pagination.last_page"
                @click="loadStores(pagination.current_page + 1)"
            >
                Next →
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

const router = useRouter()

const stores = ref([])
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const pagination = ref({ 
    current_page: 1, 
    last_page: 1, 
    per_page: 20, 
    total: 0 
})

let searchTimeout = null

const loadStores = async (page = 1) => {
    loading.value = true
    try {
        const params = {
            page,
            per_page: pagination.value.per_page
        }
        
        if (searchQuery.value) {
            params.search = searchQuery.value
        }
        
        if (statusFilter.value) {
            params.status = statusFilter.value
        }
        
        const response = await api.subscriptions.stores(params)
        
        if (response.data.success) {
            stores.value = response.data.data || []
            if (response.data.meta) {
                pagination.value = response.data.meta
            }
        }
    } catch (error) {
        console.error('Error loading stores:', error)
        stores.value = []
    } finally {
        loading.value = false
    }
}

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        loadStores(1)
    }, 500)
}

const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const viewStore = (store) => {
    console.log('View store:', store)
}

const editStore = (store) => {
    console.log('Edit store:', store)
}

const deleteStore = async (store) => {
    if (!confirm(`Are you sure you want to delete store "${store.name || store.store_name}"?`)) {
        return
    }
    
    try {
        // API call to delete store
        await loadStores(pagination.value.current_page)
    } catch (error) {
        console.error('Error deleting store:', error)
        alert('Failed to delete store')
    }
}

onMounted(() => {
    loadStores()
})
</script>

<style scoped>
.stores-page {
    padding: 24px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.page-subtitle {
    color: #64748b;
    font-size: 15px;
    margin: 0;
}

/* Filters */
.filters-bar {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.search-box {
    flex: 1;
}

.search-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
}

.filter-select {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    background: white;
}

/* Table */
.table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    position: relative;
    min-height: 400px;
}

.stores-table {
    width: 100%;
    border-collapse: collapse;
}

.stores-table thead {
    background: #f8fafc;
}

.stores-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stores-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.store-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.store-name {
    font-weight: 500;
    color: #1e293b;
}

.domain-link {
    color: #3b82f6;
    text-decoration: none;
    font-weight: 500;
}

.domain-link:hover {
    text-decoration: underline;
}

.tenant-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-icon {
    background: #f1f5f9;
    border: none;
    padding: 8px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.2s;
}

.btn-icon:hover {
    background: #e2e8f0;
}

.btn-icon.btn-danger:hover {
    background: #fee2e2;
}

.empty-cell {
    text-align: center;
    padding: 40px !important;
    color: #64748b;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e5e7eb;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-bottom: 16px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-top: 30px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.pagination-btn {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
}

.pagination-btn:hover:not(:disabled) {
    background: #2563eb;
}

.pagination-btn:disabled {
    background: #e5e7eb;
    color: #94a3b8;
    cursor: not-allowed;
}

.pagination-info {
    color: #64748b;
    font-weight: 500;
}
</style>
