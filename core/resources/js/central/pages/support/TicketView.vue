<template>
    <div class="ticket-view-page">
        <div class="page-header">
            <h2>🎫 Ticket #{{ ticket?.id }}</h2>
            <router-link to="/support" class="btn-secondary">← Back to Tickets</router-link>
        </div>

        <div v-if="loading" class="loading">Loading ticket...</div>
        <div v-else-if="ticket" class="ticket-details">
            <div class="detail-section">
                <h3>{{ ticket.title }}</h3>
                <div class="ticket-meta">
                    <StatusBadge :status="ticket.status || 'pending'" />
                    <span class="meta-item">Priority: {{ ticket.priority || 'medium' }}</span>
                    <span class="meta-item">Created: {{ formatDate(ticket.created_at) }}</span>
                </div>
                <div class="ticket-description">
                    <p>{{ ticket.description || 'No description' }}</p>
                </div>
            </div>

            <div class="detail-section">
                <h3>Messages</h3>
                <div v-if="messages.length === 0" class="empty">No messages yet</div>
                <div v-else class="messages-list">
                    <div v-for="msg in messages" :key="msg.id" class="message-item">
                        <p class="message-text">{{ msg.message }}</p>
                        <span class="message-date">{{ formatDate(msg.created_at) }}</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h3>Add Reply</h3>
                <div class="reply-form">
                    <textarea v-model="newMessage" class="form-textarea" rows="4" placeholder="Type your reply..."></textarea>
                    <button @click="addMessage" class="btn-primary" :disabled="!newMessage || loading">Send Reply</button>
                </div>
            </div>
        </div>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'
import Toast from '../../components/Toast.vue'

export default {
    name: 'TicketView',
    components: { StatusBadge, Toast },
    setup() {
        const route = useRoute()
        const loading = ref(false)
        const ticket = ref(null)
        const messages = ref([])
        const newMessage = ref('')
        const showToast = ref(false)
        const toastType = ref('success')
        const toastTitle = ref('')
        const toastMessage = ref('')

        const fetchTicket = async () => {
            loading.value = true
            try {
                const response = await api.supportTickets.get(route.params.id)
                if (response.data.success) {
                    ticket.value = response.data.data
                    messages.value = response.data.data.messages || []
                }
            } catch (error) {
                console.error('Error fetching ticket:', error)
                showToastMessage('error', 'Error', 'Failed to load ticket')
            } finally {
                loading.value = false
            }
        }

        const addMessage = async () => {
            if (!newMessage.value.trim()) return
            loading.value = true
            try {
                await api.supportTickets.addMessage(route.params.id, newMessage.value)
                showToastMessage('success', 'Success', 'Message added successfully')
                newMessage.value = ''
                fetchTicket()
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to add message')
            } finally {
                loading.value = false
            }
        }

        const formatDate = (date) => {
            if (!date) return '-'
            return new Date(date).toLocaleString()
        }

        const showToastMessage = (type, title, message) => {
            toastType.value = type
            toastTitle.value = title
            toastMessage.value = message
            showToast.value = true
        }

        onMounted(() => {
            fetchTicket()
        })

        return { loading, ticket, messages, newMessage, showToast, toastType, toastTitle, toastMessage, addMessage, formatDate }
    }
}
</script>

<style scoped>
.ticket-view-page {
    padding: 20px;
    max-width: 900px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.ticket-details {
    background: white;
    border-radius: 8px;
    padding: 24px;
}

.detail-section {
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid #e5e7eb;
}

.detail-section:last-child {
    border-bottom: none;
}

.detail-section h3 {
    margin: 0 0 16px 0;
    font-size: 18px;
    color: #1e293b;
}

.ticket-meta {
    display: flex;
    gap: 16px;
    align-items: center;
    margin-bottom: 16px;
}

.meta-item {
    font-size: 14px;
    color: #64748b;
}

.ticket-description {
    margin-top: 16px;
}

.ticket-description p {
    color: #374151;
    line-height: 1.6;
}

.messages-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.message-item {
    padding: 16px;
    background: #f9fafb;
    border-radius: 8px;
}

.message-text {
    margin: 0 0 8px 0;
    color: #374151;
}

.message-date {
    font-size: 12px;
    color: #9ca3af;
}

.reply-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.form-textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
}

.empty {
    padding: 40px;
    text-align: center;
    color: #9ca3af;
}
</style>



