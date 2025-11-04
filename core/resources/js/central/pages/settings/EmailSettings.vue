<template>
    <div class="settings-page">
        <div class="page-header">
            <h2>📧 Email Settings</h2>
            <p class="page-subtitle">Configure email settings</p>
        </div>

        <form @submit.prevent="saveSettings" class="settings-form">
            <div class="settings-section">
                <h3>SMTP Configuration</h3>
                <FormInput v-model="settings.smtp_host" label="SMTP Host" />
                <FormInput v-model="settings.smtp_port" type="number" label="SMTP Port" />
                <FormInput v-model="settings.smtp_username" label="SMTP Username" />
                <FormInput v-model="settings.smtp_password" type="password" label="SMTP Password" />
                <div class="form-group">
                    <label>SMTP Encryption</label>
                    <select v-model="settings.smtp_encryption" class="form-select">
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                        <option value="">None</option>
                    </select>
                </div>
            </div>

            <div class="settings-section">
                <h3>From Address</h3>
                <FormInput v-model="settings.mail_from_address" type="email" label="From Email" />
                <FormInput v-model="settings.mail_from_name" label="From Name" />
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" :disabled="loading">Save Settings</button>
            </div>
        </form>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import FormInput from '../../components/FormInput.vue'
import Toast from '../../components/Toast.vue'

export default {
    name: 'EmailSettings',
    components: { FormInput, Toast },
    setup() {
        const loading = ref(false)
        const settings = ref({
            smtp_host: '',
            smtp_port: 587,
            smtp_username: '',
            smtp_password: '',
            smtp_encryption: 'tls',
            mail_from_address: '',
            mail_from_name: ''
        })
        const showToast = ref(false)
        const toastType = ref('success')
        const toastTitle = ref('')
        const toastMessage = ref('')

        const fetchSettings = async () => {
            loading.value = true
            try {
                const response = await api.settings.list()
                if (response.data.success) {
                    const data = response.data.data || {}
                    settings.value = {
                        smtp_host: data.smtp_host || '',
                        smtp_port: data.smtp_port || 587,
                        smtp_username: data.smtp_username || '',
                        smtp_password: data.smtp_password || '',
                        smtp_encryption: data.smtp_encryption || 'tls',
                        mail_from_address: data.mail_from_address || '',
                        mail_from_name: data.mail_from_name || ''
                    }
                }
            } catch (error) {
                console.error('Error fetching settings:', error)
            } finally {
                loading.value = false
            }
        }

        const saveSettings = async () => {
            loading.value = true
            try {
                await api.settings.update(settings.value)
                showToastMessage('success', 'Success', 'Email settings saved successfully')
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to save email settings')
            } finally {
                loading.value = false
            }
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

        return { loading, settings, showToast, toastType, toastTitle, toastMessage, saveSettings }
    }
}
</script>

<style scoped>
.settings-page {
    padding: 20px;
    max-width: 900px;
}

.page-header {
    margin-bottom: 24px;
}

.page-header h2 {
    margin: 0 0 4px 0;
    font-size: 24px;
    color: #1e293b;
}

.page-subtitle {
    color: #64748b;
    font-size: 14px;
    margin: 0;
}

.settings-form {
    background: white;
    border-radius: 8px;
    padding: 24px;
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
    margin: 0 0 16px 0;
    font-size: 18px;
    color: #1e293b;
}

.form-group {
    margin-bottom: 20px;
}

.form-select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}
</style>



