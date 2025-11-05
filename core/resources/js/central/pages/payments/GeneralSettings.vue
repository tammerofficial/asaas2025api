<template>
    <div class="payment-settings-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">⚙️ Payment General Settings</h1>
                <p class="page-subtitle">Configure payment settings</p>
            </div>
        </div>

        <div class="settings-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading settings...</p>
            </div>

            <form v-else @submit.prevent="saveSettings" class="settings-form">
                <div class="form-section">
                    <h3>Currency Settings</h3>
                    <div class="form-group">
                        <label class="form-label">Global Currency</label>
                        <input v-model="settings.site_global_currency" type="text" class="form-input" placeholder="e.g., KWD, USD" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Currency Symbol Position</label>
                        <select v-model="settings.site_currency_symbol_position" class="form-select">
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Custom Currency Symbol</label>
                        <input v-model="settings.site_custom_currency_symbol" type="text" class="form-input" placeholder="e.g., د.ك" />
                    </div>
                </div>

                <div class="form-section">
                    <h3>Payment Gateway Settings</h3>
                    <div class="form-group">
                        <label class="form-label">Default Payment Gateway</label>
                        <input v-model="settings.site_default_payment_gateway" type="text" class="form-input" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Global Payment Gateway</label>
                        <input v-model="settings.site_global_payment_gateway" type="text" class="form-input" />
                    </div>
                </div>

                <div class="form-section">
                    <h3>Format Settings</h3>
                    <div class="form-group">
                        <label class="form-label">Thousand Separator</label>
                        <input v-model="settings.site_custom_currency_thousand_separator" type="text" class="form-input" maxlength="1" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Decimal Separator</label>
                        <input v-model="settings.site_custom_currency_decimal_separator" type="text" class="form-input" maxlength="1" />
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
    site_global_currency: '',
    site_currency_symbol_position: 'left',
    site_default_payment_gateway: '',
    site_global_payment_gateway: '',
    currency_amount_type_status: '',
    site_custom_currency_symbol: '',
    site_custom_currency_thousand_separator: ',',
    site_custom_currency_decimal_separator: '.',
    cash_on_delivery: ''
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
        const response = await axios.get('/payment-settings')
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
        const response = await axios.put('/payment-settings', settings.value)
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
.payment-settings-page { padding: 24px; }
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
