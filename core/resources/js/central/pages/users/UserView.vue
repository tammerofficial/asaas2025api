<template>
    <div class="user-view-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">👁️ View User</h1>
                <p class="page-subtitle">User account details</p>
            </div>
            <div class="page-actions">
                <router-link :to="`/users/${user.id}/edit`" class="btn btn-primary">✏️ Edit</router-link>
                <router-link to="/users" class="btn btn-secondary">← Back to Users</router-link>
            </div>
        </div>

        <div v-if="loading" class="loading-overlay">
            <div class="spinner"></div>
            <p>Loading user...</p>
        </div>

        <div v-else class="user-content">
            <div class="user-card">
                <div class="user-header">
                    <h2 class="user-name">{{ user.name }}</h2>
                    <StatusBadge :status="user.status ? 'active' : 'inactive'" />
                </div>

                <div class="user-details">
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ user.email }}</span>
                    </div>
                    <div class="detail-row" v-if="user.username">
                        <span class="detail-label">Username:</span>
                        <span class="detail-value">{{ user.username }}</span>
                    </div>
                    <div class="detail-row" v-if="user.mobile">
                        <span class="detail-label">Mobile:</span>
                        <span class="detail-value">{{ user.mobile }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Created:</span>
                        <span class="detail-value">{{ formatDateTime(user.created_at) }}</span>
                    </div>
                    <div class="detail-row" v-if="user.updated_at">
                        <span class="detail-label">Updated:</span>
                        <span class="detail-value">{{ formatDateTime(user.updated_at) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import StatusBadge from '../../components/StatusBadge.vue'

const router = useRouter()
const route = useRoute()

const loading = ref(true)
const user = ref({})

const loadUser = async () => {
    loading.value = true
    try {
        const response = await axios.get(`/users/${route.params.id}`)
        if (response.data.success) {
            user.value = response.data.data
        }
    } catch (error) {
        console.error('Error loading user:', error)
        alert('Failed to load user')
        router.push('/users')
    } finally {
        loading.value = false
    }
}

const formatDateTime = (date) => {
    if (!date) return 'N/A'
    return new Date(date).toLocaleString('en-US')
}

onMounted(() => loadUser())
</script>

<style scoped>
.user-view-page { padding: 24px; max-width: 1200px; }
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
.page-title { font-size: 28px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0; }
.page-subtitle { color: #64748b; font-size: 15px; margin: 0; }
.page-actions { display: flex; gap: 12px; }
.loading-overlay { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px; color: #64748b; }
.spinner { width: 40px; height: 40px; border: 4px solid #f1f5f9; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 16px; }
@keyframes spin { to { transform: rotate(360deg); } }
.user-content { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); }
.user-card { }
.user-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb; }
.user-name { font-size: 32px; font-weight: 700; color: #1e293b; margin: 0; }
.user-details { }
.detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
.detail-label { font-weight: 600; color: #64748b; min-width: 150px; }
.detail-value { color: #1e293b; }
.btn { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.2s; font-size: 15px; }
.btn-primary { background: #3b82f6; color: white; }
.btn-primary:hover { background: #2563eb; }
.btn-secondary { background: #e5e7eb; color: #374151; }
.btn-secondary:hover { background: #d1d5db; }
</style>

