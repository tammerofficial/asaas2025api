
<style>
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    .icon-container{
        position: absolute;
        top: 20px;
        left: 50%;
    }

    .loading-icon {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        border: 0.55rem solid #ddd;
        border-top-color: #333;
        display: inline-block;
        margin: 0 8px;

        -webkit-animation-name: spin;
        -webkit-animation-duration: 1s;
        -webkit-animation-iteration-count: infinite;

        animation-name: spin;
        animation-duration: 1s;
        animation-iteration-count: infinite;
    }

    .full-circle {
        -webkit-animation-timing-function: cubic-bezier(0.6, 0, 0.4, 1);
        animation-timing-function: cubic-bezier(0.6, 0, 0.4, 1);
    }

    @media screen and (max-width: 700px) {
        .container {
            width: 100%;
        }
    }
    .disabled {
        pointer-events: none;
        opacity: 0.5;
    }
    .creation-progress-bar {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
    }
    /* Add these styles to your CSS */
    .step.completed .step-number {
        background: linear-gradient(135deg, #0d6efd, #0d6efd) !important;
        color: white !important;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .step.completed .step-label {
        color: #0d6efd !important;
        font-weight: 600 !important;
    }
    /* Green progress line for completed steps */
    .progress-steps::before {
        content: '';
        position: absolute;
        top: 12px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(to right,
        #0d6efd 0%,
        #0d6efd calc((var(--completed-steps) / var(--total-steps)) * 100%),
        #dee2e6 calc((var(--completed-steps) / var(--total-steps)) * 100%),
        #dee2e6 100%);
        z-index: 1;
        transition: all 0.3s ease;
    }

    /* Green background for completed step cards */
    .tab-pane.completed {
        border-left: 4px solid #0d6efd;
        background: linear-gradient(to right, rgba(40, 167, 69, 0.03), transparent);
    }

    /* Success state for form fields in completed steps */
    .tab-pane.completed .form--control:valid {
        border-color: #0d6efd;
        background-color: rgba(40, 167, 69, 0.05);
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .step-number {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: #dee2e6;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .step.active .step-number {
        background: #0d6efd;
        color: white;
    }

    .step-label {
        font-size: 12px;
        color: #6c757d;
    }

    .step.active .step-label {
        color: #0d6efd;
        font-weight: 500;
    }

    .variant-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        background: #f8f9fa;
    }

    .variant-header {
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        /*color: #2c3e50;*/
        border-bottom: 1px solid #666;
        padding-bottom: 10px;
    }

    .required-field {
        border-left: 3px solid #dc3545 !important;
    }

    .navigation-buttons {
        position: sticky;
        bottom: 20px;
        padding-top: 15px;
        border-radius: 8px;
    }
    @media (max-width: 768px) {
        .progress-steps {
            flex-direction: column;
            gap: 15px;
        }

        .progress-steps::before {
            display: none;
        }

        .step {
            flex-direction: row;
            gap: 10px;
        }

        .variant-card {
            padding: 15px;
        }

        .form-row {
            flex-direction: column;
        }
    }
    /* Error message styling */
    .error-message {
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }

    .is-invalid {
        border-color: #dc3545 !important;
        background-color: #fff8f8;
    }

    .is-valid {
        border-color: #0d6efd !important;
        background-color: #f8fff8;
    }

    /* Required field indicators */
    .required-field::after {
        content: '*';
        color: #dc3545;
        margin-left: 4px;
    }

    /* Validation states for select2 */
    .select2-container--default .select2-selection--single.is-invalid {
        border-color: #dc3545 !important;
    }

    /* Media upload validation state */
    .media-upload-btn-wrapper.has-error .media_upload_form_btn {
        border-color: #dc3545 !important;
    }

    /* Animation for error messages */
    .error-message {
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Highlight invalid steps in progress bar */
    .step.has-errors .step-number {
        background: #dc3545 !important;
        color: white !important;
        animation: pulseError 2s infinite;
    }

    @keyframes pulseError {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
</style>
{{-- Styles for Custom Specifications --}}
<style>
    .custom_specification_item .form-control:focus {
        /*border-color: #0d6efd;*/
        border: 1px solid rgba(255,128,93,0.26);
    }
    .add_custom_spec, .remove_custom_spec {
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
</style>
{{--    style for slug field --}}
<style>
    .slug-field-wrapper {
        position: relative;
    }

    .slug-display {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        /*border-radius: 0.375rem;*/
        /*padding: 0.375rem 2.5rem 0.375rem 0.75rem;*/
        padding:15px;
        color: #6c757d;
        cursor: not-allowed;
    }

    .slug-edit-icon {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        /*padding: 4px;*/
        /*border-radius: 4px;*/
        transition: all 0.2s ease;
    }

    .slug-edit-icon:hover {
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
    }

    .slug-field-wrapper.editing .slug-display {
        display: none;
    }

    .slug-field-wrapper.editing .slug-input {
        display: block !important;
    }

    .slug-input {
        /* Remove custom styles - let it inherit from .form-control */
    }

    .slug-actions {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }

    .slug-field-wrapper.editing .slug-actions {
        display: flex;
    }

    .slug-field-wrapper.editing .slug-edit-icon {
        display: none;
    }

    .slug-action-btn {
        background: none;
        border: none;
        padding: 2px 4px;
        margin-left: 2px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .slug-action-btn.save {
        color: #198754;
    }

    .slug-action-btn.save:hover {
        background-color: rgba(25, 135, 84, 0.1);
    }

    .slug-action-btn.cancel {
        color: #dc3545;
    }

    .slug-action-btn.cancel:hover {
        background-color: rgba(220, 53, 69, 0.1);
    }

    .slug-preview {
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
    }

    .slug-preview strong {
        color: #0d6efd;
    }
    .note-editor.is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }
</style>
