<template>
    <Modal :show="show" @update:show="$emit('update:show', $event)" size="small" :title="title">
        <p class="confirm-message">{{ message }}</p>
        <template #footer>
            <button class="btn-secondary" @click="$emit('update:show', false)">
                Cancel
            </button>
            <button class="btn-danger" @click="confirm">
                {{ confirmText }}
            </button>
        </template>
    </Modal>
</template>

<script>
import Modal from './Modal.vue'

export default {
    name: 'ConfirmDialog',
    components: {
        Modal
    },
    props: {
        show: {
            type: Boolean,
            required: true
        },
        title: {
            type: String,
            default: 'Confirm Action'
        },
        message: {
            type: String,
            required: true
        },
        confirmText: {
            type: String,
            default: 'Confirm'
        }
    },
    emits: ['update:show', 'confirm'],
    methods: {
        confirm() {
            this.$emit('confirm')
            this.$emit('update:show', false)
        }
    }
}
</script>

<style scoped>
.confirm-message {
    margin: 0;
    color: #374151;
    font-size: 14px;
    line-height: 1.5;
}

.btn-secondary {
    padding: 10px 20px;
    border: 1px solid #d1d5db;
    background: white;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background: #f9fafb;
}

.btn-danger {
    padding: 10px 20px;
    border: none;
    background: #ef4444;
    color: white;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-danger:hover {
    background: #dc2626;
}
</style>



