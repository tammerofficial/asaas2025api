<template>
    <div class="tenant-form-page">
        <div class="page-header">
            <h2>➕ Add New Tenant</h2>
            <router-link to="/tenants" class="btn-secondary">← Back to Tenants</router-link>
        </div>

        <form @submit.prevent="submitForm" class="form-container">
            <FormInput v-model="form.name" label="Tenant Name" required />
            <FormInput v-model="form.domain" label="Domain" required />
            <FormInput v-model="form.user_id" type="number" label="User ID" />
            <FormInput v-model="form.expire_date" type="date" label="Expire Date" />
            <div class="form-actions">
                <button type="submit" class="btn-primary" :disabled="loading">Create Tenant</button>
                <router-link to="/tenants" class="btn-secondary">Cancel</router-link>
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
    name: 'TenantCreate',
    components: { FormInput, Toast },
    setup() {
        const router = useRouter()
        const loading = ref(false)
        const form = ref({ name: '', domain: '', user_id: null, expire_date: '', data: {} })
        const showToast = ref(false)
        const toastType = ref('success')
        const toastTitle = ref('')
        const toastMessage = ref('')

        const submitForm = async () => {
            loading.value = true
            try {
                await api.tenants.create(form.value)
                showToastMessage('success', 'Success', 'Tenant created successfully')
                setTimeout(() => router.push('/tenants'), 1500)
            } catch (error) {
                showToastMessage('error', 'Error', error.response?.data?.message || 'Failed to create tenant')
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
.tenant-form-page {
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

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}
</style>



