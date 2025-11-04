<template>
    <div class="admins-page">
        <div class="page-header">
            <div>
                <h2>👥 Admins Management</h2>
                <p class="page-subtitle">Manage admin users</p>
            </div>
            <router-link to="/admins/create" class="btn-primary">➕ Add New Admin</router-link>
        </div>

        <DataTable
            :columns="columns"
            :data="admins"
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
            <template #cell-actions="{ row }">
                <div class="action-buttons">
                    <router-link :to="`/admins/${row.id}/edit`" class="btn-sm btn-secondary">Edit</router-link>
                    <button @click="toggleStatus(row)" class="btn-sm" :class="row.status ? 'btn-warning' : 'btn-success'">
                        {{ row.status ? 'Deactivate' : 'Activate' }}
                    </button>
                    <button @click="deleteAdmin(row.id)" class="btn-sm btn-danger">Delete</button>
                </div>
            </template>
        </DataTable>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import api from '../../services/api'
import DataTable from '../../components/DataTable.vue'
import StatusBadge from '../../components/StatusBadge.vue'
import Toast from '../../components/Toast.vue'

export default {
    name: 'AdminsList',
    components: { DataTable, StatusBadge, Toast },
    setup() {
        const loading = ref(false)
        const admins = ref([])
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
            { key: 'name', label: 'Name', sortable: true },
            { key: 'email', label: 'Email', sortable: true },
            { key: 'username', label: 'Username', sortable: true },
            { key: 'status', label: 'Status', sortable: true },
            { key: 'created_at', label: 'Created', sortable: true },
            { key: 'actions', label: 'Actions', sortable: false }
        ]

        const fetchAdmins = async () => {
            loading.value = true
            try {
                const response = await api.admins.list({ page: currentPage.value, per_page: perPage.value })
                if (response.data.success) {
                    admins.value = response.data.data.data || response.data.data || []
                    total.value = response.data.data.total || admins.value.length
                    totalPages.value = response.data.data.last_page || 1
                }
            } catch (error) {
                console.error('Error fetching admins:', error)
                showToastMessage('error', 'Error', 'Failed to load admins')
            } finally {
                loading.value = false
            }
        }

        const toggleStatus = async (admin) => {
            try {
                if (admin.status) {
                    await api.admins.deactivate(admin.id)
                    showToastMessage('success', 'Success', 'Admin deactivated successfully')
                } else {
                    await api.admins.activate(admin.id)
                    showToastMessage('success', 'Success', 'Admin activated successfully')
                }
                fetchAdmins()
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to update admin status')
            }
        }

        const deleteAdmin = async (id) => {
            if (!confirm('Are you sure you want to delete this admin?')) return
            try {
                await api.admins.delete(id)
                showToastMessage('success', 'Success', 'Admin deleted successfully')
                fetchAdmins()
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to delete admin')
            }
        }

        const showToastMessage = (type, title, message) => {
            toastType.value = type
            toastTitle.value = title
            toastMessage.value = message
            showToast.value = true
        }

        watch(currentPage, () => {
            fetchAdmins()
        })

        onMounted(() => {
            fetchAdmins()
        })

        return { loading, admins, columns, currentPage, totalPages, total, perPage, showToast, toastType, toastTitle, toastMessage, toggleStatus, deleteAdmin }
    }
}
</script>

<style scoped>
.admins-page {
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

.btn-success {
    background: #10b981;
    color: white;
}

.btn-warning {
    background: #f59e0b;
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



