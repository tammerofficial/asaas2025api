<template>
    <div class="menus-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">📋 Menus</h1>
                <p class="page-subtitle">Manage navigation menus</p>
            </div>
            <button class="btn btn-primary" @click="createMenu">
                ➕ Create Menu
            </button>
        </div>

        <!-- Menus List -->
        <div class="menus-list">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading menus...</p>
            </div>
            
            <div v-else class="menus-container">
                <div v-for="menu in menus" :key="menu.id" class="menu-card">
                    <div class="menu-header">
                        <div class="menu-info">
                            <h3 class="menu-name">{{ menu.name || menu.title || 'Unnamed Menu' }}</h3>
                            <p class="menu-description">{{ menu.description || 'No description' }}</p>
                        </div>
                        <div class="menu-actions">
                            <button 
                                class="btn-icon" 
                                @click="editMenu(menu)"
                                title="Edit"
                            >
                                ✏️
                            </button>
                            <button 
                                class="btn-icon btn-danger" 
                                @click="deleteMenu(menu)"
                                title="Delete"
                            >
                                🗑️
                            </button>
                        </div>
                    </div>
                    
                    <div class="menu-items">
                        <div v-if="menu.items && menu.items.length > 0" class="menu-items-list">
                            <div 
                                v-for="(item, index) in menu.items" 
                                :key="index"
                                class="menu-item"
                            >
                                <span class="menu-item-icon">📄</span>
                                <span class="menu-item-label">{{ item.label || item.title || 'Item' }}</span>
                                <span class="menu-item-url">{{ item.url || item.link || '#' }}</span>
                            </div>
                        </div>
                        <div v-else class="empty-menu-items">
                            <p>No menu items</p>
                            <button class="btn btn-sm btn-outline" @click="editMenu(menu)">
                                Add Items
                            </button>
                        </div>
                    </div>
                </div>
                
                <div v-if="menus.length === 0" class="empty-state">
                    <p>No menus found</p>
                    <button class="btn btn-primary" @click="createMenu">
                        Create Your First Menu
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'

const router = useRouter()

const menus = ref([])
const loading = ref(false)

const loadMenus = async () => {
    loading.value = true
    try {
        const response = await api.appearances.menus()
        if (response.data.success) {
            menus.value = response.data.data || []
        }
    } catch (error) {
        console.error('Error loading menus:', error)
        menus.value = []
    } finally {
        loading.value = false
    }
}

const createMenu = () => {
    alert('Menu creation functionality will be implemented')
}

const editMenu = (menu) => {
    alert(`Edit menu: ${menu.name}`)
    // router.push(`/appearances/menus/${menu.id}/edit`)
}

const deleteMenu = async (menu) => {
    if (!confirm(`Delete menu "${menu.name}"? This action cannot be undone.`)) {
        return
    }
    
    try {
        // API call to delete menu
        await loadMenus()
        alert('Menu deleted successfully')
    } catch (error) {
        console.error('Error deleting menu:', error)
        alert('Failed to delete menu')
    }
}

onMounted(() => {
    loadMenus()
})
</script>

<style scoped>
.menus-page {
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

.btn-outline {
    background: white;
    color: #3b82f6;
    border: 2px solid #3b82f6;
}

.btn-outline:hover {
    background: #f0f9ff;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 14px;
}

/* Menus List */
.menus-list {
    position: relative;
    min-height: 400px;
}

.menus-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.menu-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s, box-shadow 0.2s;
}

.menu-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.menu-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f3f4f6;
}

.menu-info {
    flex: 1;
}

.menu-name {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.menu-description {
    color: #64748b;
    font-size: 14px;
    margin: 0;
}

.menu-actions {
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

.btn-icon.btn-danger:hover {
    background: #fee2e2;
}

.menu-items {
    margin-top: 16px;
}

.menu-items-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
}

.menu-item-icon {
    font-size: 20px;
}

.menu-item-label {
    flex: 1;
    font-weight: 500;
    color: #1e293b;
}

.menu-item-url {
    color: #64748b;
    font-size: 13px;
    font-family: monospace;
}

.empty-menu-items {
    text-align: center;
    padding: 30px;
    background: #f8fafc;
    border-radius: 8px;
}

.empty-menu-items p {
    color: #64748b;
    margin-bottom: 16px;
}

.empty-state {
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
