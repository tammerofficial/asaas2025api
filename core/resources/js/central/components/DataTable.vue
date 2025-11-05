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
                                <svg v-if="sortColumn === column.key && sortOrder === 'asc'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd"/>
                                </svg>
                                <svg v-else-if="sortColumn === column.key && sortOrder === 'desc'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd"/>
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M2.24 6.8a.75.75 0 001.06-.04l1.95-2.1v8.59a.75.75 0 001.5 0V4.66l1.95 2.1a.75.75 0 101.1-1.02l-3.25-3.5a.75.75 0 00-1.1 0L2.2 5.74a.75.75 0 00.04 1.06zm8 6.4a.75.75 0 00-.04 1.06l3.25 3.5a.75.75 0 001.1 0l3.25-3.5a.75.75 0 10-1.1-1.02l-1.95 2.1V6.75a.75.75 0 00-1.5 0v8.59l-1.95-2.1a.75.75 0 00-1.06-.04z" clip-rule="evenodd"/>
                                </svg>
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
    padding: 0;
    transition: color 0.2s;
    display: flex;
    align-items: center;
}

.sort-btn svg {
    width: 14px;
    height: 14px;
}

.sort-btn:hover {
    color: #7f1625;
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



