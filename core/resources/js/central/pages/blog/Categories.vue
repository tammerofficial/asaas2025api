<template>
    <div class="categories-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">📂 Blog Categories</h1>
                <p class="page-subtitle">Manage blog categories</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary">➕ Add Category</button>
            </div>
        </div>

        <DataTable
            :columns="columns"
            :data="categories"
            :loading="loading"
            :pagination="pagination"
            @page-change="loadCategories"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DataTable from '../../components/DataTable.vue'

const categories = ref([])
const loading = ref(false)
const pagination = ref({ current_page: 1, last_page: 1, per_page: 20, total: 0 })

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'slug', label: 'Slug' },
    { key: 'status', label: 'Status' },
    { key: 'created_at', label: 'Created At' },
]

const loadCategories = async (page = 1) => {
    loading.value = true
    try {
        const response = await axios.get('/blog/categories', { params: { page } })
        if (response.data.success) {
            categories.value = response.data.data
            pagination.value = response.data.meta
        }
    } catch (error) {
        console.error('Error:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => loadCategories())
</script>

<style scoped>
.categories-page { padding: 24px; }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.page-title { font-size: 24px; font-weight: 600; margin: 0 0 4px 0; }
.page-subtitle { color: #64748b; margin: 0; }
.btn { padding: 10px 20px; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; }
.btn-primary { background: #3b82f6; color: white; }
</style>
