<template>
    <div class="package-form-page">
        <div class="page-header">
            <h2>➕ Add New Package</h2>
            <router-link to="/packages" class="btn-secondary">← Back to Packages</router-link>
        </div>

        <form @submit.prevent="submitForm" class="form-container">
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
            <div class="form-group">
                <label>Features (JSON array)</label>
                <textarea v-model="featuresText" class="form-textarea" rows="5"></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary" :disabled="loading">Create Package</button>
                <router-link to="/packages" class="btn-secondary">Cancel</router-link>
            </div>
        </form>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import FormInput from '../../components/FormInput.vue'
import Toast from '../../components/Toast.vue'

export default {
    name: 'PackageCreate',
    components: { FormInput, Toast },
    setup() {
        const router = useRouter()
        const loading = ref(false)
        const form = ref({ title: '', price: '', type: 0, status: 1, features: [] })
        const featuresText = ref('[]')
        const showToast = ref(false)
        const toastType = ref('success')
        const toastTitle = ref('')
        const toastMessage = ref('')

        const submitForm = async () => {
            loading.value = true
            try {
                form.value.features = JSON.parse(featuresText.value)
                await api.plans.create(form.value)
                showToastMessage('success', 'Success', 'Package created successfully')
                setTimeout(() => router.push('/packages'), 1500)
            } catch (error) {
                showToastMessage('error', 'Error', error.response?.data?.message || 'Failed to create package')
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

        return { form, featuresText, loading, showToast, toastType, toastTitle, toastMessage, submitForm }
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



