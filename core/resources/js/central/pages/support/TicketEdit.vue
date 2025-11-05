<template>
    <div class="ticket-edit-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">✏️ Edit Support Ticket</h1>
                <p class="page-subtitle">Edit support ticket</p>
            </div>
            <router-link to="/support" class="btn btn-secondary">← Back to Tickets</router-link>
        </div>

        <div class="form-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading ticket...</p>
            </div>

            <form v-else @submit.prevent="handleSubmit">
                <div class="form-grid">
                    <div class="form-main">
                        <div class="form-group">
                            <label for="title" class="form-label">Title <span class="required">*</span></label>
                            <input type="text" id="title" v-model="form.title" class="form-input" :class="{ 'error': errors.title }" required />
                            <span v-if="errors.title" class="error-message">{{ errors.title }}</span>
                        </div>

                        <div class="form-group">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" id="subject" v-model="form.subject" class="form-input" />
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Description <span class="required">*</span></label>
                            <textarea id="description" v-model="form.description" class="form-textarea" :class="{ 'error': errors.description }" rows="10" required></textarea>
                            <span v-if="errors.description" class="error-message">{{ errors.description }}</span>
                        </div>
                    </div>

                    <div class="form-sidebar">
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" v-model="form.status" class="form-select">
                                <option value="open">Open</option>
                                <option value="pending">Pending</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="priority" class="form-label">Priority</label>
                            <select id="priority" v-model="form.priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="saving">
                        <span v-if="saving">⏳ Updating...</span>
                        <span v-else>💾 Update Ticket</span>
                    </button>
                    <router-link to="/support" class="btn btn-secondary">Cancel</router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const route = useRoute()

const loading = ref(true)
const saving = ref(false)
const form = ref({
    title: '',
    subject: '',
    description: '',
    status: 'open',
    priority: 'medium'
})

const errors = ref({})

const loadTicket = async () => {
    loading.value = true
    try {
        const response = await axios.get(`/support/tickets/${route.params.id}`)
        if (response.data.success) {
            const ticket = response.data.data
            form.value = {
                title: ticket.title || '',
                subject: ticket.subject || ticket.title || '',
                description: ticket.description || '',
                status: ticket.status || 'open',
                priority: ticket.priority || 'medium'
            }
        }
    } catch (error) {
        console.error('Error loading ticket:', error)
        alert('Failed to load ticket')
        router.push('/support')
    } finally {
        loading.value = false
    }
}

const handleSubmit = async () => {
    errors.value = {}
    saving.value = true

    try {
        const response = await axios.put(`/support/tickets/${route.params.id}`, form.value)

        if (response.data.success) {
            router.push('/support')
        } else {
            if (response.data.errors) {
                errors.value = response.data.errors
            } else {
                alert('Failed to update ticket: ' + (response.data.message || 'Unknown error'))
            }
        }
    } catch (error) {
        console.error('Error updating ticket:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            alert('Failed to update ticket: ' + (error.response?.data?.message || 'Unknown error'))
        }
    } finally {
        saving.value = false
    }
}

onMounted(() => loadTicket())
</script>

<style scoped>
.ticket-edit-page { padding: 24px; max-width: 1400px; }
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
.page-title { font-size: 28px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0; }
.page-subtitle { color: #64748b; font-size: 15px; margin: 0; }
.form-container { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); position: relative; min-height: 200px; }
.loading-overlay { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px; color: #64748b; }
.spinner { width: 40px; height: 40px; border: 4px solid #f1f5f9; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 16px; }
@keyframes spin { to { transform: rotate(360deg); } }
.form-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
.form-main, .form-sidebar { display: flex; flex-direction: column; gap: 20px; }
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-label { font-weight: 600; color: #374151; font-size: 14px; }
.required { color: #ef4444; }
.form-input, .form-textarea, .form-select { width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 15px; transition: border-color 0.2s; font-family: inherit; }
.form-input:focus, .form-textarea:focus, .form-select:focus { outline: none; border-color: #3b82f6; }
.form-input.error, .form-textarea.error, .form-select.error { border-color: #ef4444; }
.form-textarea { resize: vertical; min-height: 200px; }
.error-message { color: #ef4444; font-size: 13px; }
.form-actions { display: flex; gap: 12px; margin-top: 30px; padding-top: 30px; border-top: 1px solid #e5e7eb; }
.btn { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.2s; font-size: 15px; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover:not(:disabled) { background: #2563eb; }
.btn-primary:disabled { background: #9ca3af; cursor: not-allowed; }
.btn-secondary { background: #e5e7eb; color: #374151; }
.btn-secondary:hover { background: #d1d5db; }
@media (max-width: 1024px) { .form-grid { grid-template-columns: 1fr; } }
</style>

