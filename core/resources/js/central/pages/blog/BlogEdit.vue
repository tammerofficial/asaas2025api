<template>
    <div class="blog-edit-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">✏️ Edit Blog</h1>
                <p class="page-subtitle">Edit blog post</p>
            </div>
            <router-link to="/blog" class="btn btn-secondary">← Back to Blogs</router-link>
        </div>

        <div class="form-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading blog...</p>
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
                            <label for="blog_content" class="form-label">Content <span class="required">*</span></label>
                            <textarea id="blog_content" v-model="form.blog_content" class="form-textarea" :class="{ 'error': errors.blog_content }" rows="12" required></textarea>
                            <span v-if="errors.blog_content" class="error-message">{{ errors.blog_content }}</span>
                        </div>
                    </div>

                    <div class="form-sidebar">
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" v-model="form.status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="publish">Publish</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="category_id" class="form-label">Category</label>
                            <select id="category_id" v-model="form.category_id" class="form-select" :class="{ 'error': errors.category_id }">
                                <option value="">Select Category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                            </select>
                            <span v-if="errors.category_id" class="error-message">{{ errors.category_id }}</span>
                        </div>

                        <div class="form-group">
                            <label for="created_at" class="form-label">Publish Date</label>
                            <input type="datetime-local" id="created_at" v-model="form.created_at" class="form-input" />
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="saving">
                        <span v-if="saving">⏳ Saving...</span>
                        <span v-else>💾 Update Blog</span>
                    </button>
                    <router-link to="/blog" class="btn btn-secondary">Cancel</router-link>
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
    blog_content: '',
    status: 'draft',
    category_id: '',
    created_at: ''
})

const errors = ref({})
const categories = ref([])

const loadBlog = async () => {
    loading.value = true
    try {
        const response = await axios.get(`/blogs/${route.params.id}`)
        if (response.data.success) {
            const blog = response.data.data
            form.value = {
                title: blog.title || '',
                slug: blog.slug || '',
                blog_content: blog.blog_content || blog.content || '',
                status: blog.status || 'draft',
                category_id: blog.category_id || '',
                created_at: blog.created_at ? new Date(blog.created_at).toISOString().slice(0, 16) : ''
            }
        }
    } catch (error) {
        console.error('Error loading blog:', error)
        alert('Failed to load blog')
        router.push('/blog')
    } finally {
        loading.value = false
    }
}

const loadCategories = async () => {
    try {
        const response = await axios.get('/blog/categories', { params: { per_page: 100 } })
        if (response.data.success) {
            categories.value = response.data.data
        }
    } catch (error) {
        console.error('Error loading categories:', error)
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

        const response = await axios.put(`/blogs/${route.params.id}`, form.value)

        if (response.data.success) {
            router.push('/blog')
        } else {
            if (response.data.errors) {
                errors.value = response.data.errors
            } else {
                alert('Failed to update blog: ' + (response.data.message || 'Unknown error'))
            }
        }
    } catch (error) {
        console.error('Error updating blog:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            alert('Failed to update blog: ' + (error.response?.data?.message || 'Unknown error'))
        }
    } finally {
        saving.value = false
    }
}

onMounted(() => {
    loadBlog()
    loadCategories()
})
</script>

<style scoped>
.blog-edit-page { padding: 24px; max-width: 1400px; }
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
.form-textarea { resize: vertical; min-height: 300px; }
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

