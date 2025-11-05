<template>
    <div class="tickets-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🎫 Support Tickets</h1>
                <p class="page-subtitle">Manage all support tickets</p>
            </div>
            <router-link to="/support/create" class="btn btn-primary">
                ➕ Create Ticket
            </router-link>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search tickets..."
                    class="search-input"
                    @input="debounceSearch"
                />
            </div>
            <select v-model="statusFilter" class="filter-select" @change="loadTickets(1)">
                <option value="">All Status</option>
                <option value="open">Open</option>
                <option value="pending">Pending</option>
                <option value="closed">Closed</option>
            </select>
            <select v-model="priorityFilter" class="filter-select" @change="loadTickets(1)">
                <option value="">All Priority</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
        </div>

        <!-- Tickets Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading tickets...</p>
            </div>
            
            <table v-else class="tickets-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="tickets.length === 0">
                        <td colspan="7" class="empty-cell">
                            No tickets found
                        </td>
                    </tr>
                    <tr v-else v-for="ticket in tickets" :key="ticket.id">
                        <td>{{ ticket.id }}</td>
                        <td>
                            <div class="ticket-title">
                                <strong>{{ ticket.title }}</strong>
                            </div>
                        </td>
                        <td>{{ ticket.user_name || 'Unknown' }}</td>
                        <td>
                            <StatusBadge :status="ticket.status || 'open'" />
                        </td>
                        <td>
                            <span class="priority-badge" :class="ticket.priority || 'medium'">
                                {{ (ticket.priority || 'medium').toUpperCase() }}
                            </span>
                        </td>
                        <td>{{ formatDate(ticket.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="viewTicket(ticket)"
                                    title="View"
                                >
                                    👁️
                                </button>
                                <button 
                                    class="btn-icon" 
                                    @click="editTicket(ticket)"
                                    title="Edit"
                                >
                                    ✏️
                                </button>
                                <button 
                                    class="btn-icon btn-danger" 
                                    @click="deleteTicket(ticket)"
                                    title="Delete"
                                >
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="!loading && tickets.length > 0 && pagination.last_page > 1" class="pagination">
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page === 1"
                @click="loadTickets(pagination.current_page - 1)"
            >
                ← Previous
            </button>
            <span class="pagination-info">
                Page {{ pagination.current_page }} of {{ pagination.last_page }}
                ({{ pagination.total }} total)
            </span>
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page >= pagination.last_page"
                @click="loadTickets(pagination.current_page + 1)"
            >
                Next →
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

const router = useRouter()

const tickets = ref([])
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const priorityFilter = ref('')
const pagination = ref({ 
    current_page: 1, 
    last_page: 1, 
    per_page: 20, 
    total: 0 
})

let searchTimeout = null

const loadTickets = async (page = 1) => {
    loading.value = true
    try {
        const params = {
            page,
            per_page: pagination.value.per_page
        }
        
        if (searchQuery.value) {
            params.search = searchQuery.value
        }
        
        if (statusFilter.value) {
            params.status = statusFilter.value
        }
        
        if (priorityFilter.value) {
            params.priority = priorityFilter.value
        }
        
        const response = await api.support.tickets(params)
        
        if (response.data.success) {
            tickets.value = response.data.data
            if (response.data.meta) {
                pagination.value = response.data.meta
            }
        }
    } catch (error) {
        console.error('Error loading tickets:', error)
        tickets.value = []
    } finally {
        loading.value = false
    }
}

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        loadTickets(1)
    }, 500)
}

const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const viewTicket = (ticket) => {
    router.push(`/support/${ticket.id}`)
}

const editTicket = (ticket) => {
    router.push(`/support/${ticket.id}/edit`)
}

const deleteTicket = async (ticket) => {
    if (!confirm(`Are you sure you want to delete ticket "${ticket.title}"?`)) {
        return
    }
    
    try {
        await api.support.delete(ticket.id)
        await loadTickets(pagination.value.current_page)
    } catch (error) {
        console.error('Error deleting ticket:', error)
        alert('Failed to delete ticket')
    }
}

onMounted(() => {
    loadTickets()
})
</script>

<style scoped>
.tickets-page {
    padding: 24px;
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

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

/* Filters */
.filters-bar {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.search-box {
    flex: 1;
}

.search-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
}

.filter-select {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    background: white;
}

/* Table */
.table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    position: relative;
    min-height: 400px;
}

.tickets-table {
    width: 100%;
    border-collapse: collapse;
}

.tickets-table thead {
    background: #f8fafc;
}

.tickets-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tickets-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.ticket-title strong {
    color: #1e293b;
}

.priority-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.priority-badge.low {
    background: #dbeafe;
    color: #1e40af;
}

.priority-badge.medium {
    background: #fef3c7;
    color: #92400e;
}

.priority-badge.high {
    background: #fee2e2;
    color: #991b1b;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-icon {
    background: #f1f5f9;
    border: none;
    padding: 8px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.2s;
}

.btn-icon:hover {
    background: #e2e8f0;
}

.btn-icon.btn-danger:hover {
    background: #fee2e2;
}

.empty-cell {
    text-align: center;
    padding: 40px !important;
    color: #64748b;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e5e7eb;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-bottom: 16px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-top: 30px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.pagination-btn {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
}

.pagination-btn:hover:not(:disabled) {
    background: #2563eb;
}

.pagination-btn:disabled {
    background: #e5e7eb;
    color: #94a3b8;
    cursor: not-allowed;
}

.pagination-info {
    color: #64748b;
    font-weight: 500;
}
</style>

