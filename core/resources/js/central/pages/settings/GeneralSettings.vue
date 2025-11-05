<template>
    <div class="settings-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">⚙️ General Settings</h1>
                <p class="page-subtitle">Manage general application settings</p>
            </div>
        </div>

        <form @submit.prevent="saveSettings" class="settings-form">
            <div class="settings-section">
                <h3>Site Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Site Title <span class="required">*</span></label>
                        <input
                            type="text"
                            v-model="settings.site_title"
                            class="form-input"
                            placeholder="Enter site title"
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Site Tagline</label>
                        <input
                            type="text"
                            v-model="settings.site_tagline"
                            class="form-input"
                            placeholder="Enter site tagline"
                        />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Site Email <span class="required">*</span></label>
                        <input
                            type="email"
                            v-model="settings.site_email"
                            class="form-input"
                            placeholder="admin@example.com"
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Site Phone</label>
                        <input
                            type="tel"
                            v-model="settings.site_phone"
                            class="form-input"
                            placeholder="+965 12345678"
                        />
                    </div>
                </div>
            </div>

            <div class="settings-section">
                <h3>Time Zone & Locale</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Timezone</label>
                        <select v-model="settings.timezone" class="form-select">
                            <option value="UTC">UTC</option>
                            <option value="Asia/Kuwait">Asia/Kuwait (GMT+3)</option>
                            <option value="Asia/Dubai">Asia/Dubai (GMT+4)</option>
                            <option value="Asia/Riyadh">Asia/Riyadh (GMT+3)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date Format</label>
                        <select v-model="settings.date_format" class="form-select">
                            <option value="Y-m-d">YYYY-MM-DD</option>
                            <option value="d/m/Y">DD/MM/YYYY</option>
                            <option value="m/d/Y">MM/DD/YYYY</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Format</label>
                        <select v-model="settings.time_format" class="form-select">
                            <option value="H:i">24 Hour (HH:MM)</option>
                            <option value="h:i A">12 Hour (HH:MM AM/PM)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Currency</label>
                        <select v-model="settings.currency" class="form-select">
                            <option value="KWD">KWD - Kuwaiti Dinar</option>
                            <option value="USD">USD - US Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="SAR">SAR - Saudi Riyal</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="settings-section">
                <h3>Maintenance Mode</h3>
                <div class="form-group">
                    <label class="form-checkbox">
                        <input
                            type="checkbox"
                            v-model="settings.maintenance_mode"
                            class="checkbox-input"
                        />
                        <span>Enable Maintenance Mode</span>
                    </label>
                    <small class="form-help">When enabled, only administrators can access the site</small>
                </div>
                <div v-if="settings.maintenance_mode" class="form-group">
                    <label class="form-label">Maintenance Message</label>
                    <textarea
                        v-model="settings.maintenance_message"
                        class="form-textarea"
                        rows="3"
                        placeholder="Site is under maintenance. Please check back later."
                    ></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" :disabled="loading">
                    <span v-if="loading">⏳ Saving...</span>
                    <span v-else>💾 Save Settings</span>
                </button>
                <button type="button" class="btn btn-secondary" @click="resetSettings">
                    🔄 Reset
                </button>
            </div>
        </form>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import Toast from '../../components/Toast.vue'

const loading = ref(false)
const saving = ref(false)
const showToast = ref(false)
const toastType = ref('success')
const toastTitle = ref('')
const toastMessage = ref('')

const settings = ref({
    site_title: '',
    site_tagline: '',
    site_email: '',
    site_phone: '',
    timezone: 'Asia/Kuwait',
    date_format: 'Y-m-d',
    time_format: 'H:i',
    currency: 'KWD',
    maintenance_mode: false,
    maintenance_message: 'Site is under maintenance. Please check back later.'
})

const fetchSettings = async () => {
    loading.value = true
    try {
        const response = await api.settings.get()
        if (response.data.success) {
            const data = response.data.data || {}
            settings.value = {
                site_title: data.site_title || '',
                site_tagline: data.site_tagline || '',
                site_email: data.site_email || '',
                site_phone: data.site_phone || '',
                timezone: data.timezone || 'Asia/Kuwait',
                date_format: data.date_format || 'Y-m-d',
                time_format: data.time_format || 'H:i',
                currency: data.currency || 'KWD',
                maintenance_mode: data.maintenance_mode || false,
                maintenance_message: data.maintenance_message || 'Site is under maintenance. Please check back later.'
            }
        }
    } catch (error) {
        console.error('Error fetching settings:', error)
        showToastMessage('error', 'Error', 'Failed to load settings')
    } finally {
        loading.value = false
    }
}

const saveSettings = async () => {
    saving.value = true
    try {
        await api.settings.update(settings.value)
        showToastMessage('success', 'Success', 'Settings saved successfully')
    } catch (error) {
        console.error('Error saving settings:', error)
        showToastMessage('error', 'Error', 'Failed to save settings')
    } finally {
        saving.value = false
    }
}

const resetSettings = () => {
    fetchSettings()
}

const showToastMessage = (type, title, message) => {
    toastType.value = type
    toastTitle.value = title
    toastMessage.value = message
    showToast.value = true
}

onMounted(() => {
    fetchSettings()
})
</script>

<style scoped>
.settings-page {
    padding: 24px;
    max-width: 1000px;
}

/* Page Header */
.page-header {
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

/* Form */
.settings-form {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.settings-section {
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid #e5e7eb;
}

.settings-section:last-child {
    border-bottom: none;
}

.settings-section h3 {
    margin: 0 0 20px 0;
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
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
    font-family: inherit;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
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

.form-help {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #64748b;
}

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

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
