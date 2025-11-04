<template>
    <div class="pages-list-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">📄 All Pages</h1>
                <p class="page-subtitle">Manage all website pages</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary">➕ Add New Page</button>
            </div>
        </div>

        <DataTable
            :columns="columns"
            :data="pages"
            :loading="loading"
            :pagination="pagination"
            @page-change="loadPages"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DataTable from '../../components/DataTable.vue'

const pages = ref([])
const loading = ref(false)
const pagination = ref({ current_page: 1, last_page: 1, per_page: 20, total: 0 })

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'title', label: 'Title' },
    { key: 'slug', label: 'Slug' },
    { key: 'status', label: 'Status' },
    { key: 'visibility', label: 'Visibility' },
    { key: 'created_at', label: 'Created At' },
]

const loadPages = async (page = 1) => {
    loading.value = true
    try {
        const response = await axios.get('/pages', { params: { page } })
        if (response.data.success) {
            pages.value = response.data.data
            pagination.value = response.data.meta
        }
    } catch (error) {
        console.error('Error:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => loadPages())
</script>

<style scoped>
.pages-list-page { padding: 24px; }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.page-title { font-size: 24px; font-weight: 600; margin: 0 0 4px 0; }
.page-subtitle { color: #64748b; margin: 0; }
.btn { padding: 10px 20px; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; }
.btn-primary { background: #3b82f6; color: white; }
</style>
