@extends('tenant.admin.admin-master')
@section('title')
    {!! __('Edit Product').' - '.$product->name !!}
@endsection
@section('site-title')
    {{__('Edit Product')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/tenant/backend/css/bootstrap-taginput.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/common/css/select2.min.css')}}">
    <x-media-upload.css/>
    <x-summernote.css/>
    <x-product::variant-info.css/>
    @include('product::components.product-css')


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
            color: #2c3e50;
            border-bottom: 2px solid #0d6efd;
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
@endsection
@section('content')
    @php
        $subCat = $product?->subCategory?->id ?? null;
        $childCat = $product?->childCategory?->pluck("id")->toArray() ?? null;
        $cat = $product?->category?->id ?? null;
        $selectedDeliveryOption = $product?->delivery_option?->pluck("delivery_option_id")?->toArray() ?? [];
    @endphp
    <div class="dashboard-top-contents">
        <div class="row">
            <div class="col-lg-12">
                <div class="top-inner-contents search-area top-searchbar-wrapper">
                    <div class="dashboard-flex-contetns">
                        <div class="dashboard-left-flex">
                            <h3 class="heading-three fw-500"> {{ __("Edit Products") }} </h3>
                        </div>
                        <div class="dashboard-right-flex">
                            <div class="top-search-input">
                                <a class="btn btn-info btn-sm px-4"
                                   target="_blank"
                                   href="{{ route('tenant.dynamic.page', $product->slug) }}">{{__('View')}}
                                </a>
                                <a class="btn btn-info btn-sm px-4" href="{{route('tenant.admin.product.all')}}">{{__('Back')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-products-add bg-white radius-20 mt-4">
    <div class="row g-4">
    <div class="col-md-12">
        <div class="creation-progress-bar">
            <div class="progress-steps">
                <div class="step completed" data-step="1">
                    <span class="step-number">1</span>
                    <span class="step-label">Basic Info</span>
                </div>
                <div class="step" data-step="2">
                    <span class="step-number">2</span>
                    <span class="step-label">Pricing</span>
                </div>
                <div class="step" data-step="3">
                    <span class="step-number">3</span>
                    <span class="step-label">Inventory</span>
                </div>
                <div class="step" data-step="4">
                    <span class="step-number">4</span>
                    <span class="step-label">Media</span>
                </div>
                <div class="step" data-step="5">
                    <span class="step-number">5</span>
                    <span class="step-label">Categories</span>
                </div>
                <div class="step" data-step="6">
                    <span class="step-number">6</span>
                    <span class="step-label">Settings</span>
                </div>
                <div class="step" data-step="7">
                    <span class="step-number">✓</span>
                    <span class="step-label">Finalize</span>
                </div>
            </div>
        </div>
        <form data-request-route="{{ route("tenant.admin.product.edit", $product->id) }}" method="post"
              id="product-edit-form">
            @csrf
            <input name="id" type="hidden" value="{{ $product?->id }}">

            <div class="tab-content mt-2">
                {{-- Step 1: Basic Information --}}
                <div class="tab-pane fade show active" id="step-basic" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <x-product::general-info :brands="$data['brands']" :product="$product"/>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Pricing --}}
                <div class="tab-pane fade" id="step-pricing" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <x-product::product-price :product="$product" :taxClasses="$data['tax_classes']"/>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Inventory & Variants --}}
                <div class="tab-pane fade" id="step-inventory" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <x-product::product-inventory :units="$data['units']"
                                                          :inventory="$product?->inventory"
                                                          :uom="$product?->uom"/>
                            <x-product::product-attribute
                                :inventorydetails="$product?->inventory?->inventoryDetails"
                                :colors="$data['product_colors']"
                                :sizes="$data['product_sizes']"
                                :allAttributes="$data['all_attribute']"
                            :product="$product"/>
                        </div>
                    </div>
                </div>

                {{-- Step 4: Media --}}
                <div class="tab-pane fade" id="step-media" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <x-product::product-image :product="$product"/>
                        </div>
                    </div>
                </div>

                {{-- Step 5: Categories & Delivery --}}
                <div class="tab-pane fade" id="step-categories" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <x-product::categories :sub_categories="$sub_categories"
                                                   :categories="$data['categories']"
                                                   :child_categories="$child_categories"
                                                   :selected_child_cat="$childCat" :selected_sub_cat="$subCat"
                                                   :selectedcat="$cat"/>
                            <x-product::delivery-option :selected_delivery_option="$selectedDeliveryOption"
                                                        :deliveryOptions="$data['deliveryOptions']"/>
                        </div>
                    </div>
                </div>

                {{-- Step 6: Settings --}}
                <div class="tab-pane fade" id="step-setting" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <x-product::settings :product="$product"/>
                            <x-product::policy :product="$product"/>
                        </div>
                    </div>
                </div>

                {{-- Step 7: Finalize --}}
                <div class="tab-pane fade" id="step-final" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <x-product::tags-and-badge :badges="$data['badges']" :tag="$product?->tag"
                                                       :singlebadge="$product?->badge_id"/>
                            <x-product::meta-seo :meta_data="$product->metaData"/>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <script src="{{ global_asset('assets/common/js/jquery-ui.min.js') }}" rel="stylesheet"></script>
    <script src="{{global_asset('assets/tenant/backend/js/bootstrap-taginput.min.js')}}"></script>
    <script src="{{global_asset('assets/common/js/select2.min.js')}}"></script>
    <script src="{{global_asset('assets/common/js/slugify.js')}}"></script>
            @include('product::components.product-js')


    <x-media-upload.js/>
    <x-summernote.js/>
    <x-product::variant-info.js :colors="$data['product_colors']" :sizes="$data['product_sizes']"
                                :all-attributes="$data['all_attribute']"/>
    <x-unique-checker user="tenant" selector="input[name=sku]" table="product_inventories" column="sku"/>

    <script>
        $(document).ready(function() {
            let currentStep = 1;
            const totalSteps = 7;
            let completedSteps = new Set();
            let stepErrors = new Map(); // Track errors for each step
            let temp = false;

            // Define required fields and validation rules for each step
            const stepRequirements = {
                1: { // Basic Info
                    required: ['name', 'slug', 'description'],
                    custom: ['slug_format', 'name_length']
                },
                2: { // Pricing
                    required: ['sale_price'],
                    custom: ['price_validation', 'tax_validation']
                },
                3: { // Inventory
                    required: ['sku', 'uom', 'unit_id'],
                    custom: ['sku_unique', 'stock_validation']
                },
                4: { // Media
                    required: ['image_id'],
                    custom: ['image_validation']
                },
                5: { // Categories
                    required: ['category_id'],
                    custom: ['category_validation']
                },
                6: { // Settings
                    required: [],
                    custom: ['settings_validation']
                },
                7: { // Final
                    required: [],
                    custom: ['meta_validation']
                }
            };

            updateProgress();
            setupRealTimeValidation();

            function setupRealTimeValidation() {
                // Real-time validation for all form inputs
                $(document).on('input blur change', '.form--control, input, select, textarea', function() {
                    const field = $(this);
                    const fieldName = field.attr('name');
                    const currentStepPane = field.closest('.tab-pane');
                    const stepId = currentStepPane.attr('id');
                    const stepNumber = getStepNumberFromId(stepId);

                    if (stepNumber) {
                        validateField(field, stepNumber);
                        updateStepStatus(stepNumber);
                        updateNavigationState();
                    }

                    // Set temp flag for unsaved changes
                    temp = true;
                });

                // Validate on select2 change
                $(document).on('select2:select select2:unselect', '.select2', function() {
                    const field = $(this);
                    const stepNumber = getStepNumberFromId(field.closest('.tab-pane').attr('id'));
                    if (stepNumber) {
                        setTimeout(() => {
                            validateField(field, stepNumber);
                            updateStepStatus(stepNumber);
                            updateNavigationState();
                        }, 100);
                    }
                    temp = true;
                });

                // Validate media upload
                $(document).on('click', '.media_upload_form_btn', function() {
                    const stepNumber = getStepNumberFromId($(this).closest('.tab-pane').attr('id'));
                    if (stepNumber) {
                        setTimeout(() => {
                            validateStep(stepNumber, false);
                            updateStepStatus(stepNumber);
                            updateNavigationState();
                        }, 500);
                    }
                });
            }

            function validateField(field, stepNumber) {
                const fieldName = field.attr('name');
                const value = field.val();

                // Clear previous errors for this field
                clearFieldError(field);

                // Check if field is required for this step
                const requirements = stepRequirements[stepNumber];
                if (requirements && requirements.required.includes(fieldName)) {
                    if (!value || value.trim() === '') {
                        showFieldError(field, `${getFieldLabel(fieldName)} is required`);
                        return false;
                    }
                }

                // Custom field validation
                return validateCustomField(field, fieldName, value, stepNumber);
            }

            function validateCustomField(field, fieldName, value, stepNumber) {
                switch (fieldName) {
                    case 'name':
                        if (value && value.length < 3) {
                            showFieldError(field, 'Product name must be at least 3 characters long');
                            return false;
                        }
                        if (value && value.length > 191) {
                            showFieldError(field, 'Product name cannot exceed 191 characters');
                            return false;
                        }
                        break;

                    // case 'slug':
                    //     if (value && !/^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(value)) {
                    //         showFieldError(field, 'Slug can only contain lowercase letters, numbers, and hyphens');
                    //         return false;
                    //     }
                    //     break;
                    case 'slug':
                        if (value && !/^[\p{L}\p{N}]+(?:-[\p{L}\p{N}]+)*$/u.test(value)) {
                            showFieldError(field, 'Slug can only contain letters, numbers, and hyphens (any language)');
                            return false;
                        }
                        break;

                    case 'description':
                        if (value && value.length < 10) {
                            showFieldError(field, 'Description must be at least 10 characters long');
                            return false;
                        }
                        break;

                    case 'price':
                        if (value && (isNaN(value) || parseFloat(value) <= 0)) {
                            showFieldError(field, 'Price must be a valid positive number');
                            return false;
                        }
                        break;

                    case 'sale_price':

                        const regularPrice = parseFloat($('input[name="price"]').val()) || 0;

                       if(regularPrice){
                           if (value && parseFloat(value) >= regularPrice) {
                               showFieldError(field, 'Sale price must be less than regular price');
                               return false;
                           }
                       }
                        break;

                    case 'sku':
                        if (value && value.length < 2) {
                            showFieldError(field, 'SKU must be at least 2 characters long');
                            return false;
                        }
                        break;

                    case 'uom':
                        if (value && (isNaN(value) || parseFloat(value) <= 0)) {
                            showFieldError(field, 'Unit of measurement must be a positive number');
                            return false;
                        }
                        break;
                }

                // Tax validation
                if (fieldName === 'tax_class') {
                    const isTaxable = $('select[name="is_taxable"]').val();
                    if (isTaxable === 'yes' && (!value || value === '')) {
                        showFieldError(field, 'Tax class is required when product is taxable');
                        return false;
                    }
                }

                return true;
            }

            function validateStep(stepNumber, showErrors = true) {
                const stepName = getStepName(stepNumber);
                const stepPane = $(`#step-${stepName}`);
                let isValid = true;
                let errors = [];

                // Clear previous step errors
                if (showErrors) {
                    clearStepErrors(stepPane);
                }

                const requirements = stepRequirements[stepNumber];
                if (!requirements) return true;

                // Validate required fields
                requirements.required.forEach(fieldName => {
                    const field = stepPane.find(`[name="${fieldName}"]`);
                    if (field.length) {
                        const value = field.val();
                        if (!value || value.trim() === '') {
                            isValid = false;
                            errors.push(`${getFieldLabel(fieldName)} is required`);
                            if (showErrors) {
                                showFieldError(field, `${getFieldLabel(fieldName)} is required`);
                            }
                        } else {
                            // Validate the field content
                            if (!validateField(field, stepNumber)) {
                                isValid = false;
                            }
                        }
                    }
                });

                // Custom step validations
                if (!validateStepCustomRules(stepNumber, stepPane, showErrors)) {
                    isValid = false;
                }

                // Update step error tracking
                if (isValid) {
                    stepErrors.delete(stepNumber);
                } else {
                    stepErrors.set(stepNumber, errors);
                }

                return isValid;
            }

            function validateStepCustomRules(stepNumber, stepPane, showErrors) {
                let isValid = true;

                switch (stepNumber) {
                    case 1: // Basic Info
                        const description = stepPane.find('[name="description"]').val();
                        if (description && description.length < 10) {
                            if (showErrors) {
                                showFieldError(stepPane.find('[name="description"]'), 'Description must be at least 10 characters long');
                            }
                            isValid = false;
                        }
                        break;

                    case 2: // Pricing

                        const price = parseFloat(stepPane.find('[name="price"]').val()) || 0;
                        const salePrice = parseFloat(stepPane.find('[name="sale_price"]').val()) || 0;

                        if (salePrice && (isNaN(salePrice) || parseFloat(salePrice) <= 0)) {
                            showFieldError(field, 'Price must be a valid positive number');
                            return false;
                        }

                        if(price){
                            if (salePrice > 0 && salePrice >= price) {
                                if (showErrors) {
                                    showFieldError(stepPane.find('[name="sale_price"]'), 'Sale price must be less than regular price');
                                }
                                isValid = false;
                            }
                        }
                        break;

                    case 4: // Media
                        const imageId = stepPane.find('[name="image_id"]').val();
                        if (!imageId) {
                            if (showErrors) {
                                const uploadBtn = stepPane.find('.media_upload_form_btn');
                                showFieldError(uploadBtn, 'At least one product image is required');
                            }
                            isValid = false;
                        }
                        break;

                    case 4: // Categories
                        const categoryId = stepPane.find('[name="category_id"]').val();
                        if (!categoryId) {
                            if (showErrors) {
                                showFieldError(stepPane.find('[name="category_id"]'), 'Please select a category');
                            }
                            isValid = false;
                        }
                        break;
                }

                return isValid;
            }

            function updateProgress() {
                $('.step').removeClass('active completed has-errors');

                $('.step').each(function() {
                    const stepNum = parseInt($(this).data('step'));

                    if (stepNum < currentStep) {
                        $(this).addClass('completed');
                    } else if (stepNum === currentStep) {
                        $(this).addClass('active');
                    }

                    // Mark steps with errors
                    if (stepErrors.has(stepNum)) {
                        $(this).addClass('has-errors');
                    }
                });

                // Update progress line
                const completedCount = Array.from($('.step')).filter(step =>
                    parseInt($(step).data('step')) < currentStep
                ).length;

                document.documentElement.style.setProperty('--completed-steps', completedCount);
                document.documentElement.style.setProperty('--total-steps', totalSteps - 1);

                // Show/hide tab content
                $('.tab-pane').removeClass('show active completed');
                const currentStepName = getStepName(currentStep);
                $(`#step-${currentStepName}`).addClass('show active');

                // Mark completed steps
                for (let i = 1; i < currentStep; i++) {
                    $(`#step-${getStepName(i)}`).addClass('completed');
                }

                updateNavigationState();
            }

            function updateNavigationState() {
                const canProceed = validateStep(currentStep, false);

                $('.prev-step').prop('disabled', currentStep === 1);
                $('.next-step').prop('disabled', !canProceed).toggle(currentStep < totalSteps);
                $('.submit-form').toggle(currentStep === totalSteps);

                // Update next button text based on validation
                if (canProceed) {
                    $('.next-step').html('Next<i class="las la-arrow-right ms-2"></i>');
                } else {
                    $('.next-step').html('Complete Required Fields<i class="fas fa-exclamation-triangle ms-2"></i>');
                }
            }

            function updateStepStatus(stepNumber) {
                const isValid = validateStep(stepNumber, false);
                const stepElement = $(`.step[data-step="${stepNumber}"]`);

                if (isValid) {
                    stepElement.removeClass('has-errors');
                    if (stepNumber < currentStep) {
                        stepElement.addClass('completed');
                    }
                } else {
                    stepElement.addClass('has-errors');
                }
            }

            // Navigation event handlers
            $(document).on('click', '.next-step', function() {
                if (validateStep(currentStep, true)) {
                    completedSteps.add(currentStep);
                    currentStep++;
                    updateProgress();

                    // Scroll to top of new step
                    $('html, body').animate({
                        scrollTop: $(`#step-${getStepName(currentStep)}`).offset().top - 100
                    }, 300);

                    toastr.success('Step completed successfully!', 'Success', {
                        timeOut: 2000,
                        progressBar: true
                    });
                } else {
                    toastr.error('Please complete all required fields before proceeding', 'Validation Error');
                }
            });

            $(document).on('click', '.prev-step', function() {
                if (completedSteps.has(currentStep)) {
                    completedSteps.delete(currentStep);
                }
                currentStep--;
                updateProgress();

                // Scroll to top of step
                $('html, body').animate({
                    scrollTop: $(`#step-${getStepName(currentStep)}`).offset().top - 100
                }, 300);
            });

            // Utility functions
            function getStepName(step) {
                const steps = ['basic', 'pricing', 'inventory', 'media', 'categories', 'setting', 'final'];
                return steps[step - 1] || 'basic';
            }

            function getStepNumberFromId(stepId) {
                if (!stepId) return null;
                const stepNames = ['basic', 'pricing', 'inventory', 'media', 'categories', 'setting', 'final'];
                const stepName = stepId.replace('step-', '');
                return stepNames.indexOf(stepName) + 1;
            }

            function getFieldLabel(fieldName) {
                const labels = {
                    'name': 'Product Name',
                    'slug': 'Product Slug',
                    'description': 'Description',
                    // 'price': 'Price',
                    'sale_price': 'Sale Price',
                    'sku': 'SKU',
                    'uom': 'Unit of Measurement',
                    'unit_id': 'Unit',
                    'image_id': 'Product Image',
                    'category_id': 'Category',
                    'tax_class': 'Tax Class'
                };
                return labels[fieldName] || fieldName.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            }

            function showFieldError(field, message) {
                clearFieldError(field);

                field.addClass('is-invalid');

                const errorDiv = $('<div class="error-message text-danger mt-1 small"></div>').text(message);

                // Handle different field types
                if (field.hasClass('select2-hidden-accessible')) {
                    field.next('.select2').after(errorDiv);
                } else if (field.closest('.media-upload-btn-wrapper').length) {
                    field.closest('.media-upload-btn-wrapper').addClass('has-error').after(errorDiv);
                } else if (field.is('select')) {
                    field.after(errorDiv);
                } else {
                    field.after(errorDiv);
                }
            }

            function clearFieldError(field) {
                field.removeClass('is-invalid');
                field.next('.error-message').remove();
                field.siblings('.error-message').remove();
                field.closest('.form-group').find('.error-message').remove();

                // Clear select2 errors
                if (field.hasClass('select2-hidden-accessible')) {
                    field.next('.select2').removeClass('is-invalid').next('.error-message').remove();
                }

                // Clear media upload errors
                field.closest('.media-upload-btn-wrapper').removeClass('has-error').next('.error-message').remove();
            }

            function clearStepErrors(stepPane) {
                stepPane.find('.error-message').remove();
                stepPane.find('.is-invalid').removeClass('is-invalid');
                stepPane.find('.has-error').removeClass('has-error');
            }

            // Form submission handler
            $(document).on('submit', '#product-edit-form', function(e) {
                e.preventDefault();

                // Validate all steps before submission
                let allValid = true;
                let firstErrorStep = null;

                for (let i = 1; i <= totalSteps; i++) {
                    if (!validateStep(i, true)) {
                        allValid = false;
                        if (firstErrorStep === null) {
                            firstErrorStep = i;
                        }
                    }
                }

                if (!allValid) {
                    toastr.error('Please fix all validation errors before submitting', 'Validation Error');

                    // Jump to first step with errors
                    if (firstErrorStep) {
                        currentStep = firstErrorStep;
                        updateProgress();
                    }
                    return false;
                }

                // Get form data
                let form = $(this);
                let formData = new FormData(this);
                let submitUrl = form.attr("data-request-route");

                // Make AJAX request
                $.ajax({
                    url: submitUrl,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || "{{csrf_token()}}"
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('.submit-form').prop('disabled', true)
                            .html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
                    },
                    success: function(data) {
                        if (data.success) {
                            toastr.success("Product Updated Successfully");
                            toastr.success("Changes saved successfully");

                            // Reset temp flag
                            temp = false;

                            // Optional: Redirect or stay on page
                            setTimeout(function() {
                                // You can redirect or just show success
                                window.location.reload();
                            }, 2000);

                        } else {
                            toastr.error(data.message || 'An error occurred');
                            $('.submit-form').prop('disabled', false)
                                .html('<i class="fas fa-check me-2"></i>Update Product');
                        }
                    },
                    error: function(xhr) {
                        // Re-enable submit button
                        $('.submit-form').prop('disabled', false)
                            .html('<i class="fas fa-check me-2"></i>Update Product');

                        if (xhr.status === 422) {
                            // Validation errors from server
                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(field, messages) {
                                toastr.error(field + ': ' + messages[0]);

                                // Find and highlight the field
                                let fieldElement = form.find('[name="' + field + '"]');
                                if (fieldElement.length) {
                                    fieldElement.addClass('is-invalid');
                                    // Find which step this field belongs to
                                    let stepPane = fieldElement.closest('.tab-pane');
                                    if (stepPane.length) {
                                        let stepId = stepPane.attr('id');
                                        let stepNumber = getStepNumberFromId(stepId);
                                        if (stepNumber && stepNumber !== currentStep) {
                                            // Jump to step with error
                                            currentStep = stepNumber;
                                            updateProgress();
                                        }
                                    }
                                }
                            });

                        } else if (xhr.status === 500) {
                            toastr.error('Server error occurred. Please try again.');
                        } else if (xhr.status === 0) {
                            toastr.error('Network error. Please check your connection.');
                        } else {
                            toastr.error('An unexpected error occurred. Status: ' + xhr.status);
                        }
                    }
                });
            });
        });

        // Additional functionality from original edit page
        $(document).on('change', '.item_attribute_name', function (){
            let value = $(this).find("option:selected").text();
            let oldValue = $(this).closest(".inventory_item").find(`input[value=${value}]`);

            let attribute_warning = $(this).parents('.row').siblings('.attribute-warning');
            attribute_warning.css('color', 'black');

            if(oldValue.length > 0){
                toastr.warning(`{{ __("You can't select same attribute within a same variant if you need then please create a new variant") }}`)
                $(this).find("option").each(function (){
                    $(this).attr("selected", false)
                })
                $(this).find("option:first-child").attr("selected", true);

                attribute_warning.css('color', 'red');
                return false;
            }

            let terms = $(this).find('option:selected').data('terms');
            let terms_html = '<option value=""><?php echo e(__("Select variant value")); ?></option>';
            terms.map(function (term) {
                terms_html += '<option value="' + term + '">' + term + '</option>';
            });
            $(this).closest('.inventory_item').find('.item_attribute_value').html(terms_html);
        });

        $(document).ready(function() {
            $('.select2').select2({
                placeholder: '{{__('Select an option')}}',
                language: {
                    noResults: function() {
                        return "{{__('No result found')}}"
                    }
                }
            });
        });

        $(document).on("change",".dashboard-products-add .form--control", function (){
            $(".dashboard-products-add .form--control").each(function (){
                if($(this).val() != ''){
                    temp = true;
                    return false;
                }else{
                    temp = false;
                }
            })
        });

        $(document).ready(function () {

            $(document).on('change', '.is_taxable_wrapper select[name=is_taxable]', function () {
                $('.tax_classes_wrapper').toggle();
                $('.tax_classes_wrapper select[name=tax_class]').prop('selectedIndex', 0);
            });

            $(document).on("click", ".delivery-item", function () {
                $(this).toggleClass("active");
                $(this).effect("shake", {direction: "up", times: 1, distance: 2}, 500);
                let delivery_option = "";
                $.each($(".delivery-item.active"), function () {
                    delivery_option += $(this).data("delivery-option-id") + " , ";
                })

                delivery_option = delivery_option.slice(0, -3)
                $(".delivery-option-input").val(delivery_option);
            });

            $(document).on("change", "#category", function () {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("category_id", $(this).val());

                send_ajax_request("post", data, '{{ route('tenant.admin.category.sub-category') }}', function () {
                    $("#sub_category").html("<option value=''>{{__('Select Sub Category')}}</option>");
                    $("#child_category").html("<option value=''>{{__('Select Child Category')}}</option>");
                    $("#select2-child_category-container").html('');
                }, function (data) {
                    $("#sub_category").html(data.html);
                }, function () {

                });
            });

            $(document).on("change", "#sub_category", function () {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("sub_category_id", $(this).val());

                let child_category_wrapper = $("#child_category");
                send_ajax_request("post", data, '{{ route('tenant.admin.category.child-category') }}', function () {
                    child_category_wrapper.parent().css('position', 'relative')
                    child_category_wrapper.parent().append(`<div class="icon-container text-center">
                <div class="loading-icon full-circle"></div>
            </div>`);

                    child_category_wrapper.html("<option value=''>{{__('Select Child Category')}}</option>");
                    $("#select2-child_category-container").html('');

                }, function (data) {
                    child_category_wrapper.html(data.html);
                }, function () {

                });

                child_category_wrapper.parent().css('position', 'unset');
                $('.icon-container').remove();
            });

            $(document).on('click', '.badge-item', function (e) {
                if ($(this).hasClass("active"))
                {
                    $(this).removeClass("active")
                    $("#badge_id_input").val('');
                } else {
                    $(".badge-item").removeClass("active");
                    $(this).addClass("active");
                    $("#badge_id_input").val($(this).attr("data-badge-id"));
                }

                $(this).effect("shake", {direction: "up", times: 1, distance: 2}, 500);
            });

            $(document).on("click", ".close-icon", function () {
                $('#media_upload_modal').modal('hide');
            });

            $(document).on('click', '.repeater_button .remove', function (e) {
                if($('.repeater_button .remove').length > 1){
                    $(this).closest('.inventory_item').remove();
                }
            });

            $(document).on('click', '.remove_details_attribute', function (e) {
                $(this).parent().parent().remove();
            });

            function send_ajax_request(request_type, request_data, url, before_send, success_response, errors) {
                $.ajax({
                    url: url,
                    type: request_type,
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}",
                    },
                    beforeSend: (typeof before_send !== "undefined" && typeof before_send === "function") ? before_send : () => {
                        return "";
                    },
                    processData: false,
                    contentType: false,
                    data: request_data,
                    success: (typeof success_response !== "undefined" && typeof success_response === "function") ? success_response : () => {
                        return "";
                    },
                    error: (typeof errors !== "undefined" && typeof errors === "function") ? errors : () => {
                        return "";
                    }
                });
            }

            function ajax_toastr_error_message(xhr) {
                $.each(xhr.responseJSON.errors, function (key, value) {
                    toastr.error((key.capitalize()).replace("-", " ").replace("_", " "), value);
                });
            }

            function ajax_toastr_success_message(data) {
                if (data.success) {
                    toastr.success(data.msg)
                } else {
                    toastr.warning(data.msg);
                }
            }
        });


    </script>
@endsection
