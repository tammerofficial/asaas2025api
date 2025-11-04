<template>
    <div class="placeholder-page">
        <div class="placeholder-alert">
            <div class="alert-icon">⚠️</div>
            <div class="alert-content">
                <h3>Feature Under Development</h3>
                <p>This feature is currently available in Tenant API only.</p>
                <p>Central API endpoint needed: <code>{{ endpoint }}</code></p>
                <p class="note">Structure is ready for integration once API is available.</p>
            </div>
        </div>
        
        <div class="mock-interface" :style="{ opacity: 0.5 }">
            <slot name="mock">
                <div class="mock-content">
                    <div class="mock-header">
                        <h2>{{ title }}</h2>
                        <button class="mock-btn">Add New</button>
                    </div>
                    <div class="mock-table">
                        <div class="mock-row" v-for="i in 5" :key="i">
                            <div class="mock-cell">Item {{ i }}</div>
                            <div class="mock-cell">Status</div>
                            <div class="mock-cell">Actions</div>
                        </div>
                    </div>
                </div>
            </slot>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PlaceholderPage',
    props: {
        title: {
            type: String,
            default: 'Feature'
        },
        endpoint: {
            type: String,
            default: '/api/central/v1/{feature}'
        }
    }
}
</script>

<style scoped>
.placeholder-page {
    padding: 20px;
}

.placeholder-alert {
    background: #fef3c7;
    border: 1px solid #f59e0b;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    display: flex;
    gap: 16px;
}

.alert-icon {
    font-size: 32px;
    flex-shrink: 0;
}

.alert-content h3 {
    margin: 0 0 8px 0;
    color: #92400e;
    font-size: 18px;
}

.alert-content p {
    margin: 4px 0;
    color: #78350f;
    font-size: 14px;
}

.alert-content code {
    background: rgba(146, 64, 14, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 12px;
}

.note {
    font-style: italic;
    color: #a16207;
}

.mock-interface {
    background: white;
    border-radius: 8px;
    padding: 20px;
}

.mock-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e5e7eb;
}

.mock-header h2 {
    margin: 0;
    font-size: 20px;
    color: #1e293b;
}

.mock-btn {
    padding: 8px 16px;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: not-allowed;
}

.mock-table {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.mock-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 16px;
    padding: 12px;
    background: #f9fafb;
    border-radius: 6px;
}

.mock-cell {
    color: #9ca3af;
}
</style>



