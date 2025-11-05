<template>
    <div class="page-edit-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">✏️ Edit Page</h1>
                <p class="page-subtitle">Edit website page</p>
            </div>
            <router-link to="/pages" class="btn btn-secondary">← Back to Pages</router-link>
        </div>

        <div class="form-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading page...</p>
            </div>

            <form v-else @submit.prevent="handleSubmit">
                <div class="form-grid">
                    <div class="form-main">
                        <div class="form-group">
                            <label for="title" class="form-label">Title <span class="required">*</span></label>
                            <input type="text" id="title" v-model="form.title" class="form-input" :class="{ 'error': errors.title }" required />
                            <span v-if="errors.title" class="error-message">{{ errors.title }}</span>
                        </div>

                        <div class="form-group">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" id="slug" v-model="form.slug" class="form-input" :class="{ 'error': errors.slug }" />
                            <span v-if="errors.slug" class="error-message">{{ errors.slug }}</span>
                            <small class="form-help">Leave empty to auto-generate from title</small>
                        </div>

                        <div class="form-group">
                            <label for="page_content" class="form-label">Content</label>
                            <textarea id="page_content" v-model="form.page_content" class="form-textarea" :class="{ 'error': errors.page_content }" rows="15"></textarea>
                            <span v-if="errors.page_content" class="error-message">{{ errors.page_content }}</span>
                        </div>
                    </div>

                    <div class="form-sidebar">
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" v-model="form.status" class="form-select">
                                <option :value="0">Draft</option>
                                <option :value="1">Published</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="visibility" class="form-label">Visibility</label>
                            <select id="visibility" v-model="form.visibility" class="form-select">
                                <option :value="0">Private</option>
                                <option :value="1">Public</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="saving">
                        <span v-if="saving">⏳ Saving...</span>
                        <span v-else>💾 Update Page</span>
                    </button>
                    <router-link to="/pages" class="btn btn-secondary">Cancel</router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const route = useRoute()

const loading = ref(true)
const saving = ref(false)
const form = ref({
    title: '',
    slug: '',
    page_content: '',
    status: 0,
    visibility: 1
})

const errors = ref({})

const loadPage = async () => {
    loading.value = true
    try {
        const response = await axios.get(`/pages/${route.params.id}`)
        if (response.data.success) {
            const page = response.data.data
            form.value = {
                title: page.title || '',
                slug: page.slug || '',
                page_content: page.page_content || page.content || '',
                status: page.status ?? 0,
                visibility: page.visibility ?? 1
            }
        }
    } catch (error) {
        console.error('Error loading page:', error)
        alert('Failed to load page')
        router.push('/pages')
    } finally {
        loading.value = false
    }
}

const generateSlug = (title) => {
    return title.toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/[\s_-]+/g, '-').replace(/^-+|-+$/g, '')
}

const handleSubmit = async () => {
    errors.value = {}
    saving.value = true

    try {
        if (!form.value.slug && form.value.title) {
            form.value.slug = generateSlug(form.value.title)
        }

        const response = await axios.put(`/pages/${route.params.id}`, form.value)

        if (response.data.success) {
            router.push('/pages')
        } else {
            if (response.data.errors) {
                errors.value = response.data.errors
            } else {
                alert('Failed to update page: ' + (response.data.message || 'Unknown error'))
            }
        }
    } catch (error) {
        console.error('Error updating page:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            alert('Failed to update page: ' + (error.response?.data?.message || 'Unknown error'))
        }
    } finally {
        saving.value = false
    }
}

onMounted(() => loadPage())
</script>

<style scoped>
.page-edit-page { padding: 24px; max-width: 1400px; }
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
.page-title { font-size: 28px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0; }
.page-subtitle { color: #64748b; font-size: 15px; margin: 0; }
.form-container { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); position: relative; min-height: 200px; }
.loading-overlay { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px; color: #64748b; }
.spinner { width: 40px; height: 40px; border: 4px solid #f1f5f9; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 16px; }
@keyframes spin { to { transform: rotate(360deg); } }
.form-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
.form-main, .form-sidebar { display: flex; flex-direction: column; gap: 20px; }
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-label { font-weight: 600; color: #374151; font-size: 14px; }
.required { color: #ef4444; }
.form-input, .form-textarea, .form-select { width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 15px; transition: border-color 0.2s; font-family: inherit; }
.form-input:focus, .form-textarea:focus, .form-select:focus { outline: none; border-color: #3b82f6; }
.form-input.error, .form-textarea.error, .form-select.error { border-color: #ef4444; }
.form-textarea { resize: vertical; min-height: 400px; }
.form-help { color: #64748b; font-size: 13px; }
.error-message { color: #ef4444; font-size: 13px; }
.form-actions { display: flex; gap: 12px; margin-top: 30px; padding-top: 30px; border-top: 1px solid #e5e7eb; }
.btn { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.2s; font-size: 15px; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover:not(:disabled) { background: #2563eb; }
.btn-primary:disabled { background: #9ca3af; cursor: not-allowed; }
.btn-secondary { background: #e5e7eb; color: #374151; }
.btn-secondary:hover { background: #d1d5db; }
@media (max-width: 1024px) { .form-grid { grid-template-columns: 1fr; } }
</style>

