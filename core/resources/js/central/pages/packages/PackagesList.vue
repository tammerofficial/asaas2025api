<template>
    <div class="packages-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">📦 Packages Management</h1>
                <p class="page-subtitle">Manage pricing plans and packages</p>
            </div>
            <div class="page-actions">
                <router-link to="/packages/create" class="btn btn-primary">
                    ➕ Add New Package
                </router-link>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search packages..."
                    class="search-input"
                    @input="debounceSearch"
                />
            </div>
            <select v-model="statusFilter" class="filter-select" @change="loadPackages(1)">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <!-- Packages Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading packages...</p>
            </div>
            
            <table v-else class="packages-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Features</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="packages.length === 0">
                        <td colspan="8" class="empty-cell">
                            No packages found
                        </td>
                    </tr>
                    <tr v-else v-for="pkg in packages" :key="pkg.id">
                        <td>{{ pkg.id }}</td>
                        <td>
                            <div class="package-title">
                                <strong>{{ pkg.title || pkg.name }}</strong>
                            </div>
                        </td>
                        <td>
                            <strong class="price-text">{{ formatPrice(pkg.price) }}</strong>
                        </td>
                        <td>
                            <span class="type-badge" :class="getTypeClass(pkg.type)">
                                {{ getTypeName(pkg.type) }}
                            </span>
                        </td>
                        <td>
                            <span class="features-count">
                                {{ Array.isArray(pkg.features) ? pkg.features.length : 0 }} features
                            </span>
                        </td>
                        <td>
                            <StatusBadge :status="pkg.status ? 'active' : 'inactive'" />
                        </td>
                        <td>{{ formatDate(pkg.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="editPackage(pkg)"
                                    title="Edit"
                                >
                                    ✏️
                                </button>
                                <button 
                                    class="btn-icon btn-danger" 
                                    @click="deletePackage(pkg)"
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
        <div v-if="!loading && packages.length > 0 && pagination.last_page > 1" class="pagination">
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page === 1"
                @click="loadPackages(pagination.current_page - 1)"
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
                @click="loadPackages(pagination.current_page + 1)"
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

const packages = ref([])
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

const loadPackages = async (page = 1) => {
    loading.value = true
    try {
        const params = {
            page,
            per_page: pagination.value.per_page
        }
        
        if (searchQuery.value) {
            params.search = searchQuery.value
        }
        
        if (statusFilter.value !== '') {
            params.status = statusFilter.value
        }
        
        const response = await api.packages.list(params)
        
        if (response.data.success) {
            packages.value = response.data.data || []
            if (response.data.meta) {
                pagination.value = response.data.meta
            }
        }
    } catch (error) {
        console.error('Error loading packages:', error)
        packages.value = []
    } finally {
        loading.value = false
    }
}

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        loadPackages(1)
    }, 500)
}

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', { 
        style: 'currency', 
        currency: 'KWD',
        minimumFractionDigits: 3,
        maximumFractionDigits: 3
    }).format(price || 0)
}

const getTypeName = (type) => {
    if (type === 1 || type === 'yearly') return 'Yearly'
    return 'Monthly'
}

const getTypeClass = (type) => {
    if (type === 1 || type === 'yearly') return 'yearly'
    return 'monthly'
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

const editPackage = (pkg) => {
    router.push(`/packages/${pkg.id}/edit`)
}

const deletePackage = async (pkg) => {
    if (!confirm(`Are you sure you want to delete package "${pkg.title || pkg.name}"?`)) {
        return
    }
    
    try {
        await api.packages.delete(pkg.id)
        await loadPackages(pagination.value.current_page)
    } catch (error) {
        console.error('Error deleting package:', error)
        alert('Failed to delete package')
    }
}

onMounted(() => {
    loadPackages()
})
</script>

<style scoped>
.packages-page {
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

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background 0.2s;
    font-size: 15px;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
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

.packages-table {
    width: 100%;
    border-collapse: collapse;
}

.packages-table thead {
    background: #f8fafc;
}

.packages-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.packages-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.package-title strong {
    color: #1e293b;
}

.price-text {
    color: #059669;
    font-size: 16px;
}

.type-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.type-badge.monthly {
    background: #dbeafe;
    color: #1e40af;
}

.type-badge.yearly {
    background: #fef3c7;
    color: #92400e;
}

.features-count {
    color: #64748b;
    font-size: 14px;
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
