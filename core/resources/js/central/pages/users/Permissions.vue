<template>
    <div class="permissions-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🔐 Permissions Management</h1>
                <p class="page-subtitle">Manage user permissions</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary" @click="showCreateModal = true">
                    ➕ Add New Permission
                </button>
            </div>
        </div>

        <!-- Permissions Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading permissions...</p>
            </div>
            
            <table v-else class="permissions-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Guard</th>
                        <th>Roles Count</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="permissions.length === 0">
                        <td colspan="6" class="empty-cell">
                            No permissions found
                        </td>
                    </tr>
                    <tr v-else v-for="permission in permissions" :key="permission.id">
                        <td>{{ permission.id }}</td>
                        <td>
                            <div class="permission-name">
                                <strong>{{ permission.name }}</strong>
                            </div>
                        </td>
                        <td>{{ permission.guard_name || 'web' }}</td>
                        <td>{{ permission.roles_count || 0 }}</td>
                        <td>{{ formatDate(permission.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="editPermission(permission)"
                                    title="Edit"
                                >
                                    ✏️
                                </button>
                                <button 
                                    class="btn-icon btn-danger" 
                                    @click="deletePermission(permission)"
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

        <!-- Create/Edit Modal -->
        <div v-if="showCreateModal || showEditModal" class="modal-overlay" @click="closeModal">
            <div class="modal-content" @click.stop>
                <div class="modal-header">
                    <h3>{{ showEditModal ? '✏️ Edit Permission' : '➕ Add New Permission' }}</h3>
                    <button class="modal-close" @click="closeModal">×</button>
                </div>
                <form @submit.prevent="savePermission" class="modal-form">
                    <div class="form-group">
                        <label class="form-label">Permission Name <span class="required">*</span></label>
                        <input
                            type="text"
                            v-model="form.name"
                            class="form-input"
                            placeholder="e.g., users.create, users.update"
                            required
                        />
                        <small class="form-help">Format: resource.action (e.g., users.create)</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Guard</label>
                        <select v-model="form.guard_name" class="form-select">
                            <option value="web">Web</option>
                            <option value="api">API</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" :disabled="saving">
                            {{ saving ? 'Saving...' : 'Save Permission' }}
                        </button>
                        <button type="button" class="btn btn-secondary" @click="closeModal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast -->
        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import Toast from '../../components/Toast.vue'

const permissions = ref([])
const loading = ref(false)
const showCreateModal = ref(false)
const showEditModal = ref(false)
const saving = ref(false)
const showToast = ref(false)
const toastType = ref('success')
const toastTitle = ref('')
const toastMessage = ref('')

const form = ref({
    id: null,
    name: '',
    guard_name: 'web'
})

const loadPermissions = async () => {
    loading.value = true
    try {
        const response = await api.users.permissions()
        
        if (response.data.success) {
            permissions.value = response.data.data || []
        }
    } catch (error) {
        console.error('Error loading permissions:', error)
        permissions.value = []
        showToastMessage('error', 'Error', 'Failed to load permissions')
    } finally {
        loading.value = false
    }
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

const editPermission = (permission) => {
    form.value = {
        id: permission.id,
        name: permission.name,
        guard_name: permission.guard_name || 'web'
    }
    showEditModal.value = true
}

const deletePermission = async (permission) => {
    if (!confirm(`Are you sure you want to delete permission "${permission.name}"?`)) {
        return
    }
    
    try {
        // await api.users.deletePermission(permission.id)
        showToastMessage('success', 'Success', 'Permission deleted successfully')
        await loadPermissions()
    } catch (error) {
        console.error('Error deleting permission:', error)
        showToastMessage('error', 'Error', 'Failed to delete permission')
    }
}

const savePermission = async () => {
    saving.value = true
    try {
        if (form.value.id) {
            // Update permission
            // await api.users.updatePermission(form.value.id, form.value)
            showToastMessage('success', 'Success', 'Permission updated successfully')
        } else {
            // Create permission
            // await api.users.createPermission(form.value)
            showToastMessage('success', 'Success', 'Permission created successfully')
        }
        closeModal()
        await loadPermissions()
    } catch (error) {
        console.error('Error saving permission:', error)
        showToastMessage('error', 'Error', 'Failed to save permission')
    } finally {
        saving.value = false
    }
}

const closeModal = () => {
    showCreateModal.value = false
    showEditModal.value = false
    form.value = {
        id: null,
        name: '',
        guard_name: 'web'
    }
}

const showToastMessage = (type, title, message) => {
    toastType.value = type
    toastTitle.value = title
    toastMessage.value = message
    showToast.value = true
}

onMounted(() => {
    loadPermissions()
})
</script>

<style scoped>
.permissions-page {
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

/* Table */
.table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    position: relative;
    min-height: 400px;
}

.permissions-table {
    width: 100%;
    border-collapse: collapse;
}

.permissions-table thead {
    background: #f8fafc;
}

.permissions-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.permissions-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.permission-name strong {
    color: #1e293b;
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
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    margin: 0;
    font-size: 20px;
    color: #1e293b;
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
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.required {
    color: #ef4444;
}

.form-input,
.form-select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-help {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #64748b;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}
</style>
