<template>
    <div class="blog-view-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">👁️ View Blog</h1>
                <p class="page-subtitle">Blog post details</p>
            </div>
            <div class="page-actions">
                <router-link :to="`/blog/edit/${blog.id}`" class="btn btn-primary">✏️ Edit</router-link>
                <router-link to="/blog" class="btn btn-secondary">← Back to Blogs</router-link>
            </div>
        </div>

        <div v-if="loading" class="loading-overlay">
            <div class="spinner"></div>
            <p>Loading blog...</p>
        </div>

        <div v-else class="blog-content">
            <div class="blog-header">
                <h2 class="blog-title">{{ blog.title }}</h2>
                <div class="blog-meta">
                    <span class="meta-item">📅 {{ formatDate(blog.created_at) }}</span>
                    <span class="meta-item">📂 {{ blog.category?.name || 'Uncategorized' }}</span>
                    <span class="meta-item">
                        <StatusBadge :status="blog.status === 'publish' ? 'active' : 'inactive'" />
                    </span>
                </div>
            </div>

            <div class="blog-body">
                <div class="blog-content-text" v-html="blog.blog_content || blog.content || 'No content'"></div>
            </div>

            <div class="blog-footer">
                <div class="blog-info">
                    <p><strong>Slug:</strong> <code>{{ blog.slug }}</code></p>
                    <p><strong>Created:</strong> {{ formatDateTime(blog.created_at) }}</p>
                    <p v-if="blog.updated_at"><strong>Updated:</strong> {{ formatDateTime(blog.updated_at) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import StatusBadge from '../../components/StatusBadge.vue'

const router = useRouter()
const route = useRoute()

const loading = ref(true)
const blog = ref({})

const loadBlog = async () => {
    loading.value = true
    try {
        const response = await axios.get(`/blogs/${route.params.id}`)
        if (response.data.success) {
            blog.value = response.data.data
        }
    } catch (error) {
        console.error('Error loading blog:', error)
        alert('Failed to load blog')
        router.push('/blog')
    } finally {
        loading.value = false
    }
}

const formatDate = (date) => {
    if (!date) return 'N/A'
    return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
}

const formatDateTime = (date) => {
    if (!date) return 'N/A'
    return new Date(date).toLocaleString('en-US')
}

onMounted(() => loadBlog())
</script>

<style scoped>
.blog-view-page { padding: 24px; max-width: 1200px; }
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
.page-title { font-size: 28px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0; }
.page-subtitle { color: #64748b; font-size: 15px; margin: 0; }
.page-actions { display: flex; gap: 12px; }
.loading-overlay { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px; color: #64748b; }
.spinner { width: 40px; height: 40px; border: 4px solid #f1f5f9; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 16px; }
@keyframes spin { to { transform: rotate(360deg); } }
.blog-content { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); }
.blog-header { margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb; }
.blog-title { font-size: 32px; font-weight: 700; color: #1e293b; margin: 0 0 16px 0; }
.blog-meta { display: flex; gap: 20px; flex-wrap: wrap; }
.meta-item { color: #64748b; font-size: 14px; }
.blog-body { margin-bottom: 30px; }
.blog-content-text { line-height: 1.8; color: #334155; }
.blog-footer { padding-top: 20px; border-top: 1px solid #e5e7eb; }
.blog-info p { margin: 8px 0; color: #64748b; font-size: 14px; }
.blog-info code { background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-family: monospace; }
.btn { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.2s; font-size: 15px; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover { background: #2563eb; }
.btn-secondary { background: #e5e7eb; color: #374151; }
.btn-secondary:hover { background: #d1d5db; }
</style>

