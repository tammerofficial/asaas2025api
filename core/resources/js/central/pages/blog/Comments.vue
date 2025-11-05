<template>
    <div class="comments-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">💬 Blog Comments</h1>
                <p class="page-subtitle">Manage blog comments</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search comments..."
                    class="search-input"
                    @input="debounceSearch"
                />
            </div>
            <select v-model="statusFilter" class="filter-select" @change="loadComments(1)">
                <option value="">All Status</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
                <option value="spam">Spam</option>
                <option value="trash">Trash</option>
            </select>
        </div>

        <!-- Comments Table -->
        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading comments...</p>
            </div>
            
            <table v-else class="comments-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Author</th>
                        <th>Comment</th>
                        <th>Post</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="comments.length === 0">
                        <td colspan="7" class="empty-cell">
                            No comments found
                        </td>
                    </tr>
                    <tr v-else v-for="comment in comments" :key="comment.id">
                        <td>{{ comment.id }}</td>
                        <td>
                            <div class="author-info">
                                <strong>{{ comment.author_name || comment.name }}</strong>
                                <small>{{ comment.author_email || comment.email }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="comment-text">
                                {{ comment.comment || comment.content }}
                            </div>
                        </td>
                        <td>
                            <router-link 
                                v-if="comment.post_id" 
                                :to="`/blog/${comment.post_id}`"
                                class="post-link"
                            >
                                Post #{{ comment.post_id }}
                            </router-link>
                            <span v-else>-</span>
                        </td>
                        <td>
                            <StatusBadge :status="comment.status || 'pending'" />
                        </td>
                        <td>{{ formatDate(comment.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    v-if="comment.status !== 'approved'"
                                    class="btn-icon btn-success" 
                                    @click="approveComment(comment)"
                                    title="Approve"
                                >
                                    ✓
                                </button>
                                <button 
                                    class="btn-icon" 
                                    @click="editComment(comment)"
                                    title="Edit"
                                >
                                    ✏️
                                </button>
                                <button 
                                    class="btn-icon btn-danger" 
                                    @click="deleteComment(comment)"
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
        <div v-if="!loading && comments.length > 0 && pagination.last_page > 1" class="pagination">
            <button 
                class="pagination-btn"
                :disabled="pagination.current_page === 1"
                @click="loadComments(pagination.current_page - 1)"
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
                @click="loadComments(pagination.current_page + 1)"
            >
                Next →
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

const comments = ref([])
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const pagination = ref({ 
    current_page: 1, 
    last_page: 1, 
    per_page: 20, 
    total: 0 
})

let searchTimeout = null

const loadComments = async (page = 1) => {
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
        
        const response = await api.blog.comments(params)
        
        if (response.data.success) {
            comments.value = response.data.data || []
            if (response.data.meta) {
                pagination.value = response.data.meta
            }
        }
    } catch (error) {
        console.error('Error loading comments:', error)
        comments.value = []
    } finally {
        loading.value = false
    }
}

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        loadComments(1)
    }, 500)
}

const approveComment = async (comment) => {
    try {
        await api.blog.update(`comments/${comment.id}`, { status: 'approved' })
        await loadComments(pagination.value.current_page)
    } catch (error) {
        console.error('Error approving comment:', error)
        alert('Failed to approve comment')
    }
}

const editComment = (comment) => {
    // Open edit modal or navigate to edit page
    alert(`Edit comment: ${comment.id}`)
}

const deleteComment = async (comment) => {
    if (!confirm(`Are you sure you want to delete this comment?`)) {
        return
    }
    
    try {
        await api.blog.delete(`comments/${comment.id}`)
        await loadComments(pagination.value.current_page)
    } catch (error) {
        console.error('Error deleting comment:', error)
        alert('Failed to delete comment')
    }
}

const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

onMounted(() => {
    loadComments()
})
</script>

<style scoped>
.comments-page {
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

.comments-table {
    width: 100%;
    border-collapse: collapse;
}

.comments-table thead {
    background: #f8fafc;
}

.comments-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.comments-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.author-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.author-info strong {
    color: #1e293b;
    font-size: 14px;
}

.author-info small {
    color: #64748b;
    font-size: 12px;
}

.comment-text {
    color: #475569;
    font-size: 14px;
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.post-link {
    color: #3b82f6;
    text-decoration: none;
    font-weight: 500;
}

.post-link:hover {
    text-decoration: underline;
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

.btn-icon.btn-success:hover {
    background: #d1fae5;
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
