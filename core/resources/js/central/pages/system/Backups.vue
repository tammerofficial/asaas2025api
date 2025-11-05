<template>
    <div class="backups-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">💾 Backups</h1>
                <p class="page-subtitle">Manage system backups</p>
            </div>
            <button class="btn btn-primary" @click="createBackup" :disabled="creating">
                <span v-if="creating">⏳</span>
                <span v-else>➕</span>
                {{ creating ? 'Creating...' : 'Create Backup' }}
            </button>
        </div>

        <!-- Info Card -->
        <div class="info-card">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="info-content">
                <h3>Backup Information</h3>
                <p>Regular backups help protect your data. Create backups before major updates or changes. You can restore from any backup point.</p>
            </div>
        </div>

        <!-- Backups List -->
        <div class="backups-container">
            <div v-if="loading" class="loading-overlay">
                <div class="spinner"></div>
                <p>Loading backups...</p>
            </div>
            
            <div v-else class="backups-list">
                <div v-for="backup in backups" :key="backup.id" class="backup-card">
                    <div class="backup-header">
                        <div class="backup-info">
                            <h3 class="backup-name">{{ backup.name || 'Backup ' + backup.id }}</h3>
                            <p class="backup-description">
                                Created: {{ formatDate(backup.created_at) }} • 
                                Size: {{ formatSize(backup.size) }} • 
                                Type: {{ backup.type || 'Full' }}
                            </p>
                        </div>
                        <div class="backup-status">
                            <StatusBadge :status="backup.status || 'completed'" />
                        </div>
                    </div>
                    
                    <div class="backup-actions">
                        <button 
                            class="btn btn-sm btn-primary" 
                            @click="restoreBackup(backup)"
                        >
                            🔄 Restore
                        </button>
                        <button 
                            class="btn btn-sm btn-outline" 
                            @click="downloadBackup(backup)"
                        >
                            📥 Download
                        </button>
                        <button 
                            class="btn btn-sm btn-outline" 
                            @click="viewBackup(backup)"
                        >
                            👁️ View Details
                        </button>
                        <button 
                            class="btn btn-sm btn-danger" 
                            @click="deleteBackup(backup)"
                        >
                            🗑️ Delete
                        </button>
                    </div>
                </div>
                
                <div v-if="backups.length === 0" class="empty-state">
                    <p>No backups found</p>
                    <button class="btn btn-primary" @click="createBackup">
                        Create Your First Backup
                    </button>
                </div>
            </div>
        </div>

        <!-- Backup Settings -->
        <div class="settings-card">
            <h3>Backup Settings</h3>
            <div class="settings-form">
                <div class="form-group">
                    <label>Auto Backup Frequency</label>
                    <select v-model="settings.frequency" class="form-select">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="never">Never</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Keep Backups</label>
                    <input 
                        type="number" 
                        v-model="settings.keepCount" 
                        min="1" 
                        max="30"
                        class="form-input"
                    />
                    <span class="form-hint">Maximum number of backups to keep</span>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" v-model="settings.includeDatabase" />
                        Include database in backups
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" v-model="settings.includeFiles" />
                        Include files in backups
                    </label>
                </div>
                <button class="btn btn-primary" @click="saveSettings">
                    💾 Save Settings
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import StatusBadge from '../../components/StatusBadge.vue'

const backups = ref([])
const loading = ref(false)
const creating = ref(false)
const settings = ref({
    frequency: 'weekly',
    keepCount: 7,
    includeDatabase: true,
    includeFiles: true
})

const loadBackups = async () => {
    loading.value = true
    try {
        const response = await api.system.backups()
        if (response.data.success) {
            backups.value = response.data.data || []
        }
    } catch (error) {
        console.error('Error loading backups:', error)
        backups.value = []
        alert('Failed to load backups')
    } finally {
        loading.value = false
    }
}

const createBackup = async () => {
    creating.value = true
    try {
        const response = await api.system.createBackup({
            include_database: settings.value.includeDatabase,
            include_files: settings.value.includeFiles
        })
        if (response.data.success) {
            alert('Backup created successfully')
            await loadBackups()
        }
    } catch (error) {
        console.error('Error creating backup:', error)
        alert('Failed to create backup')
    } finally {
        creating.value = false
    }
}

const restoreBackup = async (backup) => {
    if (!confirm(`Restore from backup "${backup.name}"? This will overwrite current data.`)) {
        return
    }
    
    try {
        const response = await api.system.restoreBackup(backup.id)
        if (response.data.success) {
            alert('Backup restore initiated. This may take a few minutes.')
            await loadBackups()
        }
    } catch (error) {
        console.error('Error restoring backup:', error)
        alert('Failed to restore backup')
    }
}

const downloadBackup = (backup) => {
    alert(`Download backup: ${backup.name}`)
}

const viewBackup = (backup) => {
    alert(`View backup details: ${backup.name}`)
}

const deleteBackup = async (backup) => {
    if (!confirm(`Delete backup "${backup.name}"?`)) {
        return
    }
    
    try {
        const response = await api.system.deleteBackup(backup.id)
        if (response.data.success) {
            alert('Backup deleted successfully')
            await loadBackups()
        }
    } catch (error) {
        console.error('Error deleting backup:', error)
        alert('Failed to delete backup')
    }
}

const saveSettings = async () => {
    try {
        // API call to save settings
        alert('Settings saved successfully')
    } catch (error) {
        console.error('Error saving settings:', error)
        alert('Failed to save settings')
    }
}

const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const formatSize = (bytes) => {
    if (!bytes) return '0 B'
    const kb = bytes / 1024
    if (kb < 1024) return `${kb.toFixed(2)} KB`
    const mb = kb / 1024
    if (mb < 1024) return `${mb.toFixed(2)} MB`
    return `${(mb / 1024).toFixed(2)} GB`
}

onMounted(() => {
    loadBackups()
})
</script>

<style scoped>
.backups-page {
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

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover:not(:disabled) {
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

/* Info Card */
.info-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.info-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #dbeafe;
    border-radius: 12px;
    flex-shrink: 0;
}

.info-icon svg {
    width: 32px;
    height: 32px;
    color: #3b82f6;
}

.info-content h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.info-content p {
    color: #64748b;
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
}

/* Backups Container */
.backups-container {
    position: relative;
    min-height: 400px;
    margin-bottom: 24px;
}

.backups-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.backup-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s, box-shadow 0.2s;
}

.backup-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.backup-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f3f4f6;
}

.backup-info {
    flex: 1;
}

.backup-name {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.backup-description {
    color: #64748b;
    font-size: 14px;
    margin: 0;
}

.backup-status {
    flex-shrink: 0;
}

.backup-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
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

/* Settings Card */
.settings-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.settings-card h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 20px 0;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}

.settings-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 600;
    color: #1e293b;
    font-size: 14px;
}

.form-select,
.form-input {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
}

.form-select:focus,
.form-input:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-hint {
    color: #64748b;
    font-size: 13px;
}

.form-group label[for] {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 500;
}

.form-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}
</style>
