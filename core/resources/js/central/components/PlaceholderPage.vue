<template>
    <div class="placeholder-page">
        <div class="placeholder-alert">
            <div class="alert-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                </svg>
            </div>
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
    flex-shrink: 0;
}

.alert-icon svg {
    width: 32px;
    height: 32px;
    color: #f59e0b;
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



