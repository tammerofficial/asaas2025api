<template>
    <div class="tickets-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">🎫 All Tickets</h1>
                <p class="page-subtitle">Manage support tickets</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary">➕ Create Ticket</button>
            </div>
        </div>

        <DataTable
            :columns="columns"
            :data="tickets"
            :loading="loading"
            :pagination="pagination"
            @page-change="loadTickets"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DataTable from '../../components/DataTable.vue'

const tickets = ref([])
const loading = ref(false)
const pagination = ref({ current_page: 1, last_page: 1, per_page: 20, total: 0 })

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'title', label: 'Title' },
    { key: 'user_name', label: 'User' },
    { key: 'status', label: 'Status' },
    { key: 'priority', label: 'Priority' },
    { key: 'created_at', label: 'Created At' },
]

const loadTickets = async (page = 1) => {
    loading.value = true
    try {
        const response = await axios.get('/support/tickets', { params: { page } })
        if (response.data.success) {
            tickets.value = response.data.data
            pagination.value = response.data.meta
        }
    } catch (error) {
        console.error('Error:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => loadTickets())
</script>

<style scoped>
.tickets-page { padding: 24px; }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.page-title { font-size: 24px; font-weight: 600; margin: 0 0 4px 0; }
.page-subtitle { color: #64748b; margin: 0; }
.btn { padding: 10px 20px; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; }
.btn-primary { background: #3b82f6; color: white; }
</style>
