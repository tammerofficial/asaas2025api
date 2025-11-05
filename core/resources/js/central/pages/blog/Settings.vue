<template>
    <div class="blog-settings-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">⚙️ Blog Settings</h1>
                <p class="page-subtitle">Configure blog settings and preferences</p>
            </div>
        </div>

        <!-- Settings Form -->
        <div class="form-container">
            <form @submit.prevent="handleSubmit">
                <div class="settings-section">
                    <h3 class="section-title">📝 General Settings</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="posts_per_page" class="form-label">
                                Posts Per Page
                            </label>
                            <input
                                type="number"
                                id="posts_per_page"
                                v-model="form.posts_per_page"
                                class="form-input"
                                min="1"
                                max="100"
                            />
                            <small class="form-help">Number of posts to display per page</small>
                        </div>

                        <div class="form-group">
                            <label for="default_status" class="form-label">
                                Default Post Status
                            </label>
                            <select id="default_status" v-model="form.default_status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="publish">Publish</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="allow_comments" class="form-label">
                                Allow Comments
                            </label>
                            <div class="form-switch">
                                <input
                                    type="checkbox"
                                    id="allow_comments"
                                    v-model="form.allow_comments"
                                    class="switch-input"
                                />
                                <label for="allow_comments" class="switch-label"></label>
                            </div>
                            <small class="form-help">Enable comments on blog posts</small>
                        </div>

                        <div class="form-group">
                            <label for="require_moderation" class="form-label">
                                Require Moderation
                            </label>
                            <div class="form-switch">
                                <input
                                    type="checkbox"
                                    id="require_moderation"
                                    v-model="form.require_moderation"
                                    class="switch-input"
                                />
                                <label for="require_moderation" class="switch-label"></label>
                            </div>
                            <small class="form-help">Comments require approval before publishing</small>
                        </div>
                    </div>
                </div>

                <div class="settings-section">
                    <h3 class="section-title">🔍 SEO Settings</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="meta_title" class="form-label">
                                Default Meta Title
                            </label>
                            <input
                                type="text"
                                id="meta_title"
                                v-model="form.meta_title"
                                class="form-input"
                                placeholder="Blog Meta Title"
                            />
                        </div>

                        <div class="form-group">
                            <label for="meta_description" class="form-label">
                                Default Meta Description
                            </label>
                            <textarea
                                id="meta_description"
                                v-model="form.meta_description"
                                class="form-textarea"
                                rows="3"
                                placeholder="Blog meta description"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <div class="settings-section">
                    <h3 class="section-title">📧 Notification Settings</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="notify_new_comment" class="form-label">
                                Notify on New Comment
                            </label>
                            <div class="form-switch">
                                <input
                                    type="checkbox"
                                    id="notify_new_comment"
                                    v-model="form.notify_new_comment"
                                    class="switch-input"
                                />
                                <label for="notify_new_comment" class="switch-label"></label>
                            </div>
                            <small class="form-help">Send email notification when new comment is posted</small>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span v-if="loading">⏳ Saving...</span>
                        <span v-else>💾 Save Settings</span>
                    </button>
                    <button type="button" class="btn btn-secondary" @click="resetForm">
                        Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

const loading = ref(false)
const form = ref({
    posts_per_page: 10,
    default_status: 'draft',
    allow_comments: true,
    require_moderation: true,
    meta_title: '',
    meta_description: '',
    notify_new_comment: true
})

const originalForm = ref({})

const loadSettings = async () => {
    loading.value = true
    try {
        const response = await api.settings.get({ type: 'blog' })
        if (response.data.success && response.data.data) {
            const settings = response.data.data
            form.value = {
                posts_per_page: settings.posts_per_page || 10,
                default_status: settings.default_status || 'draft',
                allow_comments: settings.allow_comments !== undefined ? settings.allow_comments : true,
                require_moderation: settings.require_moderation !== undefined ? settings.require_moderation : true,
                meta_title: settings.meta_title || '',
                meta_description: settings.meta_description || '',
                notify_new_comment: settings.notify_new_comment !== undefined ? settings.notify_new_comment : true
            }
            originalForm.value = { ...form.value }
        }
    } catch (error) {
        console.error('Error loading settings:', error)
    } finally {
        loading.value = false
    }
}

const handleSubmit = async () => {
    loading.value = true
    try {
        const response = await api.settings.update({ 
            type: 'blog',
            settings: form.value 
        })
        
        if (response.data.success) {
            alert('Settings saved successfully!')
            originalForm.value = { ...form.value }
        } else {
            alert('Failed to save settings: ' + (response.data.message || 'Unknown error'))
        }
    } catch (error) {
        console.error('Error saving settings:', error)
        alert('Failed to save settings: ' + (error.response?.data?.message || 'Unknown error'))
    } finally {
        loading.value = false
    }
}

const resetForm = () => {
    if (confirm('Are you sure you want to reset all changes?')) {
        form.value = { ...originalForm.value }
    }
}

onMounted(() => {
    loadSettings()
})
</script>

<style scoped>
.blog-settings-page {
    padding: 24px;
    max-width: 1200px;
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

/* Form Container */
.form-container {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.settings-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e5e7eb;
}

.settings-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 20px 0;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-label {
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.form-input,
.form-textarea,
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
.form-textarea:focus,
.form-select:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

.form-help {
    color: #64748b;
    font-size: 13px;
}

/* Switch Toggle */
.form-switch {
    display: flex;
    align-items: center;
}

.switch-input {
    display: none;
}

.switch-label {
    position: relative;
    width: 48px;
    height: 24px;
    background: #cbd5e1;
    border-radius: 12px;
    cursor: pointer;
    transition: background 0.2s;
}

.switch-label::before {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: white;
    top: 2px;
    left: 2px;
    transition: transform 0.2s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.switch-input:checked + .switch-label {
    background: #3b82f6;
}

.switch-input:checked + .switch-label::before {
    transform: translateX(24px);
}

/* Form Actions */
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
