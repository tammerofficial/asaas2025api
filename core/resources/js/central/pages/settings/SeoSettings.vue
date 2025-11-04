<template>
    <div class="settings-page">
        <div class="page-header">
            <h2>🔍 SEO Settings</h2>
            <p class="page-subtitle">Configure SEO and meta settings</p>
        </div>

        <form @submit.prevent="saveSettings" class="settings-form">
            <div class="settings-section">
                <h3>Meta Information</h3>
                <FormInput v-model="settings.meta_title" label="Meta Title" />
                <FormInput v-model="settings.meta_description" label="Meta Description" />
                <FormInput v-model="settings.meta_keywords" label="Meta Keywords" />
            </div>

            <div class="settings-section">
                <h3>Open Graph</h3>
                <FormInput v-model="settings.og_image" label="OG Image URL" />
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
    name: 'SeoSettings',
    components: { FormInput, Toast },
    setup() {
        const loading = ref(false)
        const settings = ref({ meta_title: '', meta_description: '', meta_keywords: '', og_image: '' })
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
                        meta_title: data.meta_title || '',
                        meta_description: data.meta_description || '',
                        meta_keywords: data.meta_keywords || '',
                        og_image: data.og_image || ''
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
                showToastMessage('success', 'Success', 'SEO settings saved successfully')
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to save SEO settings')
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

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}
</style>



