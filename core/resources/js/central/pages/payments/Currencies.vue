<template>
    <div class="currencies-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">💱 Currencies</h1>
                <p class="page-subtitle">Manage currencies and exchange rates</p>
            </div>
        </div>

        <div class="table-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading currencies...</p>
            </div>
            
            <div v-else class="currencies-content">
                <div class="current-currency">
                    <h3>Current Global Currency</h3>
                    <div class="currency-badge">
                        <strong>{{ currentCurrency }}</strong>
                    </div>
                </div>

                <table class="currencies-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Symbol</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="currency in currencies" :key="currency.code">
                            <td><code class="currency-code">{{ currency.code }}</code></td>
                            <td><strong>{{ currency.name }}</strong></td>
                            <td>{{ currency.symbol }}</td>
                            <td>
                                <StatusBadge :status="currency.code === currentCurrency ? 'active' : 'inactive'" />
                            </td>
                            <td>
                                <button 
                                    v-if="currency.code !== currentCurrency"
                                    class="btn btn-sm btn-primary"
                                    @click="setCurrency(currency.code)"
                                >
                                    Set as Default
                                </button>
                                <span v-else class="badge-active">Current</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import StatusBadge from '../../components/StatusBadge.vue'
import Toast from '../../components/Toast.vue'

const currencies = ref([])
const currentCurrency = ref('')
const loading = ref(false)
const showToast = ref(false)
const toastType = ref('success')
const toastTitle = ref('')
const toastMessage = ref('')

const loadCurrencies = async () => {
    loading.value = true
    try {
        const response = await axios.get('/currencies')
        if (response.data.success) {
            currencies.value = response.data.data.currencies
            currentCurrency.value = response.data.data.current_currency
        }
    } catch (error) {
        console.error('Error loading currencies:', error)
        showToastMessage('error', 'Error', 'Failed to load currencies')
    } finally {
        loading.value = false
    }
}

const setCurrency = async (code) => {
    try {
        const response = await axios.put('/payment-settings', {
            site_global_currency: code
        })
        if (response.data.success) {
            showToastMessage('success', 'Success', 'Currency updated successfully')
            currentCurrency.value = code
        }
    } catch (error) {
        console.error('Error setting currency:', error)
        showToastMessage('error', 'Error', error.response?.data?.message || 'Failed to update currency')
    }
}

const showToastMessage = (type, title, message) => {
    toastType.value = type
    toastTitle.value = title
    toastMessage.value = message
    showToast.value = true
}

onMounted(() => loadCurrencies())
</script>

<style scoped>
.currencies-page { padding: 24px; }
.page-header { margin-bottom: 24px; }
.page-title { font-size: 28px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0; }
.page-subtitle { color: #64748b; font-size: 15px; margin: 0; }
.table-container { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); position: relative; min-height: 200px; }
.loading-overlay { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px; color: #64748b; }
.spinner { width: 40px; height: 40px; border: 4px solid #f1f5f9; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 16px; }
@keyframes spin { to { transform: rotate(360deg); } }
.currencies-content { padding: 24px; }
.current-currency { margin-bottom: 24px; padding: 20px; background: #f8fafc; border-radius: 8px; }
.current-currency h3 { margin: 0 0 12px 0; color: #475569; font-size: 16px; }
.currency-badge { display: inline-block; padding: 8px 16px; background: #3b82f6; color: white; border-radius: 6px; font-size: 18px; }
.currencies-table { width: 100%; border-collapse: collapse; }
.currencies-table thead { background: #f8fafc; }
.currencies-table th { padding: 16px; text-align: left; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; }
.currencies-table td { padding: 16px; border-top: 1px solid #f1f5f9; color: #334155; }
.currency-code { padding: 4px 8px; background: #f1f5f9; border-radius: 4px; font-family: monospace; }
.badge-active { padding: 4px 12px; background: #10b981; color: white; border-radius: 4px; font-size: 13px; }
.btn { padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover { background: #2563eb; }
.btn-sm { padding: 6px 12px; font-size: 13px; }
</style>
