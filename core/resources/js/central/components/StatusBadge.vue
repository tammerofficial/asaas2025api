<template>
    <span class="status-badge" :class="badgeClass">
        {{ text || status }}
    </span>
</template>

<script>
export default {
    name: 'StatusBadge',
    props: {
        status: {
            type: String,
            required: true
        },
        text: {
            type: String,
            default: null
        }
    },
    computed: {
        badgeClass() {
            const statusMap = {
                active: 'status-active',
                inactive: 'status-inactive',
                pending: 'status-pending',
                completed: 'status-completed',
                cancelled: 'status-cancelled',
                success: 'status-success',
                failed: 'status-failed',
                published: 'status-published',
                draft: 'status-draft',
                enabled: 'status-enabled',
                disabled: 'status-disabled'
            }
            return statusMap[this.status.toLowerCase()] || 'status-default'
        }
    }
}
</script>

<style scoped>
.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
}

.status-active,
.status-success,
.status-published,
.status-enabled,
.status-completed {
    background: #dcfce7;
    color: #166534;
}

.status-inactive,
.status-disabled,
.status-draft {
    background: #f3f4f6;
    color: #6b7280;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-cancelled,
.status-failed {
    background: #fee2e2;
    color: #991b1b;
}

.status-default {
    background: #e0e7ff;
    color: #3730a3;
}
</style>



