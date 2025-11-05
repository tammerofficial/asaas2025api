<template>
    <div class="categories-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">📂 Blog Categories</h1>
                <p class="page-subtitle">Manage blog categories</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary" @click="showAddModal = true">
                    ➕ Add Category
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
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Posts</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="categories.length === 0">
                        <td colspan="8" class="empty-cell">
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
                            <code class="slug-code">{{ category.slug }}</code>
                        </td>
                        <td>
                            <span class="description-text">{{ category.description || '-' }}</span>
                        </td>
                        <td>{{ category.posts_count || 0 }}</td>
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
        <div v-if="!loading && categories.length > 0 && pagination.last_page > 1" class="pagination">
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page === 1"
                @click="loadCategories(pagination.current_page - 1)"
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
                @click="loadCategories(pagination.current_page + 1)"
            >
                Next →
            </button>
        </div>

        <!-- Add/Edit Modal -->
        <div v-if="showAddModal || editingCategory" class="modal-overlay" @click="closeModal">
            <div class="modal-content" @click.stop>
                <div class="modal-header">
                    <h3>{{ editingCategory ? '✏️ Edit Category' : '➕ Add Category' }}</h3>
                    <button class="modal-close" @click="closeModal">✕</button>
                </div>
                <form @submit.prevent="handleSubmit" class="modal-form">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Name <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            class="form-input"
                            :class="{ 'error': errors.name }"
                            placeholder="Category name"
                            required
                        />
                        <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">Slug</label>
                        <input
                            type="text"
                            id="slug"
                            v-model="form.slug"
                            class="form-input"
                            :class="{ 'error': errors.slug }"
                            placeholder="category-slug (auto-generated)"
                        />
                        <small class="form-help">Leave empty to auto-generate from name</small>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            class="form-textarea"
                            rows="4"
                            placeholder="Category description"
                        ></textarea>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" v-model="form.status" class="form-select">
                            <option :value="true">Active</option>
                            <option :value="false">Inactive</option>
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
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

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
    slug: '',
    description: '',
    status: true
})

const errors = ref({})

let searchTimeout = null

const loadCategories = async (page = 1) => {
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
        
        const response = await api.blog.categories(params)
        
        if (response.data.success) {
            categories.value = response.data.data || []
            if (response.data.meta) {
                pagination.value = response.data.meta
            }
        }
    } catch (error) {
        console.error('Error loading categories:', error)
        categories.value = []
    } finally {
        loading.value = false
    }
}

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        loadCategories(1)
    }, 500)
}

const generateSlug = (name) => {
    return name
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '')
}

const editCategory = (category) => {
    editingCategory.value = category
    form.value = {
        name: category.name || '',
        slug: category.slug || '',
        description: category.description || '',
        status: category.status !== undefined ? category.status : true
    }
    showAddModal.value = true
}

const deleteCategory = async (category) => {
    if (!confirm(`Are you sure you want to delete category "${category.name}"?`)) {
        return
    }
    
    try {
        // Note: API endpoint might need to be added for delete
        await api.blog.delete(`categories/${category.id}`)
        await loadCategories(pagination.value.current_page)
    } catch (error) {
        console.error('Error deleting category:', error)
        alert('Failed to delete category')
    }
}

const handleSubmit = async () => {
    errors.value = {}
    loading.value = true

    try {
        // Auto-generate slug if empty
        if (!form.value.slug && form.value.name) {
            form.value.slug = generateSlug(form.value.name)
        }

        let response
        if (editingCategory.value) {
            // Update existing category
            response = await api.blog.update(`categories/${editingCategory.value.id}`, form.value)
        } else {
            // Create new category
            response = await api.blog.create({ ...form.value, type: 'category' })
        }

        if (response.data.success) {
            closeModal()
            await loadCategories(pagination.value.current_page)
        } else {
            if (response.data.errors) {
                errors.value = response.data.errors
            } else {
                alert('Failed to save category: ' + (response.data.message || 'Unknown error'))
            }
        }
    } catch (error) {
        console.error('Error saving category:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            alert('Failed to save category: ' + (error.response?.data?.message || 'Unknown error'))
        }
    } finally {
        loading.value = false
    }
}

const closeModal = () => {
    showAddModal.value = false
    editingCategory.value = null
    form.value = {
        name: '',
        slug: '',
        description: '',
        status: true
    }
    errors.value = {}
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

onMounted(() => {
    loadCategories()
})
</script>

<style scoped>
.categories-page {
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

.btn-primary:hover:not(:disabled) {
    background: #2563eb;
}

.btn-primary:disabled {
    background: #9ca3af;
    cursor: not-allowed;
}

.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #d1d5db;
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
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.category-name strong {
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

.description-text {
    color: #64748b;
    font-size: 14px;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: inline-block;
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

/* Modal */
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
    z-index: 10000;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    color: #64748b;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: background 0.2s;
}

.modal-close:hover {
    background: #f1f5f9;
}

.modal-form {
    padding: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    margin-bottom: 8px;
}

.required {
    color: #ef4444;
}

.form-input,
.form-textarea,
.form-select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
    font-family: inherit;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-input.error,
.form-textarea.error,
.form-select.error {
    border-color: #ef4444;
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.form-help {
    display: block;
    color: #64748b;
    font-size: 13px;
    margin-top: 4px;
}

.error-message {
    display: block;
    color: #ef4444;
    font-size: 13px;
    margin-top: 4px;
}

.modal-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
}
</style>
