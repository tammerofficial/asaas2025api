<template>
    <Teleport to="body">
        <Transition name="toast">
            <div v-if="show" class="toast" :class="type">
                <div class="toast-icon">
                    <svg v-if="type === 'success'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <svg v-else-if="type === 'error'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                    </svg>
                    <svg v-else-if="type === 'warning'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="toast-content">
                    <p class="toast-title">{{ title }}</p>
                    <p v-if="message" class="toast-message">{{ message }}</p>
                </div>
                <button class="toast-close" @click="close">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                    </svg>
                </button>
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
    margin-right: 12px;
    flex-shrink: 0;
}

.toast-icon svg {
    width: 20px;
    height: 20px;
}

.toast.success .toast-icon {
    background: #dcfce7;
}

.toast.success .toast-icon svg {
    color: #166534;
}

.toast.error .toast-icon {
    background: #fee2e2;
}

.toast.error .toast-icon svg {
    color: #991b1b;
}

.toast.warning .toast-icon {
    background: #fef3c7;
}

.toast.warning .toast-icon svg {
    color: #92400e;
}

.toast.info .toast-icon {
    background: #dbeafe;
}

.toast.info .toast-icon svg {
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

.toast-close svg {
    width: 16px;
    height: 16px;
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
