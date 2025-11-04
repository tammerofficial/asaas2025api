<template>
    <Teleport to="body">
        <Transition name="toast">
            <div v-if="show" class="toast" :class="type">
                <div class="toast-icon">
                    <span v-if="type === 'success'">✓</span>
                    <span v-else-if="type === 'error'">✕</span>
                    <span v-else-if="type === 'warning'">⚠</span>
                    <span v-else>ℹ</span>
                </div>
                <div class="toast-content">
                    <p class="toast-title">{{ title }}</p>
                    <p v-if="message" class="toast-message">{{ message }}</p>
                </div>
                <button class="toast-close" @click="close">×</button>
            </div>
        </Transition>
    </Teleport>
</template>

<script>
export default {
    name: 'Toast',
    props: {
        show: {
            type: Boolean,
            required: true
        },
        type: {
            type: String,
            default: 'info',
            validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
        },
        title: {
            type: String,
            required: true
        },
        message: {
            type: String,
            default: ''
        },
        duration: {
            type: Number,
            default: 3000
        }
    },
    emits: ['update:show', 'close'],
    watch: {
        show(newVal) {
            if (newVal && this.duration > 0) {
                setTimeout(() => {
                    this.close()
                }, this.duration)
            }
        }
    },
    methods: {
        close() {
            this.$emit('update:show', false)
            this.$emit('close')
        }
    }
}
</script>

<style scoped>
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 300px;
    max-width: 400px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: flex-start;
    padding: 16px;
    z-index: 10001;
    border-left: 4px solid;
}

.toast.success {
    border-color: #10b981;
}

.toast.error {
    border-color: #ef4444;
}

.toast.warning {
    border-color: #f59e0b;
}

.toast.info {
    border-color: #3b82f6;
}

.toast-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
    margin-right: 12px;
    flex-shrink: 0;
}

.toast.success .toast-icon {
    background: #dcfce7;
    color: #166534;
}

.toast.error .toast-icon {
    background: #fee2e2;
    color: #991b1b;
}

.toast.warning .toast-icon {
    background: #fef3c7;
    color: #92400e;
}

.toast.info .toast-icon {
    background: #dbeafe;
    color: #1e40af;
}

.toast-content {
    flex: 1;
}

.toast-title {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

.toast-message {
    margin: 0;
    font-size: 13px;
    color: #64748b;
}

.toast-close {
    background: none;
    border: none;
    font-size: 20px;
    color: #9ca3af;
    cursor: pointer;
    padding: 0;
    margin-left: 12px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s;
}

.toast-close:hover {
    color: #374151;
}

/* Transitions */
.toast-enter-active,
.toast-leave-active {
    transition: transform 0.3s, opacity 0.3s;
}

.toast-enter-from {
    transform: translateX(100%);
    opacity: 0;
}

.toast-leave-to {
    transform: translateX(100%);
    opacity: 0;
}
</style>



