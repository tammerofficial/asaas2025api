<template>
    <div class="update-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🔄 System Update</h1>
                <p class="page-subtitle">Check for and install system updates</p>
            </div>
            <button class="btn btn-primary" @click="checkForUpdates" :disabled="checking">
                <span v-if="checking">⏳</span>
                <span v-else>🔍</span>
                {{ checking ? 'Checking...' : 'Check for Updates' }}
            </button>
        </div>

        <!-- Current Version Info -->
        <div class="info-card">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.739.62 1.074 1.16.318.517.54 1.05.541 1.604a2.75 2.75 0 01-2.33 2.726l-1.378.208a14.75 14.75 0 01-2.326 0l-1.378-.208a2.75 2.75 0 01-2.33-2.726c.001-.554.223-1.087.541-1.604.335-.54.75-.964 1.074-1.16.073-.044.146-.086.22-.127.332-.184.582-.496.645-.87L9.594 3.94zm4.154 2.336a.75.75 0 00-.75-.75l-3.196.033a.75.75 0 00.008 1.5l1.666-.017a12.04 12.04 0 011.272.006zm-5.5 2.5a.75.75 0 00-.75.75v.06l1.5.09a.75.75 0 10.088-1.5l-.838.05v-.06zm5.5.06l.838.05a.75.75 0 00-.088-1.5l-1.5-.09v.06a.75.75 0 00.75.75zM9.25 11.5a.75.75 0 000 1.5h5.5a.75.75 0 000-1.5h-5.5z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="info-content">
                <h3>Current Version</h3>
                <div class="version-info">
                    <div class="version-item">
                        <span class="version-label">Version:</span>
                        <span class="version-value">{{ currentVersion }}</span>
                    </div>
                    <div class="version-item">
                        <span class="version-label">Last Updated:</span>
                        <span class="version-value">{{ formatDate(lastUpdated) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Available -->
        <div v-if="updateAvailable" class="update-card success">
            <div class="update-header">
                <h3>✨ Update Available</h3>
                <StatusBadge status="active" text="New Version" />
            </div>
            <div class="update-details">
                <div class="update-item">
                    <span class="update-label">New Version:</span>
                    <span class="update-value">{{ latestVersion }}</span>
                </div>
                <div class="update-item">
                    <span class="update-label">Release Date:</span>
                    <span class="update-value">{{ formatDate(releaseDate) }}</span>
                </div>
                <div class="update-item">
                    <span class="update-label">Changelog:</span>
                    <div class="changelog">
                        <ul>
                            <li v-for="(change, index) in changelog" :key="index">{{ change }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="update-actions">
                <button 
                    class="btn btn-primary btn-lg" 
                    @click="installUpdate"
                    :disabled="installing"
                >
                    <span v-if="installing">⏳</span>
                    <span v-else>📥</span>
                    {{ installing ? 'Installing Update...' : 'Install Update' }}
                </button>
                <button class="btn btn-outline" @click="viewChangelog">
                    📋 View Full Changelog
                </button>
            </div>
        </div>

        <!-- No Update Available -->
        <div v-else-if="!checking" class="update-card info">
            <div class="update-header">
                <h3>✅ System Up to Date</h3>
            </div>
            <p>You are running the latest version of the system. No updates are available at this time.</p>
        </div>

        <!-- Update Settings -->
        <div class="settings-card">
            <h3>Update Settings</h3>
            <div class="settings-form">
                <div class="form-group">
                    <label>
                        <input type="checkbox" v-model="settings.autoCheck" />
                        Automatically check for updates
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" v-model="settings.autoInstall" />
                        Automatically install minor updates
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

const checking = ref(false)
const installing = ref(false)
const updateAvailable = ref(false)
const currentVersion = ref('1.0.0')
const latestVersion = ref('1.1.0')
const lastUpdated = ref(new Date().toISOString())
const releaseDate = ref(new Date().toISOString())
const changelog = ref([
    'Bug fixes and performance improvements',
    'New features added',
    'Security updates'
])
const settings = ref({
    autoCheck: true,
    autoInstall: false
})

const checkForUpdates = async () => {
    checking.value = true
    try {
        // API call to check for updates
        await new Promise(resolve => setTimeout(resolve, 2000)) // Simulate
        updateAvailable.value = true
    } catch (error) {
        console.error('Error checking for updates:', error)
        alert('Failed to check for updates')
    } finally {
        checking.value = false
    }
}

const installUpdate = async () => {
    if (!confirm('Install system update? The system will be temporarily unavailable during installation.')) {
        return
    }
    
    installing.value = true
    try {
        // API call to install update
        await new Promise(resolve => setTimeout(resolve, 3000)) // Simulate
        currentVersion.value = latestVersion.value
        updateAvailable.value = false
        alert('Update installed successfully! Please refresh the page.')
    } catch (error) {
        console.error('Error installing update:', error)
        alert('Failed to install update')
    } finally {
        installing.value = false
    }
}

const viewChangelog = () => {
    alert('Full changelog will be displayed here')
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
    if (!dateString) return 'Never'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

onMounted(() => {
    // Auto check for updates if enabled
    if (settings.value.autoCheck) {
        checkForUpdates()
    }
})
</script>

<style scoped>
.update-page {
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

.btn-lg {
    padding: 16px 32px;
    font-size: 16px;
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
    margin: 0 0 16px 0;
}

.version-info {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.version-item {
    display: flex;
    gap: 12px;
}

.version-label {
    color: #64748b;
    font-weight: 500;
    min-width: 120px;
}

.version-value {
    color: #1e293b;
    font-weight: 600;
}

/* Update Card */
.update-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.update-card.success {
    border-left: 4px solid #10b981;
}

.update-card.info {
    border-left: 4px solid #06b6d4;
}

.update-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f3f4f6;
}

.update-header h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.update-details {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 24px;
}

.update-item {
    display: flex;
    gap: 12px;
}

.update-label {
    color: #64748b;
    font-weight: 500;
    min-width: 120px;
}

.update-value {
    color: #1e293b;
    font-weight: 600;
}

.changelog {
    margin-top: 8px;
}

.changelog ul {
    margin: 0;
    padding-left: 20px;
    color: #64748b;
}

.changelog li {
    margin-bottom: 8px;
}

.update-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.update-card.info p {
    color: #64748b;
    margin: 0;
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
    gap: 16px;
}

.form-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: #1e293b;
    font-weight: 500;
}

.form-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}
</style>
