<template>
    <div class="tenant-form-page">
        <div class="page-header">
            <h2>✏️ Edit Tenant</h2>
            <router-link to="/tenants" class="btn-secondary">← Back to Tenants</router-link>
        </div>

        <div v-if="loading" class="loading">Loading...</div>
        <form v-else @submit.prevent="submitForm" class="form-container">
            <FormInput v-model="form.name" label="Tenant Name" required />
            <FormInput v-model="form.domain" label="Domain" required />
            <FormInput v-model="form.user_id" type="number" label="User ID" />
            <FormInput v-model="form.expire_date" type="date" label="Expire Date" />
            <div class="form-actions">
                <button type="submit" class="btn-primary" :disabled="loading">Update Tenant</button>
                <router-link to="/tenants" class="btn-secondary">Cancel</router-link>
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
    name: 'TenantEdit',
    components: { FormInput, Toast },
    setup() {
        const router = useRouter()
        const route = useRoute()
        const loading = ref(false)
        const form = ref({ name: '', domain: '', user_id: null, expire_date: '', data: {} })
        const showToast = ref(false)
        const toastType = ref('success')
        const toastTitle = ref('')
        const toastMessage = ref('')

        const fetchTenant = async () => {
            loading.value = true
            try {
                const response = await api.tenants.get(route.params.id)
                if (response.data.success) {
                    form.value = response.data.data
                    if (form.value.expire_date) {
                        form.value.expire_date = form.value.expire_date.split(' ')[0]
                    }
                }
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to load tenant')
            } finally {
                loading.value = false
            }
        }

        const submitForm = async () => {
            loading.value = true
            try {
                await api.tenants.update(route.params.id, form.value)
                showToastMessage('success', 'Success', 'Tenant updated successfully')
                setTimeout(() => router.push('/tenants'), 1500)
            } catch (error) {
                showToastMessage('error', 'Error', error.response?.data?.message || 'Failed to update tenant')
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
            fetchTenant()
        })

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



