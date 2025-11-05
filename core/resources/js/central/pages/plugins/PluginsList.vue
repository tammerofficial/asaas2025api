<template>
    <div class="plugins-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🔌 Plugins</h1>
                <p class="page-subtitle">Manage and configure plugins</p>
            </div>
            <button class="btn btn-primary" @click="uploadPlugin">
                ➕ Upload Plugin
            </button>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <div class="search-box">
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search plugins..."
                    class="search-input"
                    @input="debounceSearch"
                />
            </div>
            <select v-model="statusFilter" class="filter-select" @change="loadPlugins">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <!-- Plugins Grid -->
        <div class="plugins-grid">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading plugins...</p>
            </div>
            
            <div v-else v-for="plugin in filteredPlugins" :key="plugin.id" class="plugin-card">
                <div class="plugin-header">
                    <div class="plugin-info">
                        <h3 class="plugin-name">{{ plugin.name || plugin.title || 'Unnamed Plugin' }}</h3>
                        <p class="plugin-description">{{ plugin.description || 'No description available' }}</p>
                    </div>
                    <div class="plugin-status">
                        <StatusBadge :status="plugin.is_active ? 'active' : 'inactive'" />
                    </div>
                </div>
                
                <div class="plugin-meta">
                    <div class="meta-item">
                        <span class="meta-label">Version:</span>
                        <span class="meta-value">{{ plugin.version || '1.0.0' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Author:</span>
                        <span class="meta-value">{{ plugin.author || 'Unknown' }}</span>
                    </div>
                    <div class="meta-item" v-if="plugin.installed_at">
                        <span class="meta-label">Installed:</span>
                        <span class="meta-value">{{ formatDate(plugin.installed_at) }}</span>
                    </div>
                </div>
                
                <div class="plugin-actions">
                    <button 
                        v-if="!plugin.is_active"
                        class="btn btn-sm btn-primary" 
                        @click="activatePlugin(plugin)"
                    >
                        ✅ Activate
                    </button>
                    <button 
                        v-else
                        class="btn btn-sm btn-secondary" 
                        @click="deactivatePlugin(plugin)"
                    >
                        ⏸️ Deactivate
                    </button>
                    <button 
                        class="btn btn-sm btn-outline" 
                        @click="configurePlugin(plugin)"
                    >
                        ⚙️ Configure
                    </button>
                    <button 
                        v-if="plugin.is_active"
                        class="btn btn-sm btn-outline" 
                        @click="viewPlugin(plugin)"
                    >
                        👁️ View
                    </button>
                    <button 
                        class="btn btn-sm btn-danger" 
                        @click="deletePlugin(plugin)"
                    >
                        🗑️ Delete
                    </button>
                </div>
            </div>
            
            <div v-if="!loading && filteredPlugins.length === 0" class="empty-state">
                <p>{{ searchQuery ? 'No plugins found matching your search' : 'No plugins found' }}</p>
                <button v-if="!searchQuery" class="btn btn-primary" @click="uploadPlugin">
                    Upload Your First Plugin
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

const plugins = ref([])
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')

let searchTimeout = null

const filteredPlugins = computed(() => {
    let filtered = plugins.value
    
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(plugin => 
            (plugin.name || '').toLowerCase().includes(query) ||
            (plugin.description || '').toLowerCase().includes(query)
        )
    }
    
    if (statusFilter.value) {
        filtered = filtered.filter(plugin => {
            if (statusFilter.value === 'active') {
                return plugin.is_active
            } else {
                return !plugin.is_active
            }
        })
    }
    
    return filtered
})

const loadPlugins = async () => {
    loading.value = true
    try {
        // Simulate API call - replace with actual API endpoint
        // const response = await api.plugins.list()
        // if (response.data.success) {
        //     plugins.value = response.data.data || []
        // }
        
        // Mock data for now
        plugins.value = [
            {
                id: 1,
                name: 'Payment Gateway',
                description: 'Integration with payment gateways',
                version: '2.1.0',
                author: 'System',
                is_active: true,
                installed_at: new Date().toISOString()
            },
            {
                id: 2,
                name: 'SEO Tools',
                description: 'Advanced SEO optimization tools',
                version: '1.5.0',
                author: 'System',
                is_active: false,
                installed_at: new Date().toISOString()
            }
        ]
    } catch (error) {
        console.error('Error loading plugins:', error)
        plugins.value = []
    } finally {
        loading.value = false
    }
}

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        // Search is handled by computed property
    }, 300)
}

const activatePlugin = async (plugin) => {
    if (!confirm(`Activate plugin "${plugin.name}"?`)) {
        return
    }
    
    try {
        // API call to activate plugin
        plugin.is_active = true
        alert('Plugin activated successfully')
    } catch (error) {
        console.error('Error activating plugin:', error)
        alert('Failed to activate plugin')
    }
}

const deactivatePlugin = async (plugin) => {
    if (!confirm(`Deactivate plugin "${plugin.name}"?`)) {
        return
    }
    
    try {
        // API call to deactivate plugin
        plugin.is_active = false
        alert('Plugin deactivated successfully')
    } catch (error) {
        console.error('Error deactivating plugin:', error)
        alert('Failed to deactivate plugin')
    }
}

const configurePlugin = (plugin) => {
    alert(`Configure plugin: ${plugin.name}`)
}

const viewPlugin = (plugin) => {
    alert(`View plugin: ${plugin.name}`)
}

const deletePlugin = async (plugin) => {
    if (!confirm(`Delete plugin "${plugin.name}"? This action cannot be undone.`)) {
        return
    }
    
    try {
        // API call to delete plugin
        plugins.value = plugins.value.filter(p => p.id !== plugin.id)
        alert('Plugin deleted successfully')
    } catch (error) {
        console.error('Error deleting plugin:', error)
        alert('Failed to delete plugin')
    }
}

const uploadPlugin = () => {
    alert('Plugin upload functionality will be implemented')
}

const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

onMounted(() => {
    loadPlugins()
})
</script>

<style scoped>
.plugins-page {
    padding: 24px;
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

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #64748b;
    color: white;
}

.btn-secondary:hover {
    background: #475569;
}

.btn-outline {
    background: white;
    color: #3b82f6;
    border: 2px solid #3b82f6;
}

.btn-outline:hover {
    background: #f0f9ff;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 14px;
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

/* Plugins Grid */
.plugins-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
    position: relative;
    min-height: 400px;
}

.plugin-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s, box-shadow 0.2s;
}

.plugin-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.plugin-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}

.plugin-info {
    flex: 1;
}

.plugin-name {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.plugin-description {
    color: #64748b;
    font-size: 14px;
    margin: 0;
    line-height: 1.5;
}

.plugin-status {
    flex-shrink: 0;
}

.plugin-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}

.meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.meta-label {
    color: #64748b;
    font-size: 13px;
}

.meta-value {
    color: #1e293b;
    font-weight: 500;
    font-size: 13px;
}

.plugin-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.empty-state p {
    color: #64748b;
    font-size: 16px;
    margin-bottom: 20px;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e5e7eb;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-bottom: 16px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.loading-overlay p {
    color: #64748b;
    font-weight: 500;
}
</style>
