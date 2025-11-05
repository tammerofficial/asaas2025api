<template>
    <div class="general-settings-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">⚙️ Appearance General Settings</h1>
                <p class="page-subtitle">Configure general appearance settings</p>
            </div>
            <button class="btn btn-primary" @click="saveSettings">
                💾 Save Changes
            </button>
        </div>

        <!-- Settings Form -->
        <div class="settings-container">
            <div class="settings-section">
                <h3>Site Identity</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Site Logo</label>
                        <input type="text" v-model="settings.site_logo" placeholder="Enter logo URL" class="form-input" />
                        <button class="btn btn-sm btn-outline" @click="uploadLogo">Upload Logo</button>
                    </div>
                    <div class="form-group">
                        <label>Site Favicon</label>
                        <input type="text" v-model="settings.site_favicon" placeholder="Enter favicon URL" class="form-input" />
                        <button class="btn btn-sm btn-outline" @click="uploadFavicon">Upload Favicon</button>
                    </div>
                </div>
            </div>

            <div class="settings-section">
                <h3>Display Settings</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Show Site Title</label>
                        <div class="checkbox-group">
                            <label>
                                <input type="checkbox" v-model="settings.show_site_title" />
                                Display site title in header
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Show Site Tagline</label>
                        <div class="checkbox-group">
                            <label>
                                <input type="checkbox" v-model="settings.show_site_tagline" />
                                Display site tagline in header
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="settings-section">
                <h3>Layout Settings</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Default Layout</label>
                        <select v-model="settings.default_layout" class="form-select">
                            <option value="full-width">Full Width</option>
                            <option value="boxed">Boxed</option>
                            <option value="sidebar-left">Sidebar Left</option>
                            <option value="sidebar-right">Sidebar Right</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Header Style</label>
                        <select v-model="settings.header_style" class="form-select">
                            <option value="default">Default</option>
                            <option value="minimal">Minimal</option>
                            <option value="centered">Centered</option>
                            <option value="transparent">Transparent</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

const settings = ref({
    site_logo: '',
    site_favicon: '',
    show_site_title: true,
    show_site_tagline: false,
    default_layout: 'full-width',
    header_style: 'default'
})

const loadSettings = async () => {
    try {
        // API call to load settings
        // const response = await api.appearances.generalSettings()
        // settings.value = response.data.data || {}
    } catch (error) {
        console.error('Error loading settings:', error)
    }
}

const saveSettings = async () => {
    try {
        // API call to save settings
        // await api.appearances.updateGeneralSettings(settings.value)
        alert('Settings saved successfully')
    } catch (error) {
        console.error('Error saving settings:', error)
        alert('Failed to save settings')
    }
}

const uploadLogo = () => {
    alert('Logo upload functionality will be implemented')
}

const uploadFavicon = () => {
    alert('Favicon upload functionality will be implemented')
}

onMounted(() => {
    loadSettings()
})
</script>

<style scoped>
.general-settings-page {
    padding: 24px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
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

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-outline {
    background: white;
    color: #3b82f6;
    border: 2px solid #3b82f6;
}

.btn-outline:hover {
    background: #f0f9ff;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 14px;
    margin-top: 8px;
}

/* Settings Container */
.settings-container {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.settings-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.settings-section h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 20px 0;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 600;
    color: #1e293b;
    font-size: 14px;
}

.form-input,
.form-select {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #3b82f6;
}

.checkbox-group {
    margin-top: 8px;
}

.checkbox-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 500;
}

.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}
</style>
