<template>
    <div class="ticket-form-page">
        <div class="page-header">
            <h2>➕ Create Ticket</h2>
            <router-link to="/support" class="btn-secondary">← Back to Tickets</router-link>
        </div>

        <form @submit.prevent="submitForm" class="form-container">
            <FormInput v-model="form.title" label="Title" required />
            <div class="form-group">
                <label>Priority</label>
                <select v-model="form.priority" class="form-select">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea v-model="form.description" class="form-textarea" rows="5" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary" :disabled="loading">Create Ticket</button>
                <router-link to="/support" class="btn-secondary">Cancel</router-link>
            </div>
        </form>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import FormInput from '../../components/FormInput.vue'
import Toast from '../../components/Toast.vue'

export default {
    name: 'TicketCreate',
    components: { FormInput, Toast },
    setup() {
        const router = useRouter()
        const loading = ref(false)
        const form = ref({ title: '', description: '', priority: 'medium' })
        const showToast = ref(false)
        const toastType = ref('success')
        const toastTitle = ref('')
        const toastMessage = ref('')

        const submitForm = async () => {
            loading.value = true
            try {
                // Note: Support ticket creation may need to be handled differently
                // For now, using update endpoint as placeholder
                showToastMessage('info', 'Info', 'Ticket creation endpoint needed in API')
                setTimeout(() => router.push('/support'), 1500)
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to create ticket')
            } finally {
                loading.value = false
            }
        }

        const showToastMessage = (type, title, message) => {
            toastType.value = type
            toastTitle.value = title
            toastMessage.value = message
            showToast.value = true
        }

        return { form, loading, showToast, toastType, toastTitle, toastMessage, submitForm }
    }
}
</script>

<style scoped>
.ticket-form-page {
    padding: 20px;
    max-width: 800px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.form-container {
    background: white;
    padding: 24px;
    border-radius: 8px;
}

.form-group {
    margin-bottom: 20px;
}

.form-select,
.form-textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}
</style>



