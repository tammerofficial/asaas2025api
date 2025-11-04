<template>
    <div class="loading-spinner" :class="{ 'full-screen': fullScreen }">
        <div class="spinner"></div>
        <p v-if="message" class="loading-message">{{ message }}</p>
    </div>
</template>

<script>
export default {
    name: 'LoadingSpinner',
    props: {
        fullScreen: {
            type: Boolean,
            default: false
        },
        message: {
            type: String,
            default: 'Loading...'
        }
    }
}
</script>

<style scoped>
.loading-spinner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.loading-spinner.full-screen {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    z-index: 9999;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f4f6;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

.loading-message {
    margin-top: 12px;
    color: #64748b;
    font-size: 14px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>



