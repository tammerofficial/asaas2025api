<template>
    <div class="media-library-page">
        <div class="page-header">
            <h2>🖼️ Media Library</h2>
            <div class="header-actions">
                <label class="btn-primary">
                    📤 Upload Files
                    <input type="file" multiple @change="handleFileUpload" style="display: none" />
                </label>
            </div>
        </div>

        <div class="filters-bar">
            <input v-model="searchQuery" type="text" placeholder="Search media..." class="search-input" />
        </div>

        <div v-if="loading" class="loading">Loading media...</div>
        <div v-else class="media-grid">
            <div v-for="item in media" :key="item.id" class="media-item">
                <div class="media-preview">
                    <img v-if="item.url && item.path" :src="item.url" :alt="item.title || item.alt" />
                    <div v-else class="file-icon">📄</div>
                </div>
                <div class="media-info">
                    <p class="media-title">{{ item.title || 'Untitled' }}</p>
                    <p class="media-size">{{ formatSize(item.size) }}</p>
                </div>
                <div class="media-actions">
                    <button @click="deleteMedia(item.id)" class="btn-sm btn-danger">Delete</button>
                </div>
            </div>
            <div v-if="media.length === 0" class="empty-state">No media files found</div>
        </div>

        <Toast v-model:show="showToast" :type="toastType" :title="toastTitle" :message="toastMessage" />
    </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import api from '../../services/api'
import Toast from '../../components/Toast.vue'

export default {
    name: 'MediaLibrary',
    components: { Toast },
    setup() {
        const loading = ref(false)
        const media = ref([])
        const searchQuery = ref('')
        const showToast = ref(false)
        const toastType = ref('success')
        const toastTitle = ref('')
        const toastMessage = ref('')

        const fetchMedia = async () => {
            loading.value = true
            try {
                const response = await api.media.list({ search: searchQuery.value })
                if (response.data.success) {
                    media.value = response.data.data || []
                }
            } catch (error) {
                console.error('Error fetching media:', error)
                showToastMessage('error', 'Error', 'Failed to load media')
                media.value = []
            } finally {
                loading.value = false
            }
        }

        const handleFileUpload = async (event) => {
            const files = event.target.files
            if (!files.length) return

            loading.value = true
            try {
                for (const file of files) {
                    const formData = new FormData()
                    formData.append('file', file)
                    await api.media.upload(formData)
                }
                showToastMessage('success', 'Success', `${files.length} file(s) uploaded successfully`)
                fetchMedia()
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to upload files')
            } finally {
                loading.value = false
            }
        }

        const deleteMedia = async (id) => {
            if (!confirm('Are you sure you want to delete this media file?')) return
            try {
                await api.media.delete(id)
                showToastMessage('success', 'Success', 'Media deleted successfully')
                fetchMedia()
            } catch (error) {
                showToastMessage('error', 'Error', 'Failed to delete media')
            }
        }

        const formatSize = (bytes) => {
            if (!bytes) return '-'
            const kb = bytes / 1024
            if (kb < 1024) return `${kb.toFixed(2)} KB`
            return `${(kb / 1024).toFixed(2)} MB`
        }

        const showToastMessage = (type, title, message) => {
            toastType.value = type
            toastTitle.value = title
            toastMessage.value = message
            showToast.value = true
        }

        watch(searchQuery, () => {
            fetchMedia()
        })

        onMounted(() => {
            fetchMedia()
        })

        return { loading, media, searchQuery, showToast, toastType, toastTitle, toastMessage, handleFileUpload, deleteMedia, formatSize }
    }
}
</script>

<style scoped>
.media-library-page {
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.filters-bar {
    margin-bottom: 20px;
}

.search-input {
    width: 100%;
    max-width: 300px;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
}

.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.media-item {
    background: white;
    border-radius: 8px;
    padding: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.media-preview {
    width: 100%;
    height: 150px;
    background: #f9fafb;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    overflow: hidden;
}

.media-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.file-icon {
    font-size: 48px;
}

.media-info {
    margin-bottom: 12px;
}

.media-title {
    font-size: 14px;
    font-weight: 500;
    color: #1e293b;
    margin: 0 0 4px 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.media-size {
    font-size: 12px;
    color: #64748b;
    margin: 0;
}

.media-actions {
    display: flex;
    gap: 8px;
}

.btn-sm {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    color: #9ca3af;
}
</style>



