<template>
    <div class="coupon-create-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">➕ Create New Coupon</h1>
                <p class="page-subtitle">Create a new discount coupon</p>
            </div>
            <router-link to="/coupons" class="btn btn-secondary">
                ← Back to Coupons
            </router-link>
        </div>

        <!-- Form -->
        <div class="form-container">
            <form @submit.prevent="handleSubmit">
                <div class="form-grid">
                    <div class="form-main">
                        <!-- Code -->
                        <div class="form-group">
                            <label for="code" class="form-label">
                                Coupon Code <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="code"
                                v-model="form.code"
                                class="form-input"
                                :class="{ 'error': errors.code }"
                                placeholder="COUPON2024"
                                required
                            />
                            <span v-if="errors.code" class="error-message">{{ errors.code }}</span>
                            <small class="form-help">Unique coupon code that customers will use</small>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                class="form-textarea"
                                rows="4"
                                placeholder="Coupon description"
                            ></textarea>
                        </div>

                        <!-- Discount Type & Amount -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="discount_type" class="form-label">
                                    Discount Type <span class="required">*</span>
                                </label>
                                <select
                                    id="discount_type"
                                    v-model="form.discount_type"
                                    class="form-select"
                                    :class="{ 'error': errors.discount_type }"
                                    required
                                >
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount (KWD)</option>
                                </select>
                                <span v-if="errors.discount_type" class="error-message">{{ errors.discount_type }}</span>
                            </div>

                            <div class="form-group">
                                <label for="discount" class="form-label">
                                    Discount Amount <span class="required">*</span>
                                </label>
                                <input
                                    type="number"
                                    id="discount"
                                    v-model="form.discount"
                                    class="form-input"
                                    :class="{ 'error': errors.discount }"
                                    :step="form.discount_type === 'percentage' ? '1' : '0.001'"
                                    :min="form.discount_type === 'percentage' ? '1' : '0.001'"
                                    :max="form.discount_type === 'percentage' ? '100' : '999999'"
                                    placeholder="10"
                                    required
                                />
                                <span v-if="errors.discount" class="error-message">{{ errors.discount }}</span>
                                <small class="form-help">
                                    {{ form.discount_type === 'percentage' ? 'Percentage (1-100)' : 'Amount in KWD' }}
                                </small>
                            </div>
                        </div>

                        <!-- Usage Limits -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="usage_limit" class="form-label">Usage Limit</label>
                                <input
                                    type="number"
                                    id="usage_limit"
                                    v-model="form.usage_limit"
                                    class="form-input"
                                    min="0"
                                    placeholder="0 = Unlimited"
                                />
                                <small class="form-help">Maximum number of times this coupon can be used (0 = unlimited)</small>
                            </div>

                            <div class="form-group">
                                <label for="usage_limit_per_user" class="form-label">Per User Limit</label>
                                <input
                                    type="number"
                                    id="usage_limit_per_user"
                                    v-model="form.usage_limit_per_user"
                                    class="form-input"
                                    min="0"
                                    placeholder="1"
                                />
                                <small class="form-help">Maximum times a single user can use this coupon</small>
                            </div>
                        </div>

                        <!-- Minimum Amount -->
                        <div class="form-group">
                            <label for="minimum_amount" class="form-label">Minimum Order Amount</label>
                            <input
                                type="number"
                                id="minimum_amount"
                                v-model="form.minimum_amount"
                                class="form-input"
                                step="0.001"
                                min="0"
                                placeholder="0.000"
                            />
                            <small class="form-help">Minimum order amount required to use this coupon (0 = no minimum)</small>
                        </div>
                    </div>

                    <div class="form-sidebar">
                        <!-- Status -->
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" v-model="form.status" class="form-select">
                                <option :value="true">Active</option>
                                <option :value="false">Inactive</option>
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div class="form-group">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input
                                type="datetime-local"
                                id="start_date"
                                v-model="form.start_date"
                                class="form-input"
                            />
                            <small class="form-help">When the coupon becomes valid</small>
                        </div>

                        <!-- Expire Date -->
                        <div class="form-group">
                            <label for="expire_date" class="form-label">Expire Date</label>
                            <input
                                type="datetime-local"
                                id="expire_date"
                                v-model="form.expire_date"
                                class="form-input"
                                :class="{ 'error': errors.expire_date }"
                            />
                            <span v-if="errors.expire_date" class="error-message">{{ errors.expire_date }}</span>
                            <small class="form-help">When the coupon expires</small>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span v-if="loading">⏳ Creating...</span>
                        <span v-else>💾 Create Coupon</span>
                    </button>
                    <router-link to="/coupons" class="btn btn-secondary">
                        Cancel
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'

const router = useRouter()

const loading = ref(false)
const form = ref({
    code: '',
    description: '',
    discount_type: 'percentage',
    discount: 10,
    usage_limit: 0,
    usage_limit_per_user: 1,
    minimum_amount: 0,
    status: true,
    start_date: new Date().toISOString().slice(0, 16),
    expire_date: ''
})

const errors = ref({})

const handleSubmit = async () => {
    errors.value = {}
    loading.value = true

    try {
        // Validate expire date
        if (form.value.expire_date && form.value.start_date) {
            const startDate = new Date(form.value.start_date)
            const expireDate = new Date(form.value.expire_date)
            if (expireDate <= startDate) {
                errors.value.expire_date = 'Expire date must be after start date'
                loading.value = false
                return
            }
        }

        // Generate code if empty
        if (!form.value.code) {
            form.value.code = 'COUPON' + Date.now().toString().slice(-6)
        }

        // Convert to uppercase
        form.value.code = form.value.code.toUpperCase().replace(/\s+/g, '')

        const response = await api.coupons.create(form.value)

        if (response.data.success) {
            router.push('/coupons')
        } else {
            if (response.data.errors) {
                errors.value = response.data.errors
            } else {
                alert('Failed to create coupon: ' + (response.data.message || 'Unknown error'))
            }
        }
    } catch (error) {
        console.error('Error creating coupon:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            alert('Failed to create coupon: ' + (error.response?.data?.message || 'Unknown error'))
        }
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    // Set default expire date to 30 days from now
    const defaultExpire = new Date()
    defaultExpire.setDate(defaultExpire.getDate() + 30)
    form.value.expire_date = defaultExpire.toISOString().slice(0, 16)
})
</script>

<style scoped>
.coupon-create-page {
    padding: 24px;
    max-width: 1400px;
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

/* Form Container */
.form-container {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.form-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

.form-main {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* Form Groups */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-label {
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.required {
    color: #ef4444;
}

.form-input,
.form-textarea,
.form-select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
    font-family: inherit;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-input.error,
.form-textarea.error,
.form-select.error {
    border-color: #ef4444;
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.form-help {
    color: #64748b;
    font-size: 13px;
}

.error-message {
    color: #ef4444;
    font-size: 13px;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid #e5e7eb;
}

/* Buttons */
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

@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
