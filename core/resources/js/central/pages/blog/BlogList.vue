<template>
    <div class="blogs-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">📝 All Blogs</h1>
                <p class="page-subtitle">Manage all blog posts</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary">➕ Add New Blog</button>
            </div>
        </div>

        <DataTable
            :columns="columns"
            :data="blogs"
            :loading="loading"
            :pagination="pagination"
            @page-change="loadBlogs"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DataTable from '../../components/DataTable.vue'

const blogs = ref([])
const loading = ref(false)
const pagination = ref({ current_page: 1, last_page: 1, per_page: 20, total: 0 })

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'title', label: 'Title' },
    { key: 'category', label: 'Category' },
    { key: 'status', label: 'Status' },
    { key: 'views', label: 'Views' },
    { key: 'created_at', label: 'Created At' },
]

const loadBlogs = async (page = 1) => {
    loading.value = true
    try {
        const response = await axios.get('/blogs', { params: { page } })
        if (response.data.success) {
            blogs.value = response.data.data
            pagination.value = response.data.meta
        }
    } catch (error) {
        console.error('Error:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => loadBlogs())
</script>

<style scoped>
.blogs-page { padding: 24px; }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.page-title { font-size: 24px; font-weight: 600; margin: 0 0 4px 0; }
.page-subtitle { color: #64748b; margin: 0; }
.btn { padding: 10px 20px; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover { background: #2563eb; }
</style>
