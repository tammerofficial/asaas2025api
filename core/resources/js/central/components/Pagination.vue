<template>
    <div class="pagination">
        <div class="pagination-info">
            Showing {{ from }} to {{ to }} of {{ total }} results
        </div>
        <div class="pagination-controls">
            <button
                class="pagination-btn"
                :disabled="currentPage === 1"
                @click="$emit('update:currentPage', currentPage - 1)"
            >
                Previous
            </button>
            
            <div class="pagination-numbers">
                <button
                    v-for="page in visiblePages"
                    :key="page"
                    class="pagination-number"
                    :class="{ active: page === currentPage }"
                    @click="$emit('update:currentPage', page)"
                >
                    {{ page }}
                </button>
            </div>
            
            <button
                class="pagination-btn"
                :disabled="currentPage === totalPages"
                @click="$emit('update:currentPage', currentPage + 1)"
            >
                Next
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Pagination',
    props: {
        currentPage: {
            type: Number,
            required: true
        },
        totalPages: {
            type: Number,
            required: true
        },
        total: {
            type: Number,
            required: true
        },
        perPage: {
            type: Number,
            default: 20
        }
    },
    emits: ['update:currentPage'],
    computed: {
        from() {
            return (this.currentPage - 1) * this.perPage + 1
        },
        to() {
            const to = this.currentPage * this.perPage
            return to > this.total ? this.total : to
        },
        visiblePages() {
            const pages = []
            const maxVisible = 5
            let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2))
            let end = Math.min(this.totalPages, start + maxVisible - 1)
            
            if (end - start < maxVisible - 1) {
                start = Math.max(1, end - maxVisible + 1)
            }
            
            for (let i = start; i <= end; i++) {
                pages.push(i)
            }
            
            return pages
        }
    }
}
</script>

<style scoped>
.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding: 16px 0;
}

.pagination-info {
    color: #6b7280;
    font-size: 14px;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.pagination-btn {
    padding: 8px 16px;
    border: 1px solid #d1d5db;
    background: white;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
}

.pagination-btn:hover:not(:disabled) {
    background: #f9fafb;
    border-color: #3b82f6;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-numbers {
    display: flex;
    gap: 4px;
}

.pagination-number {
    width: 36px;
    height: 36px;
    border: 1px solid #d1d5db;
    background: white;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
}

.pagination-number:hover {
    background: #f9fafb;
    border-color: #3b82f6;
}

.pagination-number.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}
</style>



