<template>
    <div class="pages-list-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">📄 All Pages</h1>
                <p class="page-subtitle">Manage all website pages</p>
            </div>
            <router-link to="/pages/create" class="btn btn-primary">
                ➕ Add New Page
            </router-link>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search pages..."
                    class="search-input"
                    @input="debounceSearch"
                />
            </div>
            <select v-model="statusFilter" class="filter-select" @change="loadPages(1)">
                <option value="">All Status</option>
                <option value="1">Published</option>
                <option value="0">Draft</option>
            </select>
        </div>

        <!-- Pages Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading pages...</p>
            </div>
            
            <table v-else class="pages-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Visibility</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="pages.length === 0">
                        <td colspan="7" class="empty-cell">
                            No pages found
                        </td>
                    </tr>
                    <tr v-else v-for="page in pages" :key="page.id">
                        <td>{{ page.id }}</td>
                        <td>
                            <div class="page-title">
                                <strong>{{ page.title }}</strong>
                            </div>
                        </td>
                        <td>
                            <code class="slug-code">{{ page.slug }}</code>
                        </td>
                        <td>
                            <StatusBadge :status="page.status ? 'published' : 'draft'" />
                        </td>
                        <td>
                            <span class="visibility-badge" :class="page.visibility ? 'public' : 'private'">
                                {{ page.visibility ? 'Public' : 'Private' }}
                            </span>
                        </td>
                        <td>{{ formatDate(page.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="editPage(page)"
                                    title="Edit"
                                >
                                    ✏️
                                </button>
                                <button 
                                    class="btn-icon btn-danger" 
                                    @click="deletePage(page)"
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
        <div v-if="!loading && pages.length > 0 && pagination.last_page > 1" class="pagination">
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page === 1"
                @click="loadPages(pagination.current_page - 1)"
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
                @click="loadPages(pagination.current_page + 1)"
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

const pages = ref([])
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

const loadPages = async (page = 1) => {
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
        
        const response = await api.pages.list(params)
        
        if (response.data.success) {
            pages.value = response.data.data
            if (response.data.meta) {
                pagination.value = response.data.meta
            }
        }
    } catch (error) {
        console.error('Error loading pages:', error)
        pages.value = []
    } finally {
        loading.value = false
    }
}

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        loadPages(1)
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

const editPage = (page) => {
    router.push(`/pages/edit/${page.id}`)
}

const deletePage = async (page) => {
    if (!confirm(`Are you sure you want to delete "${page.title}"?`)) {
        return
    }
    
    try {
        await api.pages.delete(page.id)
        await loadPages(pagination.value.current_page)
    } catch (error) {
        console.error('Error deleting page:', error)
        alert('Failed to delete page')
    }
}

onMounted(() => {
    loadPages()
})
</script>

<style scoped>
.pages-list-page {
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

.pages-table {
    width: 100%;
    border-collapse: collapse;
}

.pages-table thead {
    background: #f8fafc;
}

.pages-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pages-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.page-title strong {
    color: #1e293b;
}

.slug-code {
    background: #f1f5f9;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 13px;
    color: #475569;
    font-family: monospace;
}

.visibility-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.visibility-badge.public {
    background: #d1fae5;
    color: #065f46;
}

.visibility-badge.private {
    background: #fee2e2;
    color: #991b1b;
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
