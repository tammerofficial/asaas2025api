<template>
    <div class="saas-settings-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">🚀 SAAS Settings</h1>
                <p class="page-subtitle">Configure SAAS platform settings</p>
            </div>
        </div>

        <div class="settings-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading settings...</p>
            </div>

            <form v-else @submit.prevent="saveSettings" class="settings-form">
                <div class="form-section">
                    <h3>SAAS Configuration</h3>
                    <div class="form-group">
                        <label class="form-label">SAAS Enabled</label>
                        <select v-model="settings.saas_enabled" class="form-select">
                            <option value="on">Enabled</option>
                            <option value="off">Disabled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Commission Type</label>
                        <select v-model="settings.saas_commission_type" class="form-select">
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Commission Amount</label>
                        <input v-model.number="settings.saas_commission_amount" type="number" step="0.01" min="0" class="form-input" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Trial Period (days)</label>
                        <input v-model.number="settings.saas_trial_period" type="number" min="0" class="form-input" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Auto Renewal</label>
                        <select v-model="settings.saas_auto_renewal" class="form-select">
                            <option value="on">Enabled</option>
                            <option value="off">Disabled</option>
                        </select>
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
    saas_enabled: 'on',
    saas_commission_type: 'percentage',
    saas_commission_amount: '0',
    saas_trial_period: '0',
    saas_auto_renewal: 'on'
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
        const response = await axios.get('/saas-settings')
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
        const response = await axios.put('/saas-settings', settings.value)
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
.saas-settings-page { padding: 24px; }
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
.form-input, .form-select { width: 100%; padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: border-color 0.2s; }
.form-input:focus, .form-select:focus { outline: none; border-color: #3b82f6; }
.form-actions { margin-top: 32px; padding-top: 24px; border-top: 1px solid #f1f5f9; }
.btn { padding: 12px 24px; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover:not(:disabled) { background: #2563eb; }
.btn:disabled { opacity: 0.6; cursor: not-allowed; }
</style>
