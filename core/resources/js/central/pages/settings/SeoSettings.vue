<template>
    <div class="seo-settings-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">🔍 SEO Settings</h1>
                <p class="page-subtitle">Configure search engine optimization settings</p>
            </div>
        </div>

        <form @submit.prevent="saveSettings" class="settings-form">
            <div class="settings-section">
                <h3>General SEO</h3>
                <div class="form-group">
                    <label class="form-label">Site Title</label>
                    <input
                        type="text"
                        v-model="settings.seo_title"
                        class="form-input"
                        placeholder="Enter SEO title"
                    />
                    <small class="form-help">Default title for pages (50-60 characters recommended)</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea
                        v-model="settings.seo_description"
                        class="form-textarea"
                        rows="3"
                        placeholder="Enter meta description"
                    ></textarea>
                    <small class="form-help">Default meta description (150-160 characters recommended)</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Meta Keywords</label>
                    <input
                        type="text"
                        v-model="settings.seo_keywords"
                        class="form-input"
                        placeholder="keyword1, keyword2, keyword3"
                    />
                    <small class="form-help">Comma-separated keywords</small>
                </div>
            </div>

            <div class="settings-section">
                <h3>Open Graph</h3>
                <div class="form-group">
                    <label class="form-label">Open Graph Image URL</label>
                    <input
                        type="url"
                        v-model="settings.og_image"
                        class="form-input"
                        placeholder="https://example.com/image.jpg"
                    />
                    <small class="form-help">Default image for social media sharing (1200x630px recommended)</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Open Graph Type</label>
                    <select v-model="settings.og_type" class="form-select">
                        <option value="website">Website</option>
                        <option value="article">Article</option>
                        <option value="product">Product</option>
                    </select>
                </div>
            </div>

            <div class="settings-section">
                <h3>Twitter Card</h3>
                <div class="form-group">
                    <label class="form-label">Twitter Card Type</label>
                    <select v-model="settings.twitter_card" class="form-select">
                        <option value="summary">Summary</option>
                        <option value="summary_large_image">Summary Large Image</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Twitter Site</label>
                    <input
                        type="text"
                        v-model="settings.twitter_site"
                        class="form-input"
                        placeholder="@username"
                    />
                    <small class="form-help">Twitter username (without @)</small>
                </div>
            </div>

            <div class="settings-section">
                <h3>Robots & Sitemap</h3>
                <div class="form-group">
                    <label class="form-label">Robots.txt</label>
                    <textarea
                        v-model="settings.robots_txt"
                        class="form-textarea"
                        rows="5"
                        placeholder="User-agent: *&#10;Disallow: /admin"
                    ></textarea>
                    <small class="form-help">Custom robots.txt content</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Sitemap URL</label>
                    <input
                        type="url"
                        v-model="settings.sitemap_url"
                        class="form-input"
                        placeholder="https://example.com/sitemap.xml"
                    />
                    <small class="form-help">Sitemap XML file URL</small>
                </div>
                <div class="form-group">
                    <label class="form-checkbox">
                        <input
                            type="checkbox"
                            v-model="settings.auto_sitemap"
                            class="checkbox-input"
                        />
                        <span>Auto-generate Sitemap</span>
                    </label>
                </div>
            </div>

            <div class="settings-section">
                <h3>Analytics</h3>
                <div class="form-group">
                    <label class="form-label">Google Analytics ID</label>
                    <input
                        type="text"
                        v-model="settings.google_analytics"
                        class="form-input"
                        placeholder="G-XXXXXXXXXX"
                    />
                    <small class="form-help">Google Analytics 4 Measurement ID</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Google Tag Manager ID</label>
                    <input
                        type="text"
                        v-model="settings.google_tag_manager"
                        class="form-input"
                        placeholder="GTM-XXXXXXX"
                    />
                </div>
                <div class="form-group">
                    <label class="form-label">Facebook Pixel ID</label>
                    <input
                        type="text"
                        v-model="settings.facebook_pixel"
                        class="form-input"
                        placeholder="1234567890"
                    />
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
    seo_title: '',
    seo_description: '',
    seo_keywords: '',
    og_image: '',
    og_type: 'website',
    twitter_card: 'summary',
    twitter_site: '',
    robots_txt: '',
    sitemap_url: '',
    auto_sitemap: true,
    google_analytics: '',
    google_tag_manager: '',
    facebook_pixel: ''
})

const fetchSettings = async () => {
    try {
        const response = await api.settings.get({ type: 'seo' })
        if (response.data.success) {
            const data = response.data.data || {}
            settings.value = {
                seo_title: data.seo_title || '',
                seo_description: data.seo_description || '',
                seo_keywords: data.seo_keywords || '',
                og_image: data.og_image || '',
                og_type: data.og_type || 'website',
                twitter_card: data.twitter_card || 'summary',
                twitter_site: data.twitter_site || '',
                robots_txt: data.robots_txt || '',
                sitemap_url: data.sitemap_url || '',
                auto_sitemap: data.auto_sitemap !== false,
                google_analytics: data.google_analytics || '',
                google_tag_manager: data.google_tag_manager || '',
                facebook_pixel: data.facebook_pixel || ''
            }
        }
    } catch (error) {
        console.error('Error fetching SEO settings:', error)
        showToastMessage('error', 'Error', 'Failed to load settings')
    }
}

const saveSettings = async () => {
    saving.value = true
    try {
        await api.settings.update({ ...settings.value, type: 'seo' })
        showToastMessage('success', 'Success', 'SEO settings saved successfully')
    } catch (error) {
        console.error('Error saving SEO settings:', error)
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
.seo-settings-page {
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
    min-height: 100px;
}

.form-help {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #64748b;
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
</style>
