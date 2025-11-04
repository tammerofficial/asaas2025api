<template>
    <div class="data-table">
        <div v-if="loading" class="table-loading">
            <LoadingSpinner :message="loadingMessage" />
        </div>
        
        <table class="table" v-else>
            <thead>
                <tr>
                    <th v-for="column in columns" :key="column.key" :class="column.class">
                        <div class="th-content">
                            <span>{{ column.label }}</span>
                            <button
                                v-if="column.sortable"
                                class="sort-btn"
                                @click="sort(column.key)"
                            >
                                <span v-if="sortColumn === column.key">
                                    {{ sortOrder === 'asc' ? '↑' : '↓' }}
                                </span>
                                <span v-else>⇅</span>
                            </button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="data.length === 0">
                    <td :colspan="columns.length" class="empty-state">
                        {{ emptyMessage }}
                    </td>
                </tr>
                <tr v-else v-for="(row, index) in data" :key="index">
                    <td v-for="column in columns" :key="column.key" :class="column.class">
                        <slot :name="`cell-${column.key}`" :row="row" :value="row[column.key]">
                            {{ formatValue(row[column.key], column) }}
                        </slot>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <Pagination
            v-if="pagination && totalPages > 1"
            :current-page="currentPage"
            :total-pages="totalPages"
            :total="total"
            :per-page="perPage"
            @update:current-page="$emit('update:current-page', $event)"
        />
    </div>
</template>

<script>
import LoadingSpinner from './LoadingSpinner.vue'
import Pagination from './Pagination.vue'

export default {
    name: 'DataTable',
    components: {
        LoadingSpinner,
        Pagination
    },
    props: {
        columns: {
            type: Array,
            required: true
        },
        data: {
            type: Array,
            default: () => []
        },
        loading: {
            type: Boolean,
            default: false
        },
        loadingMessage: {
            type: String,
            default: 'Loading...'
        },
        emptyMessage: {
            type: String,
            default: 'No data available'
        },
        pagination: {
            type: Boolean,
            default: false
        },
        currentPage: {
            type: Number,
            default: 1
        },
        totalPages: {
            type: Number,
            default: 1
        },
        total: {
            type: Number,
            default: 0
        },
        perPage: {
            type: Number,
            default: 20
        },
        sortColumn: {
            type: String,
            default: ''
        },
        sortOrder: {
            type: String,
            default: 'asc',
            validator: (value) => ['asc', 'desc'].includes(value)
        }
    },
    emits: ['update:current-page', 'sort'],
    methods: {
        sort(column) {
            let order = 'asc'
            if (this.sortColumn === column && this.sortOrder === 'asc') {
                order = 'desc'
            }
            this.$emit('sort', { column, order })
        },
        formatValue(value, column) {
            if (column.formatter && typeof column.formatter === 'function') {
                return column.formatter(value)
            }
            return value ?? '-'
        }
    }
}
</script>

<style scoped>
.data-table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead {
    background: #f9fafb;
}

.table th {
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.th-content {
    display: flex;
    align-items: center;
    gap: 8px;
}

.sort-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: #9ca3af;
    font-size: 12px;
    padding: 0;
    transition: color 0.2s;
}

.sort-btn:hover {
    color: #3b82f6;
}

.table tbody tr {
    border-bottom: 1px solid #e5e7eb;
    transition: background 0.2s;
}

.table tbody tr:hover {
    background: #f9fafb;
}

.table td {
    padding: 12px 16px;
    font-size: 14px;
    color: #374151;
}

.empty-state {
    text-align: center;
    padding: 40px !important;
    color: #9ca3af;
}

.table-loading {
    padding: 40px;
}
</style>



