<template>
    <div class="payment-notifications-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">📧 Payment Notification Settings</h1>
                <p class="page-subtitle">Configure payment notifications</p>
            </div>
        </div>

        <div class="settings-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading settings...</p>
            </div>

            <form v-else @submit.prevent="saveSettings" class="settings-form">
                <div class="form-section">
                    <h3>Notification Preferences</h3>
                    <div class="form-group">
                        <label class="form-label">Email Notifications</label>
                        <select v-model="settings.payment_email_notification" class="form-select">
                            <option value="on">Enabled</option>
                            <option value="off">Disabled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">SMS Notifications</label>
                        <select v-model="settings.payment_sms_notification" class="form-select">
                            <option value="on">Enabled</option>
                            <option value="off">Disabled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Admin Email</label>
                        <input v-model="settings.payment_admin_email" type="email" class="form-input" placeholder="admin@example.com" />
                    </div>
                </div>

                <div class="form-section">
                    <h3>Email Templates</h3>
                    <div class="form-group">
                        <label class="form-label">Payment Success Template</label>
                        <textarea v-model="settings.payment_success_template" class="form-textarea" rows="4" placeholder="Email template for successful payments"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Failed Template</label>
                        <textarea v-model="settings.payment_failed_template" class="form-textarea" rows="4" placeholder="Email template for failed payments"></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="saving">
                        <span v-if="saving">⏳ Saving...</span>
                        <span v-else>💾 Save Settings</span>
                    </button>
                </div>
            </form>
        </div>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Toast from '../../components/Toast.vue'

const settings = ref({
    payment_email_notification: 'on',
    payment_sms_notification: 'off',
    payment_admin_email: '',
    payment_success_template: '',
    payment_failed_template: ''
})

const loading = ref(false)
const saving = ref(false)
const showToast = ref(false)
const toastType = ref('success')
const toastTitle = ref('')
const toastMessage = ref('')

const loadSettings = async () => {
    loading.value = true
    try {
        const response = await axios.get('/payment-notifications')
        if (response.data.success) {
            settings.value = { ...settings.value, ...response.data.data }
        }
    } catch (error) {
        console.error('Error loading settings:', error)
        showToastMessage('error', 'Error', 'Failed to load settings')
    } finally {
        loading.value = false
    }
}

const saveSettings = async () => {
    saving.value = true
    try {
        const response = await axios.put('/payment-notifications', settings.value)
        if (response.data.success) {
            showToastMessage('success', 'Success', response.data.message)
        }
    } catch (error) {
        console.error('Error saving settings:', error)
        showToastMessage('error', 'Error', error.response?.data?.message || 'Failed to save settings')
    } finally {
        saving.value = false
    }
}

const showToastMessage = (type, title, message) => {
    toastType.value = type
    toastTitle.value = title
    toastMessage.value = message
    showToast.value = true
}

onMounted(() => loadSettings())
</script>

<style scoped>
.payment-notifications-page { padding: 24px; }
.page-header { margin-bottom: 24px; }
.page-title { font-size: 28px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0; }
.page-subtitle { color: #64748b; font-size: 15px; margin: 0; }
.settings-container { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); position: relative; min-height: 200px; }
.loading-overlay { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px; color: #64748b; }
.spinner { width: 40px; height: 40px; border: 4px solid #f1f5f9; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 16px; }
@keyframes spin { to { transform: rotate(360deg); } }
.settings-form { max-width: 800px; }
.form-section { margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid #f1f5f9; }
.form-section:last-child { border-bottom: none; }
.form-section h3 { margin: 0 0 20px 0; color: #1e293b; font-size: 18px; }
.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 500; color: #334155; font-size: 14px; }
.form-input, .form-select, .form-textarea { width: 100%; padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: border-color 0.2s; }
.form-textarea { resize: vertical; min-height: 100px; }
.form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: #3b82f6; }
.form-actions { margin-top: 32px; padding-top: 24px; border-top: 1px solid #f1f5f9; }
.btn { padding: 12px 24px; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover:not(:disabled) { background: #2563eb; }
.btn:disabled { opacity: 0.6; cursor: not-allowed; }
</style>
