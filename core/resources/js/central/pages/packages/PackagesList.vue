<template>
    <div class="packages-page">
        <div class="page-header">
            <div>
                <h2>📦 Packages Management</h2>
                <p class="page-subtitle">Manage pricing plans and packages</p>
            </div>
            <router-link to="/packages/create" class="btn-primary">➕ Add New Package</router-link>
        </div>

        <div class="filters-bar">
            <input v-model="searchQuery" type="text" placeholder="Search packages..." class="search-input" />
            <select v-model="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <DataTable
            :columns="columns"
            :data="packages"
            :loading="loading"
            :pagination="true"
            :current-page="currentPage"
            :total-pages="totalPages"
            :total="total"
            :per-page="perPage"
            @update:current-page="currentPage = $event"
        >
            <template #cell-status="{ value }">
                <StatusBadge :status="value ? 'active' : 'inactive'" />
            </template>
            <template #cell-price="{ value }">
                {{ formatPrice(value) }}
            </template>
            <template #cell-actions="{ row }">
                <div class="action-buttons">
                    <router-link :to="`/packages/${row.id}/edit`" class="btn-sm btn-secondary">Edit</router-link>
                    <button @click="deletePackage(row.id)" class="btn-sm btn-danger">Delete</button>
                </div>
            </template>
        </DataTable>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import DataTable from '../../components/DataTable.vue'
import StatusBadge from '../../components/StatusBadge.vue'
import Toast from '../../components/Toast.vue'

export default {
    name: 'PackagesList',
    components: { DataTable, StatusBadge, Toast },
    setup() {
        const router = useRouter()
        const loading = ref(false)
        const packages = ref([])
        const searchQuery = ref('')
        const statusFilter = ref('')
        const currentPage = ref(1)
        const totalPages = ref(1)
        const total = ref(0)
        const perPage = ref(20)
        const showToast = ref(false)
        const toastType = ref('success')
        const toastTitle = ref('')
        const toastMessage = ref('')

        const columns = [
            { key: 'id', label: 'ID', sortable: true },
            { key: 'title', label: 'Title', sortable: true },
            { key: 'price', label: 'Price', sortable: true },
            { key: 'type', label: 'Type', sortable: false },
            { key: 'status', label: 'Status', sortable: true },
            { key: 'actions', label: 'Actions', sortable: false }
        ]

        const fetchPackages = async () => {
            loading.value = true
            try {
                const params = {
                    page: currentPage.value,
                    per_page: perPage.value,
                    search: searchQuery.value,
                    status: statusFilter.value
                }
                const response = await api.plans.list(params)
                if (response.data.success) {
                    packages.value = response.data.data.data || response.data.data || []
                    total.value = response.data.data.total || packages.value.length
                    totalPages.value = response.data.data.last_page || 1
                }
            } catch (error) {
                console.error('Error fetching packages:', error)
                showToastMessage('error', 'Error', 'Failed to load packages')
            } finally {
                loading.value = false
            }
        }

        const deletePackage = async (id) => {
            if (!confirm('Are you sure you want to delete this package?')) return
            try {
                await api.plans.delete(id)
                showToastMessage('success', 'Success', 'Package deleted successfully')
                fetchPackages()
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to delete package')
            }
        }

        const formatPrice = (price) => {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'KWD' }).format(price || 0)
        }

        const showToastMessage = (type, title, message) => {
            toastType.value = type
            toastTitle.value = title
            toastMessage.value = message
            showToast.value = true
        }

        watch([searchQuery, statusFilter], () => {
            currentPage.value = 1
            fetchPackages()
        })

        watch(currentPage, () => {
            fetchPackages()
        })

        onMounted(() => {
            fetchPackages()
        })

        return {
            loading,
            packages,
            columns,
            searchQuery,
            statusFilter,
            currentPage,
            totalPages,
            total,
            perPage,
            showToast,
            toastType,
            toastTitle,
            toastMessage,
            deletePackage,
            formatPrice
        }
    }
}
</script>

<style scoped>
.packages-page {
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.page-header h2 {
    margin: 0 0 4px 0;
    font-size: 24px;
    color: #1e293b;
}

.page-subtitle {
    color: #64748b;
    font-size: 14px;
    margin: 0;
}

.filters-bar {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.search-input {
    flex: 1;
    max-width: 300px;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
}

.filter-select {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: white;
}

.btn-primary {
    padding: 10px 20px;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
}

.btn-sm {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
    margin-right: 8px;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 8px;
}
</style>



