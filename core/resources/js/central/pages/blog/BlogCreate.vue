<template>
    <div class="blog-create-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">➕ Add New Blog</h1>
                <p class="page-subtitle">Create a new blog post</p>
            </div>
            <router-link to="/blog" class="btn btn-secondary">
                ← Back to Blogs
            </router-link>
        </div>

        <!-- Form -->
        <div class="form-container">
            <form @submit.prevent="handleSubmit">
                <div class="form-grid">
                    <div class="form-main">
                        <!-- Title -->
                        <div class="form-group">
                            <label for="title" class="form-label">
                                Title <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="title"
                                v-model="form.title"
                                class="form-input"
                                :class="{ 'error': errors.title }"
                                placeholder="Enter blog title"
                                required
                            />
                            <span v-if="errors.title" class="error-message">{{ errors.title }}</span>
                        </div>

                        <!-- Slug -->
                        <div class="form-group">
                            <label for="slug" class="form-label">Slug</label>
                            <input
                                type="text"
                                id="slug"
                                v-model="form.slug"
                                class="form-input"
                                :class="{ 'error': errors.slug }"
                                placeholder="blog-post-slug (auto-generated)"
                            />
                            <span v-if="errors.slug" class="error-message">{{ errors.slug }}</span>
                            <small class="form-help">Leave empty to auto-generate from title</small>
                        </div>

                        <!-- Content -->
                        <div class="form-group">
                            <label for="blog_content" class="form-label">
                                Content <span class="required">*</span>
                            </label>
                            <textarea
                                id="blog_content"
                                v-model="form.blog_content"
                                class="form-textarea"
                                :class="{ 'error': errors.blog_content }"
                                rows="12"
                                placeholder="Enter blog content"
                                required
                            ></textarea>
                            <span v-if="errors.blog_content" class="error-message">{{ errors.blog_content }}</span>
                        </div>
                    </div>

                    <div class="form-sidebar">
                        <!-- Status -->
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select
                                id="status"
                                v-model="form.status"
                                class="form-select"
                            >
                                <option value="draft">Draft</option>
                                <option value="publish">Publish</option>
                            </select>
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label for="category_id" class="form-label">Category</label>
                            <select
                                id="category_id"
                                v-model="form.category_id"
                                class="form-select"
                                :class="{ 'error': errors.category_id }"
                            >
                                <option value="">Select Category</option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                            <span v-if="errors.category_id" class="error-message">{{ errors.category_id }}</span>
                        </div>

                        <!-- Publish Date -->
                        <div class="form-group">
                            <label for="created_at" class="form-label">Publish Date</label>
                            <input
                                type="datetime-local"
                                id="created_at"
                                v-model="form.created_at"
                                class="form-input"
                            />
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span v-if="loading">⏳ Saving...</span>
                        <span v-else>💾 Save Blog</span>
                    </button>
                    <router-link to="/blog" class="btn btn-secondary">
                        Cancel
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'

const router = useRouter()

const loading = ref(false)
const form = ref({
    title: '',
    slug: '',
    blog_content: '',
    status: 'draft',
    category_id: '',
    created_at: new Date().toISOString().slice(0, 16)
})

const errors = ref({})
const categories = ref([])

const loadCategories = async () => {
    try {
        const response = await api.blog.categories({ per_page: 100 })
        if (response.data.success) {
            categories.value = response.data.data
        }
    } catch (error) {
        console.error('Error loading categories:', error)
    }
}

const generateSlug = (title) => {
    return title
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '')
}

const handleSubmit = async () => {
    errors.value = {}
    loading.value = true

    try {
        // Auto-generate slug if empty
        if (!form.value.slug && form.value.title) {
            form.value.slug = generateSlug(form.value.title)
        }

        const response = await api.blog.create(form.value)

        if (response.data.success) {
            router.push('/blog')
        } else {
            if (response.data.errors) {
                errors.value = response.data.errors
            } else {
                alert('Failed to create blog: ' + (response.data.message || 'Unknown error'))
            }
        }
    } catch (error) {
        console.error('Error creating blog:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            alert('Failed to create blog: ' + (error.response?.data?.message || 'Unknown error'))
        }
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    loadCategories()
})
</script>

<style scoped>
.blog-create-page {
    padding: 24px;
    max-width: 1400px;
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

/* Form Container */
.form-container {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.form-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

.form-main {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Form Groups */
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

.required {
    color: #ef4444;
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

.form-input.error,
.form-textarea.error,
.form-select.error {
    border-color: #ef4444;
}

.form-textarea {
    resize: vertical;
    min-height: 300px;
}

.form-help {
    color: #64748b;
    font-size: 13px;
}

.error-message {
    color: #ef4444;
    font-size: 13px;
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

@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
