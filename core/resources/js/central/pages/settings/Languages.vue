<template>
    <div class="languages-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🌐 Languages</h1>
                <p class="page-subtitle">Manage system languages</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary" @click="showCreateModal = true">
                    ➕ Add New Language
                </button>
            </div>
        </div>

        <!-- Languages Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading languages...</p>
            </div>
            
            <table v-else class="languages-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Native Name</th>
                        <th>Status</th>
                        <th>Default</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="languages.length === 0">
                        <td colspan="8" class="empty-cell">
                            No languages found
                        </td>
                    </tr>
                    <tr v-else v-for="language in languages" :key="language.id">
                        <td>{{ language.id }}</td>
                        <td>
                            <div class="language-name">
                                <strong>{{ language.name }}</strong>
                            </div>
                        </td>
                        <td>
                            <span class="code-badge">{{ language.code || language.locale }}</span>
                        </td>
                        <td>{{ language.native_name || '-' }}</td>
                        <td>
                            <StatusBadge :status="language.status || 'active'" />
                        </td>
                        <td>
                            <span v-if="language.is_default" class="default-badge">✓ Default</span>
                            <button v-else class="btn-sm btn-link" @click="setDefault(language)">
                                Set Default
                            </button>
                        </td>
                        <td>{{ formatDate(language.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="editLanguage(language)"
                                    title="Edit"
                                >
                                    ✏️
                                </button>
                                <button 
                                    class="btn-icon btn-danger" 
                                    @click="deleteLanguage(language)"
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
                    <h3>{{ showEditModal ? '✏️ Edit Language' : '➕ Add New Language' }}</h3>
                    <button class="modal-close" @click="closeModal">×</button>
                </div>
                <form @submit.prevent="saveLanguage" class="modal-form">
                    <div class="form-group">
                        <label class="form-label">Language Name <span class="required">*</span></label>
                        <input
                            type="text"
                            v-model="form.name"
                            class="form-input"
                            placeholder="e.g., English"
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Language Code <span class="required">*</span></label>
                        <input
                            type="text"
                            v-model="form.code"
                            class="form-input"
                            placeholder="e.g., en"
                            maxlength="2"
                            required
                        />
                        <small class="form-help">ISO 639-1 code (2 letters)</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Native Name</label>
                        <input
                            type="text"
                            v-model="form.native_name"
                            class="form-input"
                            placeholder="e.g., English"
                        />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Locale</label>
                        <input
                            type="text"
                            v-model="form.locale"
                            class="form-input"
                            placeholder="e.g., en_US"
                        />
                        <small class="form-help">Locale format: language_COUNTRY</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Direction</label>
                        <select v-model="form.direction" class="form-select">
                            <option value="ltr">Left to Right (LTR)</option>
                            <option value="rtl">Right to Left (RTL)</option>
                        </select>
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
                    <div class="form-group">
                        <label class="form-checkbox">
                            <input
                                type="checkbox"
                                v-model="form.is_default"
                                class="checkbox-input"
                            />
                            <span>Set as Default</span>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" :disabled="saving">
                            {{ saving ? 'Saving...' : 'Save Language' }}
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

const languages = ref([])
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
    code: '',
    native_name: '',
    locale: '',
    direction: 'ltr',
    status: true,
    is_default: false
})

const loadLanguages = async () => {
    loading.value = true
    try {
        const response = await api.system.languages()
        
        if (response.data.success) {
            languages.value = response.data.data || []
        }
    } catch (error) {
        console.error('Error loading languages:', error)
        languages.value = []
        showToastMessage('error', 'Error', 'Failed to load languages')
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

const editLanguage = (language) => {
    form.value = {
        id: language.id,
        name: language.name || '',
        code: language.code || language.locale || '',
        native_name: language.native_name || '',
        locale: language.locale || '',
        direction: language.direction || 'ltr',
        status: language.status !== false,
        is_default: language.is_default || false
    }
    showEditModal.value = true
}

const setDefault = async (language) => {
    try {
        const response = await api.system.setDefaultLanguage(language.id)
        if (response.data.success) {
            showToastMessage('success', 'Success', 'Default language updated successfully')
            await loadLanguages()
        }
    } catch (error) {
        console.error('Error setting default language:', error)
        showToastMessage('error', 'Error', 'Failed to set default language')
    }
}

const deleteLanguage = async (language) => {
    if (!confirm(`Are you sure you want to delete language "${language.name}"?`)) {
        return
    }
    
    try {
        const response = await api.system.deleteLanguage(language.id)
        if (response.data.success) {
            showToastMessage('success', 'Success', 'Language deleted successfully')
            await loadLanguages()
        }
    } catch (error) {
        console.error('Error deleting language:', error)
        showToastMessage('error', 'Error', 'Failed to delete language')
    }
}

const saveLanguage = async () => {
    saving.value = true
    try {
        if (form.value.id) {
            // Update language
            const response = await api.system.updateLanguage(form.value.id, form.value)
            if (response.data.success) {
                showToastMessage('success', 'Success', 'Language updated successfully')
            }
        } else {
            // Create language
            const response = await api.system.createLanguage(form.value)
            if (response.data.success) {
                showToastMessage('success', 'Success', 'Language created successfully')
            }
        }
        closeModal()
        await loadLanguages()
    } catch (error) {
        console.error('Error saving language:', error)
        showToastMessage('error', 'Error', 'Failed to save language')
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
        code: '',
        native_name: '',
        locale: '',
        direction: 'ltr',
        status: true,
        is_default: false
    }
}

const showToastMessage = (type, title, message) => {
    toastType.value = type
    toastTitle.value = title
    toastMessage.value = message
    showToast.value = true
}

onMounted(() => {
    loadLanguages()
})
</script>

<style scoped>
.languages-page {
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

.btn-sm {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    font-weight: 500;
}

.btn-link {
    background: none;
    color: #3b82f6;
    text-decoration: underline;
}

.btn-link:hover {
    color: #2563eb;
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

.languages-table {
    width: 100%;
    border-collapse: collapse;
}

.languages-table thead {
    background: #f8fafc;
}

.languages-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.languages-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.language-name strong {
    color: #1e293b;
}

.code-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    font-family: monospace;
    text-transform: uppercase;
}

.default-badge {
    background: #dcfce7;
    color: #166534;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
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
</style>
