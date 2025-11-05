<template>
    <div class="themes-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🎨 Themes</h1>
                <p class="page-subtitle">Manage and customize themes</p>
            </div>
            <button class="btn btn-primary" @click="uploadTheme">
                ➕ Upload Theme
            </button>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading-overlay">
            <div class="spinner"></div>
            <p>Loading themes...</p>
        </div>

        <!-- Themes Grid -->
        <div v-else class="themes-grid">
            <div v-for="theme in themes" :key="theme.id" class="theme-card">
                <div class="theme-preview">
                    <img 
                        v-if="theme.screenshot" 
                        :src="theme.screenshot" 
                        :alt="theme.name"
                        class="theme-image"
                    />
                    <div v-else class="theme-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div v-if="theme.is_active" class="active-badge">
                        Active
                    </div>
                </div>
                <div class="theme-info">
                    <h3 class="theme-name">{{ theme.name || theme.title || 'Unnamed Theme' }}</h3>
                    <p class="theme-description">{{ theme.description || 'No description available' }}</p>
                    <div class="theme-meta">
                        <span class="theme-version">v{{ theme.version || '1.0.0' }}</span>
                        <span class="theme-author">by {{ theme.author || 'Unknown' }}</span>
                    </div>
                    <div class="theme-actions">
                        <button 
                            v-if="!theme.is_active"
                            class="btn btn-sm btn-primary" 
                            @click="activateTheme(theme)"
                        >
                            Activate
                        </button>
                        <button 
                            v-else
                            class="btn btn-sm btn-secondary"
                            disabled
                        >
                            Active
                        </button>
                        <button 
                            class="btn btn-sm btn-outline" 
                            @click="customizeTheme(theme)"
                        >
                            Customize
                        </button>
                        <button 
                            class="btn btn-sm btn-danger" 
                            @click="deleteTheme(theme)"
                        >
                            🗑️
                        </button>
                    </div>
                </div>
            </div>
            
            <div v-if="themes.length === 0" class="empty-state">
                <p>No themes found</p>
                <button class="btn btn-primary" @click="uploadTheme">
                    Upload Your First Theme
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'

const router = useRouter()

const themes = ref([])
const loading = ref(false)

const loadThemes = async () => {
    loading.value = true
    try {
        const response = await api.appearances.themes()
        if (response.data.success) {
            themes.value = response.data.data || []
        }
    } catch (error) {
        console.error('Error loading themes:', error)
        themes.value = []
    } finally {
        loading.value = false
    }
}

const activateTheme = async (theme) => {
    if (!confirm(`Activate theme "${theme.name}"?`)) {
        return
    }
    
    try {
        // API call to activate theme
        await loadThemes()
        alert('Theme activated successfully')
    } catch (error) {
        console.error('Error activating theme:', error)
        alert('Failed to activate theme')
    }
}

const customizeTheme = (theme) => {
    router.push(`/appearances/theme-options?theme=${theme.id}`)
}

const deleteTheme = async (theme) => {
    if (!confirm(`Delete theme "${theme.name}"? This action cannot be undone.`)) {
        return
    }
    
    try {
        // API call to delete theme
        await loadThemes()
        alert('Theme deleted successfully')
    } catch (error) {
        console.error('Error deleting theme:', error)
        alert('Failed to delete theme')
    }
}

const uploadTheme = () => {
    alert('Theme upload functionality will be implemented')
}

onMounted(() => {
    loadThemes()
})
</script>

<style scoped>
.themes-page {
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

/* Themes Grid */
.themes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}

.theme-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s, box-shadow 0.2s;
}

.theme-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.theme-preview {
    position: relative;
    width: 100%;
    height: 200px;
    background: #f8fafc;
    overflow: hidden;
}

.theme-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.theme-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e5e7eb;
}

.theme-placeholder svg {
    width: 80px;
    height: 80px;
    color: #9ca3af;
}

.active-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #10b981;
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.theme-info {
    padding: 20px;
}

.theme-name {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.theme-description {
    color: #64748b;
    font-size: 14px;
    margin: 0 0 12px 0;
    line-height: 1.5;
}

.theme-meta {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
    font-size: 13px;
    color: #94a3b8;
}

.theme-version {
    font-weight: 600;
}

.theme-actions {
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
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 9999;
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
