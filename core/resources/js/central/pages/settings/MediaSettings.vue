<template>
    <div class="media-settings-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">🖼️ Media Settings</h1>
                <p class="page-subtitle">Configure media upload and storage settings</p>
            </div>
        </div>

        <form @submit.prevent="saveSettings" class="settings-form">
            <div class="settings-section">
                <h3>Upload Settings</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Max File Size (MB) <span class="required">*</span></label>
                        <input
                            type="number"
                            v-model="settings.max_file_size"
                            class="form-input"
                            placeholder="10"
                            min="1"
                            required
                        />
                        <small class="form-help">Maximum file size in megabytes</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Allowed File Types</label>
                        <input
                            type="text"
                            v-model="settings.allowed_types"
                            class="form-input"
                            placeholder="jpg,jpeg,png,gif,pdf,doc,docx"
                        />
                        <small class="form-help">Comma-separated list of allowed file extensions</small>
                    </div>
                </div>
            </div>

            <div class="settings-section">
                <h3>Image Processing</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Image Quality</label>
                        <input
                            type="number"
                            v-model="settings.image_quality"
                            class="form-input"
                            placeholder="85"
                            min="1"
                            max="100"
                        />
                        <small class="form-help">Image quality (1-100)</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Image Width (px)</label>
                        <input
                            type="number"
                            v-model="settings.max_image_width"
                            class="form-input"
                            placeholder="1920"
                            min="1"
                        />
                        <small class="form-help">Maximum width for uploaded images</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Image Height (px)</label>
                        <input
                            type="number"
                            v-model="settings.max_image_height"
                            class="form-input"
                            placeholder="1080"
                            min="1"
                        />
                        <small class="form-help">Maximum height for uploaded images</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Thumbnail Size (px)</label>
                        <input
                            type="number"
                            v-model="settings.thumbnail_size"
                            class="form-input"
                            placeholder="150"
                            min="1"
                        />
                        <small class="form-help">Size of generated thumbnails</small>
                    </div>
                </div>
            </div>

            <div class="settings-section">
                <h3>Storage Settings</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Storage Driver</label>
                        <select v-model="settings.storage_driver" class="form-select">
                            <option value="local">Local Storage</option>
                            <option value="s3">Amazon S3</option>
                            <option value="azure">Azure Blob</option>
                            <option value="gcs">Google Cloud Storage</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Storage Path</label>
                        <input
                            type="text"
                            v-model="settings.storage_path"
                            class="form-input"
                            placeholder="/storage/media"
                        />
                        <small class="form-help">Path where media files are stored</small>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" :disabled="saving">
                    <span v-if="saving">⏳ Saving...</span>
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

const saving = ref(false)
const showToast = ref(false)
const toastType = ref('success')
const toastTitle = ref('')
const toastMessage = ref('')

const settings = ref({
    max_file_size: 10,
    allowed_types: 'jpg,jpeg,png,gif,pdf,doc,docx',
    image_quality: 85,
    max_image_width: 1920,
    max_image_height: 1080,
    thumbnail_size: 150,
    storage_driver: 'local',
    storage_path: '/storage/media'
})

const fetchSettings = async () => {
    try {
        const response = await api.settings.get({ type: 'media' })
        if (response.data.success) {
            const data = response.data.data || {}
            settings.value = {
                max_file_size: data.max_file_size || 10,
                allowed_types: data.allowed_types || 'jpg,jpeg,png,gif,pdf,doc,docx',
                image_quality: data.image_quality || 85,
                max_image_width: data.max_image_width || 1920,
                max_image_height: data.max_image_height || 1080,
                thumbnail_size: data.thumbnail_size || 150,
                storage_driver: data.storage_driver || 'local',
                storage_path: data.storage_path || '/storage/media'
            }
        }
    } catch (error) {
        console.error('Error fetching media settings:', error)
        showToastMessage('error', 'Error', 'Failed to load settings')
    }
}

const saveSettings = async () => {
    saving.value = true
    try {
        await api.settings.update({ ...settings.value, type: 'media' })
        showToastMessage('success', 'Success', 'Media settings saved successfully')
    } catch (error) {
        console.error('Error saving media settings:', error)
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
.media-settings-page {
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
.form-select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
    font-family: inherit;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #3b82f6;
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
