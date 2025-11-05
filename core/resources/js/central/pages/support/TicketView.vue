<template>
    <div class="ticket-view-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🎫 Ticket #{{ ticket?.id || '...' }}</h1>
                <p class="page-subtitle">{{ ticket?.title || 'Loading...' }}</p>
            </div>
            <router-link to="/support" class="btn btn-secondary">
                ← Back to Tickets
            </router-link>
        </div>

        <div v-if="loading" class="loading-overlay">
            <div class="spinner"></div>
            <p>Loading ticket...</p>
        </div>

        <div v-else-if="ticket" class="ticket-container">
            <!-- Ticket Info -->
            <div class="ticket-card">
                <div class="ticket-header">
                    <div class="ticket-meta">
                        <StatusBadge :status="ticket.status || 'open'" />
                        <span class="priority-badge" :class="ticket.priority || 'medium'">
                            {{ (ticket.priority || 'medium').toUpperCase() }}
                        </span>
                        <span class="meta-item">Created: {{ formatDate(ticket.created_at) }}</span>
                    </div>
                </div>
                <div class="ticket-description">
                    <h3>Description</h3>
                    <p>{{ ticket.description || ticket.content || 'No description provided' }}</p>
                </div>
            </div>

            <!-- Messages -->
            <div class="messages-card">
                <h3 class="section-title">💬 Messages</h3>
                <div v-if="messages.length === 0" class="empty-state">
                    No messages yet
                </div>
                <div v-else class="messages-list">
                    <div v-for="msg in messages" :key="msg.id" class="message-item">
                        <div class="message-header">
                            <strong>{{ msg.user_name || msg.author || 'Admin' }}</strong>
                            <span class="message-date">{{ formatDate(msg.created_at) }}</span>
                        </div>
                        <div class="message-text">{{ msg.message || msg.content }}</div>
                    </div>
                </div>
            </div>

            <!-- Add Reply -->
            <div class="reply-card">
                <h3 class="section-title">✏️ Add Reply</h3>
                <div class="reply-form">
                    <textarea 
                        v-model="newMessage" 
                        class="form-textarea" 
                        rows="5" 
                        placeholder="Type your reply here..."
                    ></textarea>
                    <div class="form-actions">
                        <button 
                            @click="addMessage" 
                            class="btn btn-primary" 
                            :disabled="!newMessage.trim() || loading"
                        >
                            <span v-if="loading">⏳ Sending...</span>
                            <span v-else>📤 Send Reply</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

const route = useRoute()
const loading = ref(false)
const ticket = ref(null)
const messages = ref([])
const newMessage = ref('')

const fetchTicket = async () => {
    loading.value = true
    try {
        const response = await api.support.ticket(route.params.id)
        if (response.data.success) {
            ticket.value = response.data.data
            messages.value = response.data.data.messages || response.data.data.replies || []
        }
    } catch (error) {
        console.error('Error fetching ticket:', error)
        alert('Failed to load ticket')
    } finally {
        loading.value = false
    }
}

const addMessage = async () => {
    if (!newMessage.value.trim()) return
    
    loading.value = true
    try {
        await api.support.addMessage(route.params.id, newMessage.value)
        newMessage.value = ''
        await fetchTicket()
    } catch (error) {
        console.error('Error adding message:', error)
        alert('Failed to add message')
    } finally {
        loading.value = false
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
    fetchTicket()
})
</script>

<style scoped>
.ticket-view-page {
    padding: 24px;
    max-width: 1000px;
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

/* Loading */
.loading-overlay {
    background: white;
    border-radius: 12px;
    padding: 60px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e5e7eb;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 16px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Ticket Container */
.ticket-container {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.ticket-card,
.messages-card,
.reply-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.ticket-header {
    margin-bottom: 20px;
}

.ticket-meta {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
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

.meta-item {
    color: #64748b;
    font-size: 14px;
}

.ticket-description h3 {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 12px 0;
}

.ticket-description p {
    color: #475569;
    line-height: 1.6;
    margin: 0;
}

/* Messages */
.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 20px 0;
}

.empty-state {
    padding: 40px;
    text-align: center;
    color: #94a3b8;
}

.messages-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.message-item {
    padding: 16px;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 3px solid #3b82f6;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.message-header strong {
    color: #1e293b;
    font-size: 14px;
}

.message-date {
    font-size: 12px;
    color: #94a3b8;
}

.message-text {
    color: #475569;
    line-height: 1.6;
    margin: 0;
}

/* Reply Form */
.reply-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.form-textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    font-family: inherit;
    resize: vertical;
    min-height: 120px;
    transition: border-color 0.2s;
}

.form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
}
</style>
