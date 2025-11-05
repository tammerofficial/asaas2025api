<template>
    <div class="support-categories-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🎫 Support Ticket Categories</h1>
                <p class="page-subtitle">Manage support ticket categories</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary" @click="showAddModal = true">
                    ➕ Add New
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search categories..."
                    class="search-input"
                    @input="debounceSearch"
                />
            </div>
            <select v-model="statusFilter" class="filter-select" @change="loadCategories(1)">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <!-- Categories Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading categories...</p>
            </div>
            
            <table v-else class="categories-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="categories.length === 0">
                        <td colspan="5" class="empty-cell">
                            No categories found
                        </td>
                    </tr>
                    <tr v-else v-for="category in categories" :key="category.id">
                        <td>{{ category.id }}</td>
                        <td>
                            <div class="category-name">
                                <strong>{{ category.name }}</strong>
                            </div>
                        </td>
                        <td>
                            <StatusBadge :status="category.status ? 'active' : 'inactive'" />
                        </td>
                        <td>{{ formatDate(category.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="editCategory(category)"
                                    title="Edit"
                                >
                                    ✏️
                                </button>
                                <button 
                                    class="btn-icon btn-danger" 
                                    @click="deleteCategory(category)"
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
        <div v-if="pagination.last_page > 1" class="pagination">
            <button 
                class="btn btn-sm" 
                :disabled="pagination.current_page === 1"
                @click="loadCategories(pagination.current_page - 1)"
            >
                ← Previous
            </button>
            <span class="pagination-info">
                Page {{ pagination.current_page }} of {{ pagination.last_page }}
                (Total: {{ pagination.total }})
            </span>
            <button 
                class="btn btn-sm" 
                :disabled="pagination.current_page === pagination.last_page"
                @click="loadCategories(pagination.current_page + 1)"
            >
                Next →
            </button>
        </div>

        <!-- Add/Edit Modal -->
        <div v-if="showAddModal || editingCategory" class="modal-overlay" @click="closeModal">
            <div class="modal-content" @click.stop>
                <div class="modal-header">
                    <h3>{{ editingCategory ? '✏️ Edit Category' : '➕ Add New Category' }}</h3>
                    <button class="modal-close" @click="closeModal">×</button>
                </div>

                <form @submit.prevent="saveCategory" class="modal-form">
                    <div class="form-group">
                        <label for="name" class="form-label">Name <span class="required">*</span></label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="form-input"
                            :class="{ 'error': errors.name }"
                            placeholder="Category name"
                            required
                        />
                        <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" v-model="form.status" class="form-select">
                            <option :value="1">Active</option>
                            <option :value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="modal-actions">
                        <button type="submit" class="btn btn-primary" :disabled="loading">
                            <span v-if="loading">⏳ Saving...</span>
                            <span v-else>💾 Save</span>
                        </button>
                        <button type="button" class="btn btn-secondary" @click="closeModal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import StatusBadge from '../../components/StatusBadge.vue'
import Toast from '../../components/Toast.vue'

const categories = ref([])
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const showAddModal = ref(false)
const editingCategory = ref(null)
const pagination = ref({ 
    current_page: 1, 
    last_page: 1, 
    per_page: 20, 
    total: 0 
})

const form = ref({
    name: '',
    status: 1
})

const errors = ref({})
const showToast = ref(false)
const toastType = ref('success')
const toastTitle = ref('')
const toastMessage = ref('')

let searchTimeout = null

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        loadCategories(1)
    }, 500)
}

const loadCategories = async (page = 1) => {
    loading.value = true
    try {
        const params = {
            page,
            per_page: 20,
        }
        
        if (searchQuery.value) {
            params.search = searchQuery.value
        }
        
        if (statusFilter.value !== '') {
            params.status = statusFilter.value
        }
        
        const response = await axios.get('/support/ticket-categories', { params })
        
        if (response.data.success) {
            categories.value = response.data.data
            pagination.value = response.data.meta
        }
    } catch (error) {
        console.error('Error loading categories:', error)
        showToastMessage('error', 'Error', 'Failed to load categories')
    } finally {
        loading.value = false
    }
}

const saveCategory = async () => {
    errors.value = {}
    loading.value = true
    
    try {
        const url = editingCategory.value 
            ? `/support/ticket-categories/${editingCategory.value.id}`
            : '/support/ticket-categories'
        
        const method = editingCategory.value ? 'put' : 'post'
        
        const response = await axios[method](url, form.value)
        
        if (response.data.success) {
            showToastMessage('success', 'Success', response.data.message)
            closeModal()
            loadCategories(pagination.value.current_page)
        }
    } catch (error) {
        console.error('Error saving category:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            showToastMessage('error', 'Error', error.response?.data?.message || 'Failed to save category')
        }
    } finally {
        loading.value = false
    }
}

const editCategory = (category) => {
    editingCategory.value = category
    form.value = {
        name: category.name,
        status: category.status
    }
    showAddModal.value = true
}

const deleteCategory = async (category) => {
    if (!confirm(`Are you sure you want to delete "${category.name}"?`)) {
        return
    }
    
    try {
        const response = await axios.delete(`/support/ticket-categories/${category.id}`)
        
        if (response.data.success) {
            showToastMessage('success', 'Success', response.data.message)
            loadCategories(pagination.value.current_page)
        }
    } catch (error) {
        console.error('Error deleting category:', error)
        showToastMessage('error', 'Error', error.response?.data?.message || 'Failed to delete category')
    }
}

const closeModal = () => {
    showAddModal.value = false
    editingCategory.value = null
    form.value = {
        name: '',
        status: 1
    }
    errors.value = {}
}

const formatDate = (date) => {
    if (!date) return 'N/A'
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const showToastMessage = (type, title, message) => {
    toastType.value = type
    toastTitle.value = title
    toastMessage.value = message
    showToast.value = true
}

onMounted(() => {
    loadCategories()
})
</script>

<style scoped>
.support-categories-page {
    padding: 24px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
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

.filters-bar {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
}

.search-box {
    flex: 1;
}

.search-input {
    width: 100%;
    padding: 10px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
}

.filter-select {
    padding: 10px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    min-width: 150px;
}

.table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    position: relative;
    min-height: 200px;
}

.loading-overlay {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px;
    color: #64748b;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f1f5f9;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 16px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.categories-table {
    width: 100%;
    border-collapse: collapse;
}

.categories-table thead {
    background: #f8fafc;
}

.categories-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.categories-table td {
    padding: 16px;
    border-top: 1px solid #f1f5f9;
    color: #334155;
}

.empty-cell {
    text-align: center;
    padding: 60px !important;
    color: #94a3b8;
}

.category-name {
    font-weight: 500;
    color: #1e293b;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-icon {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    background: #f1f5f9;
    transition: background 0.2s;
}

.btn-icon:hover {
    background: #e2e8f0;
}

.btn-icon.btn-danger:hover {
    background: #fee2e2;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 16px;
    margin-top: 24px;
    padding: 20px;
}

.pagination-info {
    color: #64748b;
    font-size: 14px;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-bottom: 1px solid #f1f5f9;
}

.modal-header h3 {
    margin: 0;
    font-size: 20px;
    color: #1e293b;
}

.modal-close {
    background: none;
    border: none;
    font-size: 28px;
    color: #94a3b8;
    cursor: pointer;
    line-height: 1;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-form {
    padding: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #334155;
    font-size: 14px;
}

.required {
    color: #ef4444;
}

.form-input,
.form-select {
    width: 100%;
    padding: 10px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-input.error {
    border-color: #ef4444;
}

.error-message {
    display: block;
    margin-top: 6px;
    color: #ef4444;
    font-size: 13px;
}

.modal-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: #2563eb;
}

.btn-secondary {
    background: #f1f5f9;
    color: #475569;
}

.btn-secondary:hover {
    background: #e2e8f0;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}
</style>
