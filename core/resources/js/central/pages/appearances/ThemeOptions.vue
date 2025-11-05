<template>
    <div class="theme-options-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">🎨 Theme Options</h1>
                <p class="page-subtitle">Customize theme settings and appearance</p>
            </div>
            <button class="btn btn-primary" @click="saveOptions">
                💾 Save Changes
            </button>
        </div>

        <!-- Theme Options Form -->
        <div class="options-container">
            <div class="options-sidebar">
                <div class="sidebar-section">
                    <h3>Settings Sections</h3>
                    <nav class="sidebar-nav">
                        <a 
                            v-for="section in sections" 
                            :key="section.id"
                            :href="`#${section.id}`"
                            @click.prevent="activeSection = section.id"
                            :class="{ active: activeSection === section.id }"
                            class="nav-item"
                        >
                            {{ section.icon }} {{ section.name }}
                        </a>
                    </nav>
                </div>
            </div>

            <div class="options-content">
                <div v-for="section in sections" :key="section.id" v-show="activeSection === section.id" class="options-section">
                    <h2 class="section-title">{{ section.icon }} {{ section.name }}</h2>
                    <div class="section-content">
                        <div v-for="option in section.options" :key="option.key" class="form-group">
                            <label :for="option.key">{{ option.label }}</label>
                            <input 
                                v-if="option.type === 'text' || option.type === 'color'"
                                :type="option.type"
                                :id="option.key"
                                v-model="options[option.key]"
                                :placeholder="option.placeholder"
                                class="form-input"
                            />
                            <textarea 
                                v-else-if="option.type === 'textarea'"
                                :id="option.key"
                                v-model="options[option.key]"
                                :placeholder="option.placeholder"
                                class="form-textarea"
                                rows="4"
                            ></textarea>
                            <select 
                                v-else-if="option.type === 'select'"
                                :id="option.key"
                                v-model="options[option.key]"
                                class="form-select"
                            >
                                <option v-for="opt in option.options" :key="opt.value" :value="opt.value">
                                    {{ opt.label }}
                                </option>
                            </select>
                            <div v-else-if="option.type === 'checkbox'" class="checkbox-group">
                                <label>
                                    <input 
                                        type="checkbox" 
                                        :id="option.key"
                                        v-model="options[option.key]"
                                    />
                                    {{ option.checkboxLabel || 'Enable' }}
                                </label>
                            </div>
                            <p v-if="option.description" class="form-description">{{ option.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

const activeSection = ref('general')
const options = ref({})

const sections = ref([
    {
        id: 'general',
        name: 'General',
        icon: '⚙️',
        options: [
            { key: 'site_name', label: 'Site Name', type: 'text', placeholder: 'Enter site name' },
            { key: 'site_description', label: 'Site Description', type: 'textarea', placeholder: 'Enter site description' },
            { key: 'site_logo', label: 'Site Logo URL', type: 'text', placeholder: 'Enter logo URL' },
        ]
    },
    {
        id: 'colors',
        name: 'Colors',
        icon: '🎨',
        options: [
            { key: 'primary_color', label: 'Primary Color', type: 'color', description: 'Main brand color' },
            { key: 'secondary_color', label: 'Secondary Color', type: 'color', description: 'Secondary brand color' },
            { key: 'background_color', label: 'Background Color', type: 'color', description: 'Background color' },
        ]
    },
    {
        id: 'typography',
        name: 'Typography',
        icon: '📝',
        options: [
            { key: 'font_family', label: 'Font Family', type: 'select', options: [
                { value: 'Arial', label: 'Arial' },
                { value: 'Helvetica', label: 'Helvetica' },
                { value: 'Times New Roman', label: 'Times New Roman' },
                { value: 'Georgia', label: 'Georgia' },
            ]},
            { key: 'font_size', label: 'Font Size', type: 'select', options: [
                { value: '14px', label: '14px' },
                { value: '16px', label: '16px' },
                { value: '18px', label: '18px' },
            ]},
        ]
    },
    {
        id: 'layout',
        name: 'Layout',
        icon: '📐',
        options: [
            { key: 'header_style', label: 'Header Style', type: 'select', options: [
                { value: 'default', label: 'Default' },
                { value: 'minimal', label: 'Minimal' },
                { value: 'centered', label: 'Centered' },
            ]},
            { key: 'sidebar_position', label: 'Sidebar Position', type: 'select', options: [
                { value: 'left', label: 'Left' },
                { value: 'right', label: 'Right' },
                { value: 'none', label: 'None' },
            ]},
            { key: 'enable_footer', label: 'Enable Footer', type: 'checkbox', checkboxLabel: 'Show footer on all pages' },
        ]
    }
])

const loadOptions = async () => {
    try {
        const response = await api.appearances.themeOptions()
        if (response.data.success && response.data.data) {
            options.value = response.data.data
        } else {
            // Initialize with default values if no data
            sections.value.forEach(section => {
                section.options.forEach(option => {
                    if (option.type === 'checkbox') {
                        options.value[option.key] = false
                    } else {
                        options.value[option.key] = ''
                    }
                })
            })
        }
    } catch (error) {
        console.error('Error loading theme options:', error)
        // Initialize with default values on error
        sections.value.forEach(section => {
            section.options.forEach(option => {
                if (option.type === 'checkbox') {
                    options.value[option.key] = false
                } else {
                    options.value[option.key] = ''
                }
            })
        })
    }
}

const saveOptions = async () => {
    try {
        const response = await api.appearances.updateThemeOptions(options.value)
        if (response.data.success) {
            alert('Theme options saved successfully')
        }
    } catch (error) {
        console.error('Error saving theme options:', error)
        alert('Failed to save theme options')
    }
}

onMounted(() => {
    loadOptions()
})
</script>

<style scoped>
.theme-options-page {
    padding: 24px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.page-subtitle {
    color: #64748b;
    font-size: 15px;
    margin: 0;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

/* Options Container */
.options-container {
    display: flex;
    gap: 24px;
}

.options-sidebar {
    width: 250px;
    flex-shrink: 0;
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    height: fit-content;
}

.sidebar-section h3 {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 16px 0;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.nav-item {
    padding: 12px 16px;
    border-radius: 8px;
    text-decoration: none;
    color: #64748b;
    font-weight: 500;
    transition: all 0.2s;
}

.nav-item:hover {
    background: #f8fafc;
    color: #3b82f6;
}

.nav-item.active {
    background: #3b82f6;
    color: white;
}

.options-content {
    flex: 1;
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.section-title {
    font-size: 24px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 24px 0;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}

.section-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 600;
    color: #1e293b;
    font-size: 14px;
}

.form-input,
.form-textarea,
.form-select {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
    font-family: inherit;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-textarea {
    resize: vertical;
}

.form-input[type="color"] {
    height: 50px;
    cursor: pointer;
}

.checkbox-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 500;
}

.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.form-description {
    color: #64748b;
    font-size: 13px;
    margin: 0;
}
</style>
