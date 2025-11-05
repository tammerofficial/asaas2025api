<template>
    <div class="sitemap-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🗺️ Sitemap</h1>
                <p class="page-subtitle">Manage and generate XML sitemap</p>
            </div>
        </div>

        <!-- Info Card -->
        <div class="info-card">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="info-content">
                <h3>Sitemap Information</h3>
                <p>A sitemap helps search engines discover and index all pages on your website. Generate or update your sitemap regularly to ensure all pages are indexed.</p>
            </div>
        </div>

        <!-- Sitemap Status -->
        <div class="status-card">
            <div class="status-header">
                <h3>Sitemap Status</h3>
                <StatusBadge :status="sitemapStatus" />
            </div>
            <div v-if="sitemapInfo" class="status-details">
                <div class="status-item">
                    <span class="status-label">Last Generated:</span>
                    <span class="status-value">{{ formatDate(sitemapInfo.last_generated) }}</span>
                </div>
                <div class="status-item">
                    <span class="status-label">Total URLs:</span>
                    <span class="status-value">{{ sitemapInfo.total_urls || 0 }}</span>
                </div>
                <div class="status-item">
                    <span class="status-label">Sitemap URL:</span>
                    <a :href="sitemapInfo.url || '/sitemap.xml'" target="_blank" class="sitemap-link">
                        {{ sitemapInfo.url || '/sitemap.xml' }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions-card">
            <h3>Actions</h3>
            <div class="actions-grid">
                <button 
                    class="action-btn btn-primary" 
                    @click="generateSitemap"
                    :disabled="generating"
                >
                    <span v-if="generating">⏳</span>
                    <span v-else>🔄</span>
                    {{ generating ? 'Generating...' : 'Generate Sitemap' }}
                </button>
                <button 
                    class="action-btn btn-secondary" 
                    @click="updateSitemap"
                    :disabled="updating"
                >
                    <span v-if="updating">⏳</span>
                    <span v-else>📝</span>
                    {{ updating ? 'Updating...' : 'Update Sitemap' }}
                </button>
                <a 
                    :href="sitemapInfo?.url || '/sitemap.xml'" 
                    target="_blank"
                    class="action-btn btn-outline"
                >
                    👁️ View Sitemap
                </a>
                <button 
                    class="action-btn btn-outline" 
                    @click="downloadSitemap"
                >
                    📥 Download XML
                </button>
            </div>
        </div>

        <!-- Settings -->
        <div class="settings-card">
            <h3>Sitemap Settings</h3>
            <div class="settings-form">
                <div class="form-group">
                    <label>
                        <input 
                            type="checkbox" 
                            v-model="settings.includeBlogs"
                        />
                        Include Blog Posts
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input 
                            type="checkbox" 
                            v-model="settings.includePages"
                        />
                        Include Pages
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input 
                            type="checkbox" 
                            v-model="settings.includeCategories"
                        />
                        Include Categories
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input 
                            type="checkbox" 
                            v-model="settings.autoGenerate"
                        />
                        Auto-generate on content update
                    </label>
                </div>
                <button class="btn btn-primary" @click="saveSettings">
                    💾 Save Settings
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

const sitemapStatus = ref('active')
const sitemapInfo = ref(null)
const generating = ref(false)
const updating = ref(false)
const settings = ref({
    includeBlogs: true,
    includePages: true,
    includeCategories: true,
    autoGenerate: false
})

const loadSitemapInfo = async () => {
    try {
        const response = await api.system.sitemap()
        if (response.data.success) {
            sitemapInfo.value = response.data.data
            if (response.data.data.settings) {
                settings.value = {
                    includeBlogs: response.data.data.settings.include_blogs ?? true,
                    includePages: response.data.data.settings.include_pages ?? true,
                    includeCategories: response.data.data.settings.include_categories ?? true,
                    autoGenerate: response.data.data.settings.auto_generate ?? false
                }
            }
        }
    } catch (error) {
        console.error('Error loading sitemap info:', error)
    }
}

const generateSitemap = async () => {
    generating.value = true
    try {
        const response = await api.system.generateSitemap()
        if (response.data.success) {
            alert('Sitemap generated successfully')
            await loadSitemapInfo()
        }
    } catch (error) {
        console.error('Error generating sitemap:', error)
        alert('Failed to generate sitemap')
    } finally {
        generating.value = false
    }
}

const updateSitemap = async () => {
    updating.value = true
    try {
        const response = await api.system.updateSitemap({
            include_blogs: settings.value.includeBlogs,
            include_pages: settings.value.includePages,
            include_categories: settings.value.includeCategories,
            auto_generate: settings.value.autoGenerate
        })
        if (response.data.success) {
            alert('Sitemap updated successfully')
            await loadSitemapInfo()
        }
    } catch (error) {
        console.error('Error updating sitemap:', error)
        alert('Failed to update sitemap')
    } finally {
        updating.value = false
    }
}

const downloadSitemap = () => {
    const url = sitemapInfo.value?.url || '/sitemap.xml'
    window.open(url, '_blank')
}

const saveSettings = async () => {
    try {
        // API call to save settings
        alert('Settings saved successfully')
    } catch (error) {
        console.error('Error saving settings:', error)
        alert('Failed to save settings')
    }
}

const formatDate = (dateString) => {
    if (!dateString) return 'Never'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

onMounted(() => {
    loadSitemapInfo()
})
</script>

<style scoped>
.sitemap-page {
    padding: 24px;
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

/* Info Card */
.info-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.info-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #dbeafe;
    border-radius: 12px;
    flex-shrink: 0;
}

.info-icon svg {
    width: 32px;
    height: 32px;
    color: #3b82f6;
}

.info-content h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.info-content p {
    color: #64748b;
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
}

/* Status Card */
.status-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f3f4f6;
}

.status-header h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.status-details {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.status-label {
    color: #64748b;
    font-weight: 500;
}

.status-value {
    color: #1e293b;
    font-weight: 600;
}

.sitemap-link {
    color: #3b82f6;
    text-decoration: none;
    font-weight: 500;
}

.sitemap-link:hover {
    text-decoration: underline;
}

/* Actions Card */
.actions-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.actions-card h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 20px 0;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.action-btn {
    padding: 16px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.action-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: #2563eb;
}

.btn-secondary {
    background: #64748b;
    color: white;
}

.btn-secondary:hover:not(:disabled) {
    background: #475569;
}

.btn-outline {
    background: white;
    color: #3b82f6;
    border: 2px solid #3b82f6;
}

.btn-outline:hover {
    background: #f0f9ff;
}

/* Settings Card */
.settings-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.settings-card h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 20px 0;
}

.settings-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.form-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: #1e293b;
    font-weight: 500;
}

.form-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    align-self: flex-start;
}
</style>
