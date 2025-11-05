<template>
    <div class="user-create-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">➕ Create New User</h1>
                <p class="page-subtitle">Create a new user account</p>
            </div>
            <router-link to="/users" class="btn btn-secondary">← Back to Users</router-link>
        </div>

        <div class="form-container">
            <form @submit.prevent="handleSubmit">
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
                            <label for="password" class="form-label">Password <span class="required">*</span></label>
                            <input type="password" id="password" v-model="form.password" class="form-input" :class="{ 'error': errors.password }" required />
                            <span v-if="errors.password" class="error-message">{{ errors.password }}</span>
                            <small class="form-help">Minimum 8 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="required">*</span></label>
                            <input type="password" id="password_confirmation" v-model="form.password_confirmation" class="form-input" :class="{ 'error': errors.password_confirmation }" required />
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
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span v-if="loading">⏳ Creating...</span>
                        <span v-else>💾 Create User</span>
                    </button>
                    <router-link to="/users" class="btn btn-secondary">Cancel</router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const loading = ref(false)
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

const handleSubmit = async () => {
    errors.value = {}
    loading.value = true

    try {
        const response = await axios.post('/users', form.value)

        if (response.data.success) {
            router.push('/users')
        } else {
            if (response.data.errors) {
                errors.value = response.data.errors
            } else {
                alert('Failed to create user: ' + (response.data.message || 'Unknown error'))
            }
        }
    } catch (error) {
        console.error('Error creating user:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            alert('Failed to create user: ' + (error.response?.data?.message || 'Unknown error'))
        }
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
.user-create-page { padding: 24px; max-width: 1400px; }
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
.page-title { font-size: 28px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0; }
.page-subtitle { color: #64748b; font-size: 15px; margin: 0; }
.form-container { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); }
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

