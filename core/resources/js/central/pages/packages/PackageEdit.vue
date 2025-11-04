<template>
    <div class="package-form-page">
        <div class="page-header">
            <h2>✏️ Edit Package</h2>
            <router-link to="/packages" class="btn-secondary">← Back to Packages</router-link>
        </div>

        <div v-if="loading" class="loading">Loading...</div>
        <form v-else @submit.prevent="submitForm" class="form-container">
            <FormInput v-model="form.title" label="Title" required />
            <FormInput v-model="form.price" type="number" label="Price" required />
            <div class="form-group">
                <label>Type</label>
                <select v-model="form.type" class="form-select">
                    <option value="0">Monthly</option>
                    <option value="1">Yearly</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select v-model="form.status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary" :disabled="loading">Update Package</button>
                <router-link to="/packages" class="btn-secondary">Cancel</router-link>
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
    name: 'PackageEdit',
    components: { FormInput, Toast },
    setup() {
        const router = useRouter()
        const route = useRoute()
        const loading = ref(false)
        const form = ref({ title: '', price: '', type: 0, status: 1 })
        const showToast = ref(false)
        const toastType = ref('success')
        const toastTitle = ref('')
        const toastMessage = ref('')

        const fetchPackage = async () => {
            loading.value = true
            try {
                const response = await api.plans.get(route.params.id)
                if (response.data.success) {
                    form.value = response.data.data
                }
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to load package')
            } finally {
                loading.value = false
            }
        }

        const submitForm = async () => {
            loading.value = true
            try {
                await api.plans.update(route.params.id, form.value)
                showToastMessage('success', 'Success', 'Package updated successfully')
                setTimeout(() => router.push('/packages'), 1500)
            } catch (error) {
                showToastMessage('error', 'Error', error.response?.data?.message || 'Failed to update package')
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
            fetchPackage()
        })

        return { form, loading, showToast, toastType, toastTitle, toastMessage, submitForm }
    }
}
</script>

<style scoped>
.package-form-page {
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



