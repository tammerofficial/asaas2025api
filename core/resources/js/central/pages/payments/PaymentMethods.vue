<template>
    <div class="payment-methods-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">💳 Payment Methods</h1>
                <p class="page-subtitle">Manage payment gateways</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary" @click="showAddModal = true">
                    ➕ Add New
                </button>
            </div>
        </div>

        <div class="filters-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search payment methods..."
                    class="search-input"
                    @input="debounceSearch"
                />
            </div>
            <select v-model="statusFilter" class="filter-select" @change="loadMethods(1)">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading payment methods...</p>
            </div>
            
            <table v-else class="methods-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Test Mode</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="methods.length === 0">
                        <td colspan="7" class="empty-cell">No payment methods found</td>
                    </tr>
                    <tr v-else v-for="method in methods" :key="method.id">
                        <td>{{ method.id }}</td>
                        <td><strong>{{ method.name }}</strong></td>
                        <td>{{ method.description || '-' }}</td>
                        <td><StatusBadge :status="method.status ? 'active' : 'inactive'" /></td>
                        <td><StatusBadge :status="method.test_mode ? 'active' : 'inactive'" /></td>
                        <td>{{ formatDate(method.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon" @click="editMethod(method)" title="Edit">✏️</button>
                                <button class="btn-icon btn-danger" @click="deleteMethod(method)" title="Delete">🗑️</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="pagination.last_page > 1" class="pagination">
            <button class="btn btn-sm" :disabled="pagination.current_page === 1" @click="loadMethods(pagination.current_page - 1)">← Previous</button>
            <span class="pagination-info">Page {{ pagination.current_page }} of {{ pagination.last_page }} (Total: {{ pagination.total }})</span>
            <button class="btn btn-sm" :disabled="pagination.current_page === pagination.last_page" @click="loadMethods(pagination.current_page + 1)">Next →</button>
        </div>

        <div v-if="showAddModal || editingMethod" class="modal-overlay" @click="closeModal">
            <div class="modal-content" @click.stop>
                <div class="modal-header">
                    <h3>{{ editingMethod ? '✏️ Edit Payment Method' : '➕ Add New Payment Method' }}</h3>
                    <button class="modal-close" @click="closeModal">×</button>
                </div>
                <form @submit.prevent="saveMethod" class="modal-form">
                    <div class="form-group">
                        <label class="form-label">Name <span class="required">*</span></label>
                        <input v-model="form.name" type="text" class="form-input" :class="{ 'error': errors.name }" required />
                        <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea v-model="form.description" class="form-textarea" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select v-model="form.status" class="form-select">
                            <option :value="1">Active</option>
                            <option :value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Test Mode</label>
                        <select v-model="form.test_mode" class="form-select">
                            <option :value="1">Yes</option>
                            <option :value="0">No</option>
                        </select>
                    </div>
                    <div class="modal-actions">
                        <button type="submit" class="btn btn-primary" :disabled="loading">
                            <span v-if="loading">⏳ Saving...</span>
                            <span v-else>💾 Save</span>
                        </button>
                        <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
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

const methods = ref([])
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const showAddModal = ref(false)
const editingMethod = ref(null)
const pagination = ref({ current_page: 1, last_page: 1, per_page: 20, total: 0 })

const form = ref({ name: '', description: '', status: 0, test_mode: 0 })
const errors = ref({})
const showToast = ref(false)
const toastType = ref('success')
const toastTitle = ref('')
const toastMessage = ref('')

let searchTimeout = null

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => loadMethods(1), 500)
}

const loadMethods = async (page = 1) => {
    loading.value = true
    try {
        const params = { page, per_page: 20 }
        if (searchQuery.value) params.search = searchQuery.value
        if (statusFilter.value !== '') params.status = statusFilter.value
        
        const response = await axios.get('/payment-methods', { params })
        if (response.data.success) {
            methods.value = response.data.data
            pagination.value = response.data.meta
        }
    } catch (error) {
        console.error('Error loading methods:', error)
        showToastMessage('error', 'Error', 'Failed to load payment methods')
    } finally {
        loading.value = false
    }
}

const saveMethod = async () => {
    errors.value = {}
    loading.value = true
    try {
        const url = editingMethod.value ? `/payment-methods/${editingMethod.value.id}` : '/payment-methods'
        const method = editingMethod.value ? 'put' : 'post'
        const response = await axios[method](url, form.value)
        
        if (response.data.success) {
            showToastMessage('success', 'Success', response.data.message)
            closeModal()
            loadMethods(pagination.value.current_page)
        }
    } catch (error) {
        console.error('Error saving method:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            showToastMessage('error', 'Error', error.response?.data?.message || 'Failed to save payment method')
        }
    } finally {
        loading.value = false
    }
}

const editMethod = (method) => {
    editingMethod.value = method
    form.value = { name: method.name, description: method.description, status: method.status, test_mode: method.test_mode }
    showAddModal.value = true
}

const deleteMethod = async (method) => {
    if (!confirm(`Are you sure you want to delete "${method.name}"?`)) return
    try {
        const response = await axios.delete(`/payment-methods/${method.id}`)
        if (response.data.success) {
            showToastMessage('success', 'Success', response.data.message)
            loadMethods(pagination.value.current_page)
        }
    } catch (error) {
        console.error('Error deleting method:', error)
        showToastMessage('error', 'Error', error.response?.data?.message || 'Failed to delete payment method')
    }
}

const closeModal = () => {
    showAddModal.value = false
    editingMethod.value = null
    form.value = { name: '', description: '', status: 0, test_mode: 0 }
    errors.value = {}
}

const formatDate = (date) => {
    if (!date) return 'N/A'
    return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

const showToastMessage = (type, title, message) => {
    toastType.value = type
    toastTitle.value = title
    toastMessage.value = message
    showToast.value = true
}

onMounted(() => loadMethods())
</script>

<style scoped>
.payment-methods-page { padding: 24px; }
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
.page-title { font-size: 28px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0; }
.page-subtitle { color: #64748b; font-size: 15px; margin: 0; }
.filters-bar { display: flex; gap: 16px; margin-bottom: 24px; }
.search-box { flex: 1; }
.search-input, .filter-select { width: 100%; padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }
.filter-select { min-width: 150px; }
.table-container { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); position: relative; min-height: 200px; }
.loading-overlay { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px; color: #64748b; }
.spinner { width: 40px; height: 40px; border: 4px solid #f1f5f9; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 16px; }
@keyframes spin { to { transform: rotate(360deg); } }
.methods-table { width: 100%; border-collapse: collapse; }
.methods-table thead { background: #f8fafc; }
.methods-table th { padding: 16px; text-align: left; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
.methods-table td { padding: 16px; border-top: 1px solid #f1f5f9; color: #334155; }
.empty-cell { text-align: center; padding: 60px !important; color: #94a3b8; }
.action-buttons { display: flex; gap: 8px; }
.btn-icon { padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; background: #f1f5f9; transition: background 0.2s; }
.btn-icon:hover { background: #e2e8f0; }
.btn-icon.btn-danger:hover { background: #fee2e2; }
.pagination { display: flex; justify-content: center; align-items: center; gap: 16px; margin-top: 24px; padding: 20px; }
.pagination-info { color: #64748b; font-size: 14px; }
.modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; }
.modal-content { background: white; border-radius: 12px; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto; }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 24px; border-bottom: 1px solid #f1f5f9; }
.modal-header h3 { margin: 0; font-size: 20px; color: #1e293b; }
.modal-close { background: none; border: none; font-size: 28px; color: #94a3b8; cursor: pointer; line-height: 1; padding: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; }
.modal-form { padding: 24px; }
.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; color: #334155; font-size: 14px; }
.required { color: #ef4444; }
.form-input, .form-select, .form-textarea { width: 100%; padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: border-color 0.2s; }
.form-textarea { resize: vertical; min-height: 80px; }
.form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: #3b82f6; }
.form-input.error { border-color: #ef4444; }
.error-message { display: block; margin-top: 6px; color: #ef4444; font-size: 13px; }
.modal-actions { display: flex; gap: 12px; margin-top: 24px; }
.btn { padding: 10px 20px; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover:not(:disabled) { background: #2563eb; }
.btn-secondary { background: #f1f5f9; color: #475569; }
.btn-secondary:hover { background: #e2e8f0; }
.btn:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-sm { padding: 6px 12px; font-size: 13px; }
</style>
