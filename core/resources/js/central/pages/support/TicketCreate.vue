<template>
    <div class="ticket-create-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">➕ Create New Ticket</h1>
                <p class="page-subtitle">Create a new support ticket</p>
            </div>
            <router-link to="/support" class="btn btn-secondary">
                ← Back to Tickets
            </router-link>
        </div>

        <!-- Form -->
        <div class="form-container">
            <form @submit.prevent="handleSubmit">
                <div class="form-grid">
                    <div class="form-main">
                        <!-- Title -->
                        <div class="form-group">
                            <label for="title" class="form-label">
                                Title <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="title"
                                v-model="form.title"
                                class="form-input"
                                :class="{ 'error': errors.title }"
                                placeholder="Enter ticket title"
                                required
                            />
                            <span v-if="errors.title" class="error-message">{{ errors.title }}</span>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="form-label">
                                Description <span class="required">*</span>
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                class="form-textarea"
                                :class="{ 'error': errors.description }"
                                rows="8"
                                placeholder="Describe your issue or request..."
                                required
                            ></textarea>
                            <span v-if="errors.description" class="error-message">{{ errors.description }}</span>
                        </div>
                    </div>

                    <div class="form-sidebar">
                        <!-- Priority -->
                        <div class="form-group">
                            <label for="priority" class="form-label">Priority</label>
                            <select
                                id="priority"
                                v-model="form.priority"
                                class="form-select"
                                :class="{ 'error': errors.priority }"
                            >
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                            <span v-if="errors.priority" class="error-message">{{ errors.priority }}</span>
                        </div>

                        <!-- Department -->
                        <div class="form-group">
                            <label for="department_id" class="form-label">Department</label>
                            <select
                                id="department_id"
                                v-model="form.department_id"
                                class="form-select"
                                :class="{ 'error': errors.department_id }"
                            >
                                <option value="">Select Department</option>
                                <option
                                    v-for="dept in departments"
                                    :key="dept.id"
                                    :value="dept.id"
                                >
                                    {{ dept.name }}
                                </option>
                            </select>
                            <span v-if="errors.department_id" class="error-message">{{ errors.department_id }}</span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span v-if="loading">⏳ Creating...</span>
                        <span v-else>📤 Create Ticket</span>
                    </button>
                    <router-link to="/support" class="btn btn-secondary">
                        Cancel
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'

const router = useRouter()

const loading = ref(false)
const form = ref({
    title: '',
    description: '',
    priority: 'medium',
    department_id: ''
})

const errors = ref({})
const departments = ref([])

const loadDepartments = async () => {
    try {
        const response = await api.support.departments()
        if (response.data.success) {
            departments.value = response.data.data || []
        }
    } catch (error) {
        console.error('Error loading departments:', error)
    }
}

const handleSubmit = async () => {
    errors.value = {}
    loading.value = true

    try {
        const response = await api.support.create(form.value)

        if (response.data.success) {
            router.push('/support')
        } else {
            if (response.data.errors) {
                errors.value = response.data.errors
            } else {
                alert('Failed to create ticket: ' + (response.data.message || 'Unknown error'))
            }
        }
    } catch (error) {
        console.error('Error creating ticket:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            alert('Failed to create ticket: ' + (error.response?.data?.message || 'Unknown error'))
        }
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    loadDepartments()
})
</script>

<style scoped>
.ticket-create-page {
    padding: 24px;
    max-width: 1400px;
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

/* Form Container */
.form-container {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.form-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

.form-main {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Form Groups */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-label {
    font-weight: 600;
    color: #374151;
    font-size: 14px;
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
    min-height: 200px;
}

.error-message {
    color: #ef4444;
    font-size: 13px;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid #e5e7eb;
}

/* Buttons */
.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s;
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

@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
