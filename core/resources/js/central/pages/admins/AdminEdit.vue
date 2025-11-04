<template>
    <div class="admin-form-page">
        <div class="page-header">
            <h2>✏️ Edit Admin</h2>
            <router-link to="/admins" class="btn-secondary">← Back to Admins</router-link>
        </div>

        <div v-if="loading" class="loading">Loading...</div>
        <form v-else @submit.prevent="submitForm" class="form-container">
            <FormInput v-model="form.name" label="Name" required />
            <FormInput v-model="form.email" type="email" label="Email" required />
            <FormInput v-model="form.username" label="Username" required />
            <FormInput v-model="form.password" type="password" label="Password (leave empty to keep current)" />
            <div class="form-group">
                <label>Status</label>
                <select v-model="form.status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary" :disabled="loading">Update Admin</button>
                <router-link to="/admins" class="btn-secondary">Cancel</router-link>
            </div>
        </form>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '../../services/api'
import FormInput from '../../components/FormInput.vue'
import Toast from '../../components/Toast.vue'

export default {
    name: 'AdminEdit',
    components: { FormInput, Toast },
    setup() {
        const router = useRouter()
        const route = useRoute()
        const loading = ref(false)
        const form = ref({ name: '', email: '', username: '', password: '', status: 1 })
        const showToast = ref(false)
        const toastType = ref('success')
        const toastTitle = ref('')
        const toastMessage = ref('')

        const fetchAdmin = async () => {
            loading.value = true
            try {
                const response = await api.admins.get(route.params.id)
                if (response.data.success) {
                    form.value = { ...response.data.data, password: '' }
                }
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to load admin')
            } finally {
                loading.value = false
            }
        }

        const submitForm = async () => {
            loading.value = true
            try {
                const data = { ...form.value }
                if (!data.password) delete data.password
                await api.admins.update(route.params.id, data)
                showToastMessage('success', 'Success', 'Admin updated successfully')
                setTimeout(() => router.push('/admins'), 1500)
            } catch (error) {
                showToastMessage('error', 'Error', error.response?.data?.message || 'Failed to update admin')
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

        onMounted(() => {
            fetchAdmin()
        })

        return { form, loading, showToast, toastType, toastTitle, toastMessage, submitForm }
    }
}
</script>

<style scoped>
.admin-form-page {
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

.form-select {
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



