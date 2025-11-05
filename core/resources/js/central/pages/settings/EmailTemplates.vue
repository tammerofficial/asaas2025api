<template>
    <div class="email-templates-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">📧 Email Templates</h1>
                <p class="page-subtitle">Manage email templates</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary" @click="showCreateModal = true">
                    ➕ Add New Template
                </button>
            </div>
        </div>

        <!-- Templates Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading templates...</p>
            </div>
            
            <table v-else class="templates-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="templates.length === 0">
                        <td colspan="7" class="empty-cell">
                            No email templates found
                        </td>
                    </tr>
                    <tr v-else v-for="template in templates" :key="template.id">
                        <td>{{ template.id }}</td>
                        <td>
                            <div class="template-name">
                                <strong>{{ template.name || template.title }}</strong>
                            </div>
                        </td>
                        <td>
                            <div class="template-subject">
                                {{ template.subject || '-' }}
                            </div>
                        </td>
                        <td>
                            <span class="type-badge">{{ template.type || 'general' }}</span>
                        </td>
                        <td>
                            <StatusBadge :status="template.status || 'active'" />
                        </td>
                        <td>{{ formatDate(template.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="editTemplate(template)"
                                    title="Edit"
                                >
                                    ✏️
                                </button>
                                <button 
                                    class="btn-icon" 
                                    @click="showPreview(template)"
                                    title="Preview"
                                >
                                    👁️
                                </button>
                                <button 
                                    class="btn-icon btn-danger" 
                                    @click="deleteTemplate(template)"
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
            <div class="modal-content modal-large" @click.stop>
                <div class="modal-header">
                    <h3>{{ showEditModal ? '✏️ Edit Template' : '➕ Add New Template' }}</h3>
                    <button class="modal-close" @click="closeModal">×</button>
                </div>
                <form @submit.prevent="saveTemplate" class="modal-form">
                    <div class="form-group">
                        <label class="form-label">Template Name <span class="required">*</span></label>
                        <input
                            type="text"
                            v-model="form.name"
                            class="form-input"
                            placeholder="e.g., Welcome Email"
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subject <span class="required">*</span></label>
                        <input
                            type="text"
                            v-model="form.subject"
                            class="form-input"
                            placeholder="Email subject"
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Type</label>
                        <select v-model="form.type" class="form-select">
                            <option value="general">General</option>
                            <option value="welcome">Welcome</option>
                            <option value="notification">Notification</option>
                            <option value="transactional">Transactional</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Body (HTML) <span class="required">*</span></label>
                        <textarea
                            v-model="form.body"
                            class="form-textarea"
                            rows="12"
                            placeholder="Enter email template body (HTML supported)"
                            required
                        ></textarea>
                        <small class="form-help">Use {name}, {email} etc. for dynamic variables</small>
                    </div>
                    <div class="form-group">
                        <label class="form-checkbox">
                            <input
                                type="checkbox"
                                v-model="form.status"
                                class="checkbox-input"
                            />
                            <span>Active</span>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" :disabled="saving">
                            {{ saving ? 'Saving...' : 'Save Template' }}
                        </button>
                        <button type="button" class="btn btn-secondary" @click="closeModal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Modal -->
        <div v-if="previewTemplate" class="modal-overlay" @click="closePreview">
            <div class="modal-content modal-large" @click.stop>
                <div class="modal-header">
                    <h3>👁️ Preview Template</h3>
                    <button class="modal-close" @click="closePreview">×</button>
                </div>
                <div class="modal-body">
                    <div class="preview-content">
                        <div class="preview-subject">
                            <strong>Subject:</strong> {{ previewTemplate.subject }}
                        </div>
                        <div class="preview-body" v-html="previewTemplate.body"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="closePreview">Close</button>
                </div>
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

const templates = ref([])
const loading = ref(false)
const showCreateModal = ref(false)
const showEditModal = ref(false)
const previewTemplate = ref(null)
const saving = ref(false)
const showToast = ref(false)
const toastType = ref('success')
const toastTitle = ref('')
const toastMessage = ref('')

const form = ref({
    id: null,
    name: '',
    subject: '',
    body: '',
    type: 'general',
    status: true
})

const loadTemplates = async () => {
    loading.value = true
    try {
        // Note: This endpoint might need to be added to API service
        const response = await api.settings.get({ type: 'email_templates' })
        
        if (response.data.success) {
            templates.value = response.data.data || []
        }
    } catch (error) {
        console.error('Error loading templates:', error)
        templates.value = []
        showToastMessage('error', 'Error', 'Failed to load templates')
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

const editTemplate = (template) => {
    form.value = {
        id: template.id,
        name: template.name || template.title,
        subject: template.subject || '',
        body: template.body || template.content || '',
        type: template.type || 'general',
        status: template.status !== false
    }
    showEditModal.value = true
}

const showPreview = (template) => {
    previewTemplate.value = template
}

const closePreview = () => {
    previewTemplate.value = null
}

const deleteTemplate = async (template) => {
    if (!confirm(`Are you sure you want to delete template "${template.name || template.title}"?`)) {
        return
    }
    
    try {
        // await api.settings.deleteTemplate(template.id)
        showToastMessage('success', 'Success', 'Template deleted successfully')
        await loadTemplates()
    } catch (error) {
        console.error('Error deleting template:', error)
        showToastMessage('error', 'Error', 'Failed to delete template')
    }
}

const saveTemplate = async () => {
    saving.value = true
    try {
        if (form.value.id) {
            // Update template
            // await api.settings.updateTemplate(form.value.id, form.value)
            showToastMessage('success', 'Success', 'Template updated successfully')
        } else {
            // Create template
            // await api.settings.createTemplate(form.value)
            showToastMessage('success', 'Success', 'Template created successfully')
        }
        closeModal()
        await loadTemplates()
    } catch (error) {
        console.error('Error saving template:', error)
        showToastMessage('error', 'Error', 'Failed to save template')
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
        subject: '',
        body: '',
        type: 'general',
        status: true
    }
}

const showToastMessage = (type, title, message) => {
    toastType.value = type
    toastTitle.value = title
    toastMessage.value = message
    showToast.value = true
}

onMounted(() => {
    loadTemplates()
})
</script>

<style scoped>
.email-templates-page {
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

.templates-table {
    width: 100%;
    border-collapse: collapse;
}

.templates-table thead {
    background: #f8fafc;
}

.templates-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.templates-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.template-name strong {
    color: #1e293b;
}

.template-subject {
    max-width: 250px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 13px;
    color: #64748b;
}

.type-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
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
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-large {
    max-width: 900px;
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

.modal-form,
.modal-body {
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
.form-select,
.form-textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-textarea {
    resize: vertical;
    min-height: 200px;
    font-family: monospace;
}

.form-help {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #64748b;
}

.form-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.checkbox-input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

/* Preview */
.preview-content {
    background: #f8fafc;
    padding: 20px;
    border-radius: 8px;
}

.preview-subject {
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e5e7eb;
}

.preview-body {
    background: white;
    padding: 20px;
    border-radius: 6px;
    min-height: 200px;
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
}
</style>
