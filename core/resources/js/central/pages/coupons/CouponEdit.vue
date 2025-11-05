<template>
    <div class="coupon-edit-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">✏️ Edit Coupon</h1>
                <p class="page-subtitle">Edit discount coupon</p>
            </div>
            <router-link to="/coupons" class="btn btn-secondary">← Back to Coupons</router-link>
        </div>

        <div class="form-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading coupon...</p>
            </div>

            <form v-else @submit.prevent="handleSubmit">
                <div class="form-grid">
                    <div class="form-main">
                        <div class="form-group">
                            <label for="code" class="form-label">Coupon Code <span class="required">*</span></label>
                            <input type="text" id="code" v-model="form.code" class="form-input" :class="{ 'error': errors.code }" required />
                            <span v-if="errors.code" class="error-message">{{ errors.code }}</span>
                            <small class="form-help">Unique coupon code that customers will use</small>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" v-model="form.description" class="form-textarea" rows="4"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="discount_type" class="form-label">Discount Type <span class="required">*</span></label>
                                <select id="discount_type" v-model="form.discount_type" class="form-select" :class="{ 'error': errors.discount_type }" required>
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount (KWD)</option>
                                </select>
                                <span v-if="errors.discount_type" class="error-message">{{ errors.discount_type }}</span>
                            </div>

                            <div class="form-group">
                                <label for="discount" class="form-label">Discount Amount <span class="required">*</span></label>
                                <input type="number" id="discount" v-model.number="form.discount" class="form-input" :class="{ 'error': errors.discount }" :step="form.discount_type === 'percentage' ? '1' : '0.001'" :min="form.discount_type === 'percentage' ? '1' : '0.001'" :max="form.discount_type === 'percentage' ? '100' : '999999'" required />
                                <span v-if="errors.discount" class="error-message">{{ errors.discount }}</span>
                                <small class="form-help">{{ form.discount_type === 'percentage' ? 'Percentage (1-100)' : 'Amount in KWD' }}</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="usage_limit" class="form-label">Usage Limit</label>
                                <input type="number" id="usage_limit" v-model.number="form.usage_limit" class="form-input" min="0" placeholder="0 = Unlimited" />
                                <small class="form-help">Maximum number of times this coupon can be used (0 = unlimited)</small>
                            </div>

                            <div class="form-group">
                                <label for="usage_limit_per_user" class="form-label">Per User Limit</label>
                                <input type="number" id="usage_limit_per_user" v-model.number="form.usage_limit_per_user" class="form-input" min="0" placeholder="1" />
                                <small class="form-help">Maximum times a single user can use this coupon</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="minimum_amount" class="form-label">Minimum Order Amount</label>
                            <input type="number" id="minimum_amount" v-model.number="form.minimum_amount" class="form-input" step="0.001" min="0" placeholder="0.000" />
                            <small class="form-help">Minimum order amount required to use this coupon (0 = no minimum)</small>
                        </div>
                    </div>

                    <div class="form-sidebar">
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" v-model="form.status" class="form-select">
                                <option :value="true">Active</option>
                                <option :value="false">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="datetime-local" id="start_date" v-model="form.start_date" class="form-input" />
                            <small class="form-help">When the coupon becomes valid</small>
                        </div>

                        <div class="form-group">
                            <label for="expire_date" class="form-label">Expire Date</label>
                            <input type="datetime-local" id="expire_date" v-model="form.expire_date" class="form-input" :class="{ 'error': errors.expire_date }" />
                            <span v-if="errors.expire_date" class="error-message">{{ errors.expire_date }}</span>
                            <small class="form-help">When the coupon expires</small>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="saving">
                        <span v-if="saving">⏳ Updating...</span>
                        <span v-else>💾 Update Coupon</span>
                    </button>
                    <router-link to="/coupons" class="btn btn-secondary">Cancel</router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const route = useRoute()

const loading = ref(true)
const saving = ref(false)
const form = ref({
    code: '',
    description: '',
    discount_type: 'percentage',
    discount: 0,
    usage_limit: 0,
    usage_limit_per_user: 1,
    minimum_amount: 0,
    status: true,
    start_date: '',
    expire_date: ''
})

const errors = ref({})

const loadCoupon = async () => {
    loading.value = true
    try {
        const response = await axios.get(`/coupons/${route.params.id}`)
        if (response.data.success) {
            const coupon = response.data.data
            form.value = {
                code: coupon.code || '',
                description: coupon.description || '',
                discount_type: coupon.discount_type || 'percentage',
                discount: coupon.discount || 0,
                usage_limit: coupon.usage_limit ?? 0,
                usage_limit_per_user: coupon.usage_limit_per_user ?? 1,
                minimum_amount: coupon.minimum_amount ?? 0,
                status: coupon.status ?? true,
                start_date: coupon.start_date ? new Date(coupon.start_date).toISOString().slice(0, 16) : '',
                expire_date: coupon.expire_date ? new Date(coupon.expire_date).toISOString().slice(0, 16) : ''
            }
        }
    } catch (error) {
        console.error('Error loading coupon:', error)
        alert('Failed to load coupon')
        router.push('/coupons')
    } finally {
        loading.value = false
    }
}

const handleSubmit = async () => {
    errors.value = {}
    saving.value = true

    try {
        const response = await axios.put(`/coupons/${route.params.id}`, form.value)

        if (response.data.success) {
            router.push('/coupons')
        } else {
            if (response.data.errors) {
                errors.value = response.data.errors
            } else {
                alert('Failed to update coupon: ' + (response.data.message || 'Unknown error'))
            }
        }
    } catch (error) {
        console.error('Error updating coupon:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            alert('Failed to update coupon: ' + (error.response?.data?.message || 'Unknown error'))
        }
    } finally {
        saving.value = false
    }
}

onMounted(() => loadCoupon())
</script>

<style scoped>
.coupon-edit-page { padding: 24px; max-width: 1400px; }
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
.page-title { font-size: 28px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0; }
.page-subtitle { color: #64748b; font-size: 15px; margin: 0; }
.form-container { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); position: relative; min-height: 200px; }
.loading-overlay { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px; color: #64748b; }
.spinner { width: 40px; height: 40px; border: 4px solid #f1f5f9; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 16px; }
@keyframes spin { to { transform: rotate(360deg); } }
.form-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
.form-main, .form-sidebar { display: flex; flex-direction: column; gap: 20px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-label { font-weight: 600; color: #374151; font-size: 14px; }
.required { color: #ef4444; }
.form-input, .form-textarea, .form-select { width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 15px; transition: border-color 0.2s; font-family: inherit; }
.form-input:focus, .form-textarea:focus, .form-select:focus { outline: none; border-color: #3b82f6; }
.form-input.error, .form-textarea.error, .form-select.error { border-color: #ef4444; }
.form-textarea { resize: vertical; min-height: 100px; }
.form-help { color: #64748b; font-size: 13px; }
.error-message { color: #ef4444; font-size: 13px; }
.form-actions { display: flex; gap: 12px; margin-top: 30px; padding-top: 30px; border-top: 1px solid #e5e7eb; }
.btn { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.2s; font-size: 15px; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover:not(:disabled) { background: #2563eb; }
.btn-primary:disabled { background: #9ca3af; cursor: not-allowed; }
.btn-secondary { background: #e5e7eb; color: #374151; }
.btn-secondary:hover { background: #d1d5db; }
@media (max-width: 1024px) { .form-grid, .form-row { grid-template-columns: 1fr; } }
</style>

