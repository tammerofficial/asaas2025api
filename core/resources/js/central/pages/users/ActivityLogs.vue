<template>
    <div class="activity-logs-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">📋 Activity Logs</h1>
                <p class="page-subtitle">View all system activity logs</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" @click="loadLogs">
                    🔄 Refresh
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search logs..."
                    class="search-input"
                    @input="debounceSearch"
                />
            </div>
            <select v-model="logNameFilter" class="filter-select" @change="loadLogs(1)">
                <option value="">All Types</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
                <option value="tenant">Tenant</option>
                <option value="package">Package</option>
            </select>
            <select v-model="eventFilter" class="filter-select" @change="loadLogs(1)">
                <option value="">All Events</option>
                <option value="created">Created</option>
                <option value="updated">Updated</option>
                <option value="deleted">Deleted</option>
            </select>
        </div>

        <!-- Logs Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading activity logs...</p>
            </div>
            
            <table v-else class="logs-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Log Name</th>
                        <th>Description</th>
                        <th>Subject</th>
                        <th>Causer</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="logs.length === 0">
                        <td colspan="8" class="empty-cell">
                            No activity logs found
                        </td>
                    </tr>
                    <tr v-else v-for="log in logs" :key="log.id">
                        <td>{{ log.id }}</td>
                        <td>
                            <span class="log-name-badge">{{ log.log_name || 'N/A' }}</span>
                        </td>
                        <td>
                            <div class="log-description">
                                {{ log.description || '-' }}
                            </div>
                        </td>
                        <td>
                            <div class="log-subject">
                                {{ getSubjectType(log.subject_type) }} #{{ log.subject_id || '-' }}
                            </div>
                        </td>
                        <td>
                            <div class="log-causer">
                                {{ log.causer?.name || log.causer_type || '-' }}
                            </div>
                        </td>
                        <td>
                            <StatusBadge :status="getEventStatus(log.event)" />
                        </td>
                        <td>{{ formatDate(log.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="viewLog(log)"
                                    title="View Details"
                                >
                                    👁️
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="!loading && logs.length > 0 && pagination.last_page > 1" class="pagination">
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page === 1"
                @click="loadLogs(pagination.current_page - 1)"
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
                @click="loadLogs(pagination.current_page + 1)"
            >
                Next →
            </button>
        </div>

        <!-- Log Details Modal -->
        <div v-if="selectedLog" class="modal-overlay" @click="closeModal">
            <div class="modal-content" @click.stop>
                <div class="modal-header">
                    <h3>📋 Log Details</h3>
                    <button class="modal-close" @click="closeModal">×</button>
                </div>
                <div class="modal-body">
                    <div class="detail-item">
                        <strong>ID:</strong> {{ selectedLog.id }}
                    </div>
                    <div class="detail-item">
                        <strong>Log Name:</strong> {{ selectedLog.log_name || 'N/A' }}
                    </div>
                    <div class="detail-item">
                        <strong>Description:</strong> {{ selectedLog.description || '-' }}
                    </div>
                    <div class="detail-item">
                        <strong>Subject:</strong> {{ getSubjectType(selectedLog.subject_type) }} #{{ selectedLog.subject_id || '-' }}
                    </div>
                    <div class="detail-item">
                        <strong>Causer:</strong> {{ selectedLog.causer?.name || selectedLog.causer_type || '-' }}
                    </div>
                    <div class="detail-item">
                        <strong>Event:</strong> {{ selectedLog.event || '-' }}
                    </div>
                    <div class="detail-item">
                        <strong>Properties:</strong>
                        <pre class="properties-json">{{ JSON.stringify(selectedLog.properties || {}, null, 2) }}</pre>
                    </div>
                    <div class="detail-item">
                        <strong>Created At:</strong> {{ formatDateTime(selectedLog.created_at) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="closeModal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

const logs = ref([])
const loading = ref(false)
const searchQuery = ref('')
const logNameFilter = ref('')
const eventFilter = ref('')
const selectedLog = ref(null)
const pagination = ref({ 
    current_page: 1, 
    last_page: 1, 
    per_page: 20, 
    total: 0 
})

let searchTimeout = null

const loadLogs = async (page = 1) => {
    loading.value = true
    try {
        const params = {
            page,
            per_page: pagination.value.per_page
        }
        
        if (searchQuery.value) {
            params.search = searchQuery.value
        }
        
        if (logNameFilter.value) {
            params.log_name = logNameFilter.value
        }
        
        if (eventFilter.value) {
            params.event = eventFilter.value
        }
        
        const response = await api.users.activityLogs(params)
        
        if (response.data.success) {
            logs.value = response.data.data || []
            if (response.data.meta) {
                pagination.value = response.data.meta
            }
        }
    } catch (error) {
        console.error('Error loading activity logs:', error)
        logs.value = []
    } finally {
        loading.value = false
    }
}

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        loadLogs(1)
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

const formatDateTime = (dateString) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const getSubjectType = (type) => {
    if (!type) return 'N/A'
    const parts = type.split('\\')
    return parts[parts.length - 1]
}

const getEventStatus = (event) => {
    if (!event) return 'pending'
    const eventMap = {
        'created': 'success',
        'updated': 'pending',
        'deleted': 'failed'
    }
    return eventMap[event.toLowerCase()] || 'pending'
}

const viewLog = (log) => {
    selectedLog.value = log
}

const closeModal = () => {
    selectedLog.value = null
}

onMounted(() => {
    loadLogs()
})
</script>

<style scoped>
.activity-logs-page {
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

.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #d1d5db;
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

.logs-table {
    width: 100%;
    border-collapse: collapse;
}

.logs-table thead {
    background: #f8fafc;
}

.logs-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.logs-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
    font-size: 14px;
}

.log-name-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.log-description {
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.log-subject,
.log-causer {
    font-size: 13px;
    color: #64748b;
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

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 700px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    margin: 0;
    font-size: 20px;
    color: #1e293b;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    color: #64748b;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: background 0.2s;
}

.modal-close:hover {
    background: #f1f5f9;
}

.modal-body {
    padding: 24px;
}

.detail-item {
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item strong {
    display: block;
    margin-bottom: 6px;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.properties-json {
    background: #f8fafc;
    padding: 12px;
    border-radius: 6px;
    font-size: 12px;
    overflow-x: auto;
    margin-top: 8px;
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
}
</style>
