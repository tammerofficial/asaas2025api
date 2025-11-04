<template>
    <div class="payment-histories-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">💳 Payment Histories</h1>
                <p class="page-subtitle">View all payment transactions</p>
            </div>
        </div>

        <DataTable
            :columns="columns"
            :data="payments"
            :loading="loading"
            :pagination="pagination"
            @page-change="loadPayments"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DataTable from '../../components/DataTable.vue'

const payments = ref([])
const loading = ref(false)
const pagination = ref({ current_page: 1, last_page: 1, per_page: 20, total: 0 })

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'order_id', label: 'Order ID' },
    { key: 'user_name', label: 'User' },
    { key: 'package_name', label: 'Package' },
    { key: 'amount', label: 'Amount (KWD)' },
    { key: 'payment_status', label: 'Status' },
    { key: 'created_at', label: 'Date' },
]

const loadPayments = async (page = 1) => {
    loading.value = true
    try {
        const response = await axios.get('/subscriptions/payment-histories', { params: { page } })
        if (response.data.success) {
            payments.value = response.data.data
            pagination.value = response.data.meta
        }
    } catch (error) {
        console.error('Error:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => loadPayments())
</script>

<style scoped>
.payment-histories-page { padding: 24px; }
.page-header { margin-bottom: 24px; }
.page-title { font-size: 24px; font-weight: 600; margin: 0 0 4px 0; }
.page-subtitle { color: #64748b; margin: 0; }
</style>
