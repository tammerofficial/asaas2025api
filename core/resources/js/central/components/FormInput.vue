<template>
    <div class="form-group">
        <label v-if="label" :for="id" class="form-label">
            {{ label }}
            <span v-if="required" class="required">*</span>
        </label>
        <input
            :id="id"
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            :required="required"
            :disabled="disabled"
            :class="['form-input', { 'has-error': error }]"
            @input="$emit('update:modelValue', $event.target.value)"
            @blur="$emit('blur')"
        />
        <span v-if="error" class="error-message">{{ error }}</span>
        <span v-if="hint && !error" class="hint">{{ hint }}</span>
    </div>
</template>

<script>
export default {
    name: 'FormInput',
    props: {
        modelValue: {
            type: [String, Number],
            default: ''
        },
        label: {
            type: String,
            default: ''
        },
        type: {
            type: String,
            default: 'text'
        },
        placeholder: {
            type: String,
            default: ''
        },
        required: {
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        },
        error: {
            type: String,
            default: ''
        },
        hint: {
            type: String,
            default: ''
        },
        id: {
            type: String,
            default: () => `input-${Math.random().toString(36).substr(2, 9)}`
        }
    },
    emits: ['update:modelValue', 'blur']
}
</script>

<style scoped>
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
}

.required {
    color: #ef4444;
}

.form-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s;
    background: white;
}

.form-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-input:disabled {
    background: #f9fafb;
    color: #9ca3af;
    cursor: not-allowed;
}

.form-input.has-error {
    border-color: #ef4444;
}

.form-input.has-error:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.error-message {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #ef4444;
}

.hint {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #6b7280;
}
</style>



