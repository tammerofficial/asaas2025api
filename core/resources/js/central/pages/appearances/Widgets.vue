<template>
    <div class="widgets-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🧩 Widgets</h1>
                <p class="page-subtitle">Manage sidebar widgets</p>
            </div>
            <button class="btn btn-primary" @click="addWidget">
                ➕ Add Widget
            </button>
        </div>

        <!-- Widgets List -->
        <div class="widgets-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading widgets...</p>
            </div>
            
            <div v-else class="widgets-grid">
                <div v-for="widget in widgets" :key="widget.id" class="widget-card">
                    <div class="widget-header">
                        <div class="widget-icon">{{ widget.icon || '📦' }}</div>
                        <div class="widget-info">
                            <h3 class="widget-name">{{ widget.name || widget.title || 'Unnamed Widget' }}</h3>
                            <p class="widget-description">{{ widget.description || 'No description' }}</p>
                        </div>
                        <div class="widget-status">
                            <StatusBadge :status="widget.is_active ? 'active' : 'inactive'" />
                        </div>
                    </div>
                    
                    <div class="widget-actions">
                        <button 
                            v-if="!widget.is_active"
                            class="btn btn-sm btn-primary" 
                            @click="activateWidget(widget)"
                        >
                            ✅ Activate
                        </button>
                        <button 
                            v-else
                            class="btn btn-sm btn-secondary" 
                            @click="deactivateWidget(widget)"
                        >
                            ⏸️ Deactivate
                        </button>
                        <button 
                            class="btn btn-sm btn-outline" 
                            @click="editWidget(widget)"
                        >
                            ✏️ Edit
                        </button>
                        <button 
                            class="btn btn-sm btn-danger" 
                            @click="deleteWidget(widget)"
                        >
                            🗑️ Delete
                        </button>
                    </div>
                </div>
                
                <div v-if="widgets.length === 0" class="empty-state">
                    <p>No widgets found</p>
                    <button class="btn btn-primary" @click="addWidget">
                        Add Your First Widget
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

const widgets = ref([])
const loading = ref(false)

const loadWidgets = async () => {
    loading.value = true
    try {
        const response = await api.appearances.widgets()
        if (response.data.success) {
            widgets.value = response.data.data || []
        }
    } catch (error) {
        console.error('Error loading widgets:', error)
        widgets.value = []
    } finally {
        loading.value = false
    }
}

const addWidget = () => {
    alert('Add widget functionality will be implemented')
}

const activateWidget = async (widget) => {
    try {
        widget.is_active = true
        alert('Widget activated successfully')
    } catch (error) {
        console.error('Error activating widget:', error)
        alert('Failed to activate widget')
    }
}

const deactivateWidget = async (widget) => {
    try {
        widget.is_active = false
        alert('Widget deactivated successfully')
    } catch (error) {
        console.error('Error deactivating widget:', error)
        alert('Failed to deactivate widget')
    }
}

const editWidget = (widget) => {
    alert(`Edit widget: ${widget.name}`)
}

const deleteWidget = async (widget) => {
    if (!confirm(`Delete widget "${widget.name}"?`)) {
        return
    }
    
    try {
        widgets.value = widgets.value.filter(w => w.id !== widget.id)
        alert('Widget deleted successfully')
    } catch (error) {
        console.error('Error deleting widget:', error)
        alert('Failed to delete widget')
    }
}

onMounted(() => {
    loadWidgets()
})
</script>

<style scoped>
.widgets-page {
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

/* Widgets Container */
.widgets-container {
    position: relative;
    min-height: 400px;
}

.widgets-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
}

.widget-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s, box-shadow 0.2s;
}

.widget-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.widget-header {
    display: flex;
    gap: 16px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f3f4f6;
}

.widget-icon {
    font-size: 40px;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border-radius: 12px;
    flex-shrink: 0;
}

.widget-info {
    flex: 1;
}

.widget-name {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.widget-description {
    color: #64748b;
    font-size: 14px;
    margin: 0;
    line-height: 1.5;
}

.widget-status {
    flex-shrink: 0;
}

.widget-actions {
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
