<template>
    <div class="settings-page">
        <div class="page-header">
            <h2>⚙️ General Settings</h2>
            <p class="page-subtitle">Manage general application settings</p>
        </div>

        <form @submit.prevent="saveSettings" class="settings-form">
            <div class="settings-section">
                <h3>Site Information</h3>
                <FormInput v-model="settings.site_title" label="Site Title" />
                <FormInput v-model="settings.site_tagline" label="Site Tagline" />
                <FormInput v-model="settings.site_email" type="email" label="Site Email" />
            </div>

            <div class="settings-section">
                <h3>Time Zone</h3>
                <FormInput v-model="settings.timezone" label="Timezone" />
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" :disabled="loading">Save Settings</button>
                <button type="button" class="btn-secondary" @click="resetSettings">Reset</button>
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
    name: 'GeneralSettings',
    components: { FormInput, Toast },
    setup() {
        const loading = ref(false)
        const settings = ref({ site_title: '', site_tagline: '', site_email: '', timezone: '' })
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
                        site_title: data.site_title || '',
                        site_tagline: data.site_tagline || '',
                        site_email: data.site_email || '',
                        timezone: data.timezone || 'UTC'
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
                showToastMessage('success', 'Success', 'Settings saved successfully')
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to save settings')
            } finally {
                loading.value = false
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

        return { loading, settings, showToast, toastType, toastTitle, toastMessage, saveSettings, resetSettings }
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

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}
</style>



