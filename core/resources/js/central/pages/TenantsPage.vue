<template>
    <div class="tenants-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2>🏢 Tenants Management</h2>
                <p class="page-subtitle">Manage all your tenants and their subscriptions</p>
            </div>
            <button class="btn-primary" @click="showCreateModal = true">
                ➕ Add New Tenant
            </button>
        </div>
        
        <!-- Filters -->
        <div class="filters-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search tenants..."
                    class="search-input"
                />
            </div>
            <select v-model="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        
        <!-- Tenants Table -->
        <div class="table-container">
            <table class="tenants-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Domain</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loading">
                        <td colspan="7" class="loading-cell">
                            <div class="spinner-small"></div>
                            Loading tenants...
                        </td>
                    </tr>
                    <tr v-else-if="filteredTenants.length === 0">
                        <td colspan="7" class="empty-cell">
                            No tenants found
                        </td>
                    </tr>
                    <tr v-else v-for="tenant in filteredTenants" :key="tenant.id">
                        <td>{{ tenant.id }}</td>
                        <td>
                            <div class="tenant-name">
                                <strong>{{ tenant.name || 'N/A' }}</strong>
                            </div>
                        </td>
                        <td>
                            <code class="domain-code">{{ tenant.domain || 'N/A' }}</code>
                        </td>
                        <td>
                            <span class="plan-badge">{{ tenant.plan_name || 'Free' }}</span>
                        </td>
                        <td>
                            <span 
                                class="status-badge"
                                :class="tenant.status === 'active' ? 'active' : 'inactive'"
                            >
                                {{ tenant.status || 'inactive' }}
                            </span>
                        </td>
                        <td>{{ formatDate(tenant.created_at) }}</td>
                        <td>
                            <div class="action-buttons">
                                <button 
                                    class="btn-icon" 
                                    @click="viewTenant(tenant)"
                                    title="View"
                                >
                                    👁️
                                </button>
                                <button 
                                    class="btn-icon" 
                                    @click="editTenant(tenant)"
                                    title="Edit"
                                >
                                    ✏️
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div v-if="!loading && filteredTenants.length > 0" class="pagination">
            <button 
                class="pagination-btn"
                :disabled="currentPage === 1"
                @click="currentPage--"
            >
                ← Previous
            </button>
            <span class="pagination-info">
                Page {{ currentPage }} of {{ totalPages }}
            </span>
            <button 
                class="pagination-btn"
                :disabled="currentPage >= totalPages"
                @click="currentPage++"
            >
                Next →
            </button>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

export default {
    name: 'TenantsPage',
    setup() {
        const loading = ref(false)
        const tenants = ref([])
        const searchQuery = ref('')
        const statusFilter = ref('')
        const currentPage = ref(1)
        const perPage = ref(10)
        
        // Mock data for demonstration
        const mockTenants = [
            {
                id: 1,
                name: 'Kuwait Store',
                domain: 'kuwait-store.asaas.local',
                plan_name: 'Premium',
                status: 'active',
                created_at: '2024-01-15'
            },
            {
                id: 2,
                name: 'Al-Salam Market',
                domain: 'alsalam.asaas.local',
                plan_name: 'Basic',
                status: 'active',
                created_at: '2024-02-20'
            },
            {
                id: 3,
                name: 'Digital Hub',
                domain: 'digitalhub.asaas.local',
                plan_name: 'Enterprise',
                status: 'inactive',
                created_at: '2024-03-10'
            }
        ]
        
        const fetchTenants = async () => {
            loading.value = true
            try {
                const response = await axios.get('/tenants')
                
                // Handle Laravel Resource Collection response
                if (response.data && response.data.data) {
                    // Map the data to our format
                    tenants.value = response.data.data.map(tenant => ({
                        id: tenant.id,
                        name: tenant.user?.name || tenant.id,
                        domain: tenant.domains?.[0]?.domain || 'No domain',
                        plan_name: tenant.payment_log?.package_name || 'Free',
                        status: tenant.user_id ? 'active' : 'inactive',
                        created_at: tenant.created_at
                    }))
                } else {
                    tenants.value = []
                }
            } catch (error) {
                console.error('Error fetching tenants:', error)
                // Show empty state on error
                tenants.value = []
            } finally {
                loading.value = false
            }
        }
        
        const filteredTenants = computed(() => {
            let filtered = tenants.value
            
            // Filter by search query
            if (searchQuery.value) {
                const query = searchQuery.value.toLowerCase()
                filtered = filtered.filter(tenant => 
                    tenant.name?.toLowerCase().includes(query) ||
                    tenant.domain?.toLowerCase().includes(query)
                )
            }
            
            // Filter by status
            if (statusFilter.value) {
                filtered = filtered.filter(tenant => 
                    tenant.status === statusFilter.value
                )
            }
            
            // Pagination
            const start = (currentPage.value - 1) * perPage.value
            const end = start + perPage.value
            return filtered.slice(start, end)
        })
        
        const totalPages = computed(() => {
            return Math.ceil(tenants.value.length / perPage.value)
        })
        
        const formatDate = (dateString) => {
            if (!dateString) return 'N/A'
            const date = new Date(dateString)
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            })
        }
        
        const viewTenant = (tenant) => {
            alert(`View tenant: ${tenant.name}`)
        }
        
        const editTenant = (tenant) => {
            alert(`Edit tenant: ${tenant.name}`)
        }
        
        onMounted(() => {
            fetchTenants()
        })
        
        return {
            loading,
            tenants,
            searchQuery,
            statusFilter,
            currentPage,
            filteredTenants,
            totalPages,
            formatDate,
            viewTenant,
            editTenant,
            showCreateModal: ref(false)
        }
    }
}
</script>

<style scoped>
.tenants-page {
    max-width: 1400px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
}

.page-header h2 {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.page-subtitle {
    color: #64748b;
    font-size: 15px;
}

.btn-primary {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-primary:hover {
    background: #2563eb;
}

/* Filters */
.filters-bar {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.search-box {
    flex: 1;
}

.search-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
}

.filter-select {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    background: white;
}

/* Table */
.table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.tenants-table {
    width: 100%;
    border-collapse: collapse;
}

.tenants-table thead {
    background: #f8fafc;
}

.tenants-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tenants-table td {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    color: #1e293b;
}

.tenant-name strong {
    color: #1e293b;
}

.domain-code {
    background: #f1f5f9;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 13px;
    color: #475569;
}

.plan-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    text-transform: capitalize;
}

.status-badge.active {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.inactive {
    background: #fee2e2;
    color: #991b1b;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-icon {
    background: #f1f5f9;
    border: none;
    padding: 8px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.2s;
}

.btn-icon:hover {
    background: #e2e8f0;
}

.loading-cell,
.empty-cell {
    text-align: center;
    padding: 40px !important;
    color: #64748b;
}

.spinner-small {
    width: 24px;
    height: 24px;
    border: 3px solid #e5e7eb;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 12px;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-top: 30px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.pagination-btn {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
}

.pagination-btn:hover:not(:disabled) {
    background: #2563eb;
}

.pagination-btn:disabled {
    background: #e5e7eb;
    color: #94a3b8;
    cursor: not-allowed;
}

.pagination-info {
    color: #64748b;
    font-weight: 500;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

