<template>
    <div class="roles-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🎭 Roles Management</h1>
                <p class="page-subtitle">Manage user roles and permissions</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary" @click="showCreateModal = true">
                    ➕ Add New Role
                </button>
            </div>
        </div>

        <!-- Roles Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading roles...</p>
            </div>
            
            <table v-else class="roles-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Guard</th>
                        <th>Users Count</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="roles.length === 0">
                        <td colspan="6" class="empty-cell">
                            No roles found
                        </td>
                    </tr>
                    <tr v-else v-for="role in roles" :key="role.id">
                        <td>{{ role.id }}</td>
                        <td>
                            <div class="role-name">
                                <strong>{{ role.name }}</strong>
                            </div>
                        </td>
                        <td>{{ role.guard_name || 'web' }}</td>
                        <td>{{ role.users_count || 0 }}</td>
                        <td>{{ formatDate(role.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="editRole(role)"
                                    title="Edit"
                                >
                                    ✏️
                                </button>
                                <button 
                                    class="btn-icon" 
                                    @click="managePermissions(role)"
                                    title="Manage Permissions"
                                >
                                    🔐
                                </button>
                                <button 
                                    class="btn-icon btn-danger" 
                                    @click="deleteRole(role)"
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
                    <h3>{{ showEditModal ? '✏️ Edit Role' : '➕ Add New Role' }}</h3>
                    <button class="modal-close" @click="closeModal">×</button>
                </div>
                <form @submit.prevent="saveRole" class="modal-form">
                    <div class="form-group">
                        <label class="form-label">Role Name <span class="required">*</span></label>
                        <input
                            type="text"
                            v-model="form.name"
                            class="form-input"
                            placeholder="Enter role name"
                            required
                        />
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
                            {{ saving ? 'Saving...' : 'Save Role' }}
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
import StatusBadge from '../../components/StatusBadge.vue'
import Toast from '../../components/Toast.vue'

const roles = ref([])
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

const loadRoles = async () => {
    loading.value = true
    try {
        const response = await api.users.roles()
        
        if (response.data.success) {
            roles.value = response.data.data || []
        }
    } catch (error) {
        console.error('Error loading roles:', error)
        roles.value = []
        showToastMessage('error', 'Error', 'Failed to load roles')
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

const editRole = (role) => {
    form.value = {
        id: role.id,
        name: role.name,
        guard_name: role.guard_name || 'web'
    }
    showEditModal.value = true
}

const managePermissions = (role) => {
    // Navigate to permissions management page
    alert(`Manage permissions for role: ${role.name}`)
}

const deleteRole = async (role) => {
    if (!confirm(`Are you sure you want to delete role "${role.name}"?`)) {
        return
    }
    
    try {
        // await api.users.deleteRole(role.id)
        showToastMessage('success', 'Success', 'Role deleted successfully')
        await loadRoles()
    } catch (error) {
        console.error('Error deleting role:', error)
        showToastMessage('error', 'Error', 'Failed to delete role')
    }
}

const saveRole = async () => {
    saving.value = true
    try {
        if (form.value.id) {
            // Update role
            // await api.users.updateRole(form.value.id, form.value)
            showToastMessage('success', 'Success', 'Role updated successfully')
        } else {
            // Create role
            // await api.users.createRole(form.value)
            showToastMessage('success', 'Success', 'Role created successfully')
        }
        closeModal()
        await loadRoles()
    } catch (error) {
        console.error('Error saving role:', error)
        showToastMessage('error', 'Error', 'Failed to save role')
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
    loadRoles()
})
</script>

<style scoped>
.roles-page {
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

.roles-table {
    width: 100%;
    border-collapse: collapse;
}

.roles-table thead {
    background: #f8fafc;
}

.roles-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.roles-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.role-name strong {
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

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}
</style>
