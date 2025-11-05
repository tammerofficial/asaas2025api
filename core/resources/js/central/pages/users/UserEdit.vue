<template>
    <div class="user-edit-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">✏️ Edit User</h1>
                <p class="page-subtitle">Edit user account</p>
            </div>
            <router-link to="/users" class="btn btn-secondary">← Back to Users</router-link>
        </div>

        <div class="form-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading user...</p>
            </div>

            <form v-else @submit.prevent="handleSubmit">
                <div class="form-grid">
                    <div class="form-main">
                        <div class="form-group">
                            <label for="name" class="form-label">Name <span class="required">*</span></label>
                            <input type="text" id="name" v-model="form.name" class="form-input" :class="{ 'error': errors.name }" required />
                            <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email <span class="required">*</span></label>
                            <input type="email" id="email" v-model="form.email" class="form-input" :class="{ 'error': errors.email }" required />
                            <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
                        </div>

                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" v-model="form.username" class="form-input" :class="{ 'error': errors.username }" />
                            <span v-if="errors.username" class="error-message">{{ errors.username }}</span>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" v-model="form.password" class="form-input" :class="{ 'error': errors.password }" />
                            <span v-if="errors.password" class="error-message">{{ errors.password }}</span>
                            <small class="form-help">Leave empty to keep current password</small>
                        </div>

                        <div class="form-group" v-if="form.password">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" id="password_confirmation" v-model="form.password_confirmation" class="form-input" :class="{ 'error': errors.password_confirmation }" />
                            <span v-if="errors.password_confirmation" class="error-message">{{ errors.password_confirmation }}</span>
                        </div>
                    </div>

                    <div class="form-sidebar">
                        <div class="form-group">
                            <label for="mobile" class="form-label">Mobile</label>
                            <input type="text" id="mobile" v-model="form.mobile" class="form-input" />
                        </div>

                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" v-model="form.status" class="form-select">
                                <option :value="true">Active</option>
                                <option :value="false">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="saving">
                        <span v-if="saving">⏳ Updating...</span>
                        <span v-else>💾 Update User</span>
                    </button>
                    <router-link to="/users" class="btn btn-secondary">Cancel</router-link>
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
    name: '',
    email: '',
    username: '',
    password: '',
    password_confirmation: '',
    mobile: '',
    status: true
})

const errors = ref({})

const loadUser = async () => {
    loading.value = true
    try {
        const response = await axios.get(`/users/${route.params.id}`)
        if (response.data.success) {
            const user = response.data.data
            form.value = {
                name: user.name || '',
                email: user.email || '',
                username: user.username || '',
                password: '',
                password_confirmation: '',
                mobile: user.mobile || '',
                status: user.status ?? true
            }
        }
    } catch (error) {
        console.error('Error loading user:', error)
        alert('Failed to load user')
        router.push('/users')
    } finally {
        loading.value = false
    }
}

const handleSubmit = async () => {
    errors.value = {}
    saving.value = true

    try {
        const data = { ...form.value }
        if (!data.password) {
            delete data.password
            delete data.password_confirmation
        }

        const response = await axios.put(`/users/${route.params.id}`, data)

        if (response.data.success) {
            router.push('/users')
        } else {
            if (response.data.errors) {
                errors.value = response.data.errors
            } else {
                alert('Failed to update user: ' + (response.data.message || 'Unknown error'))
            }
        }
    } catch (error) {
        console.error('Error updating user:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            alert('Failed to update user: ' + (error.response?.data?.message || 'Unknown error'))
        }
    } finally {
        saving.value = false
    }
}

onMounted(() => loadUser())
</script>

<style scoped>
.user-edit-page { padding: 24px; max-width: 1400px; }
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
.form-input, .form-select { width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 15px; transition: border-color 0.2s; font-family: inherit; }
.form-input:focus, .form-select:focus { outline: none; border-color: #3b82f6; }
.form-input.error, .form-select.error { border-color: #ef4444; }
.form-help { color: #64748b; font-size: 13px; }
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

