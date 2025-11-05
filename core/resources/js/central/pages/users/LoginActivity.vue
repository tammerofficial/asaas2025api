<template>
    <div class="login-activity-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🔐 Login Activity</h1>
                <p class="page-subtitle">View all login attempts and activities</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" @click="loadActivity">
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
                    placeholder="Search by user, email, IP..."
                    class="search-input"
                    @input="debounceSearch"
                />
            </div>
            <select v-model="statusFilter" class="filter-select" @change="loadActivity(1)">
                <option value="">All Status</option>
                <option value="success">Success</option>
                <option value="failed">Failed</option>
            </select>
            <select v-model="typeFilter" class="filter-select" @change="loadActivity(1)">
                <option value="">All Types</option>
                <option value="login">Login</option>
                <option value="logout">Logout</option>
                <option value="password_reset">Password Reset</option>
            </select>
        </div>

        <!-- Activity Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading login activity...</p>
            </div>
            
            <table v-else class="activity-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="activities.length === 0">
                        <td colspan="9" class="empty-cell">
                            No login activity found
                        </td>
                    </tr>
                    <tr v-else v-for="activity in activities" :key="activity.id">
                        <td>{{ activity.id }}</td>
                        <td>
                            <div class="user-name">
                                <strong>{{ activity.user?.name || activity.username || 'N/A' }}</strong>
                            </div>
                        </td>
                        <td>{{ activity.user?.email || activity.email || '-' }}</td>
                        <td>
                            <span class="type-badge">{{ activity.type || 'login' }}</span>
                        </td>
                        <td>
                            <StatusBadge :status="activity.status || 'success'" />
                        </td>
                        <td>
                            <span class="ip-address">{{ activity.ip_address || '-' }}</span>
                        </td>
                        <td>
                            <div class="user-agent">
                                {{ truncateText(activity.user_agent || '-', 50) }}
                            </div>
                        </td>
                        <td>{{ formatDateTime(activity.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="viewActivity(activity)"
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
        <div v-if="!loading && activities.length > 0 && pagination.last_page > 1" class="pagination">
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page === 1"
                @click="loadActivity(pagination.current_page - 1)"
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
                @click="loadActivity(pagination.current_page + 1)"
            >
                Next →
            </button>
        </div>

        <!-- Activity Details Modal -->
        <div v-if="selectedActivity" class="modal-overlay" @click="closeModal">
            <div class="modal-content" @click.stop>
                <div class="modal-header">
                    <h3>🔐 Activity Details</h3>
                    <button class="modal-close" @click="closeModal">×</button>
                </div>
                <div class="modal-body">
                    <div class="detail-item">
                        <strong>ID:</strong> {{ selectedActivity.id }}
                    </div>
                    <div class="detail-item">
                        <strong>User:</strong> {{ selectedActivity.user?.name || selectedActivity.username || 'N/A' }}
                    </div>
                    <div class="detail-item">
                        <strong>Email:</strong> {{ selectedActivity.user?.email || selectedActivity.email || '-' }}
                    </div>
                    <div class="detail-item">
                        <strong>Type:</strong> {{ selectedActivity.type || 'login' }}
                    </div>
                    <div class="detail-item">
                        <strong>Status:</strong> 
                        <StatusBadge :status="selectedActivity.status || 'success'" />
                    </div>
                    <div class="detail-item">
                        <strong>IP Address:</strong> {{ selectedActivity.ip_address || '-' }}
                    </div>
                    <div class="detail-item">
                        <strong>User Agent:</strong>
                        <div class="user-agent-full">{{ selectedActivity.user_agent || '-' }}</div>
                    </div>
                    <div class="detail-item">
                        <strong>Location:</strong> {{ selectedActivity.location || 'N/A' }}
                    </div>
                    <div class="detail-item">
                        <strong>Created At:</strong> {{ formatDateTime(selectedActivity.created_at) }}
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

const activities = ref([])
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const typeFilter = ref('')
const selectedActivity = ref(null)
const pagination = ref({ 
    current_page: 1, 
    last_page: 1, 
    per_page: 20, 
    total: 0 
})

let searchTimeout = null

const loadActivity = async (page = 1) => {
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
        
        if (typeFilter.value) {
            params.type = typeFilter.value
        }
        
        const response = await api.users.loginActivity(params)
        
        if (response.data.success) {
            activities.value = response.data.data || []
            if (response.data.meta) {
                pagination.value = response.data.meta
            }
        }
    } catch (error) {
        console.error('Error loading login activity:', error)
        activities.value = []
    } finally {
        loading.value = false
    }
}

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        loadActivity(1)
    }, 500)
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

const truncateText = (text, maxLength) => {
    if (!text) return '-'
    if (text.length <= maxLength) return text
    return text.substring(0, maxLength) + '...'
}

const viewActivity = (activity) => {
    selectedActivity.value = activity
}

const closeModal = () => {
    selectedActivity.value = null
}

onMounted(() => {
    loadActivity()
})
</script>

<style scoped>
.login-activity-page {
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

.activity-table {
    width: 100%;
    border-collapse: collapse;
}

.activity-table thead {
    background: #f8fafc;
}

.activity-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.activity-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
    font-size: 14px;
}

.user-name strong {
    color: #1e293b;
}

.type-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
}

.ip-address {
    font-family: monospace;
    font-size: 13px;
    color: #64748b;
}

.user-agent {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 12px;
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

.user-agent-full {
    background: #f8fafc;
    padding: 12px;
    border-radius: 6px;
    font-size: 12px;
    margin-top: 8px;
    word-break: break-all;
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
}
</style>
