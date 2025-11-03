<script>
    (function ($) {
        "use strict"

        $(document).on('change', '.item_attribute_name', function () {
            // Get the parent inventory item container
            let $container = $(this).closest('.inventory_item');

            // Get the selected terms for the attribute value dropdown
            let terms = $(this).find('option:selected').data('terms') || [];

            // Generate HTML for attribute value options
            let terms_html = '<option value="">Select variant value</option>';
            terms.forEach(function (term) {
                terms_html += `<option value="${term}">${term}</option>`;
            });

            // Update the item_attribute_value dropdown with new options
            $container.find('.item_attribute_value').html(terms_html);

            // Collect all selected attribute names within this inventory item
            let selectedAttributes = [];
            $container.find('.item_selected_attributes input[name^="item_attribute_name"]').each(function() {
                let value = $(this).val();
                if (value) {
                    // Map the text value to the corresponding option value
                    selectedAttributes.push($container.find('.item_attribute_name option').filter(function() {
                        return $(this).text() === value;
                    }).val());
                }
            });

            // Get the item_attribute_name select dropdown
            let $attributeTypeSelect = $container.find('.item_attribute_name');

            // Enable all options first to reset previous restrictions
            $attributeTypeSelect.find('option').prop('disabled', false);

            // Disable options that are already selected
            $attributeTypeSelect.find('option').each(function() {
                let optionValue = $(this).val();
                if (optionValue && selectedAttributes.includes(optionValue)) {
                    $(this).prop('disabled', true);
                }
            });
        });
        /** ABOVE CODE ARE COME FROM EDIT PAGE **/

        function initializeAttributeRestrictions() {
            $('.inventory_item').each(function() {
                let $container = $(this);

                // Collect all selected attribute names within this inventory item
                let selectedAttributes = [];
                $container.find('.item_selected_attributes input[name^="item_attribute_name"]').each(function() {
                    let value = $(this).val();
                    if (value) {
                        // Map the text value to the corresponding option value
                        selectedAttributes.push($container.find('.item_attribute_name option').filter(function() {
                            return $(this).text() === value;
                        }).val());
                    }
                });

                // Get the item_attribute_name select dropdown
                let $attributeSelect = $container.find('.item_attribute_name');

                // Enable all options first to reset any previous state
                $attributeSelect.find('option').prop('disabled', false);

                // Disable options that are already selected
                $attributeSelect.find('option').each(function() {
                    let optionValue = $(this).val();
                    if (optionValue && selectedAttributes.includes(optionValue)) {
                        $(this).prop('disabled', true);
                    }
                });
            });
        }

        $(document).ready(function () {

            initializeAttributeRestrictions();

            $(document).on('click', '.add_variant_info_btn', function () {
                // console.log('from add_variant_info_btn');
                $(this).closest('.variant_info').append(`<x-product::variant-info.repeater :is-first="false" :colors="$colors" :sizes="$sizes" :all-available-attributes="$allAttributes" />`);
            });

            $(document).on('click', '.remove_this_variant_info_btn', function () {
                $(this).closest('.variant_info_repeater').remove();
            });
        });
        $(document).on('click', '.add_item_attribute', function (e) {
            /* Main culprit for the custom attribute not storing properly in product create/update */

            // console.log('from add_item_attribute');
            let container = $(this).closest('.inventory_item');
            let attribute_name_field = container.find('.item_attribute_name');
            let attribute_value_field = container.find('.item_attribute_value');
            let attribute_name = attribute_name_field.find('option:selected').text();
            let attribute_value = attribute_value_field.find('option:selected').text();

            let container_id = container.data('id');

            if (!container_id) {
                container_id = 0;
            }

            if (attribute_name_field.val().length && attribute_value_field.val().length) {
                let attribute_repeater = '';
                attribute_repeater += '<div class="row align-items-center">';
                attribute_repeater += '<input type="hidden" name="item_attribute_id[' + container_id + '][]" value="">';
                attribute_repeater += '<div class="col">';
                attribute_repeater += '<div class="form-group">';
                attribute_repeater += '<input type="text" class="form-control" name="item_attribute_name[' + container_id + '][]" value="' + attribute_name + '" readonly />';
                attribute_repeater += '</div>';
                attribute_repeater += '</div>';
                attribute_repeater += '<div class="col">';
                attribute_repeater += '<div class="form-group">';
                attribute_repeater += '<input type="text" class="form-control" name="item_attribute_value[' + container_id + '][]" value="' + attribute_value + '" readonly />';
                attribute_repeater += '</div>';
                attribute_repeater += '</div>';
                attribute_repeater += '<div class="col-auto">';
                attribute_repeater += '<button class="btn btn-danger remove_details_attribute"> x </button>';
                attribute_repeater += '</div>';
                attribute_repeater += '</div>';

                container.find('.item_selected_attributes').append(attribute_repeater);

                attribute_name_field.val('');
                attribute_value_field.val('');
            } else {
                toastr.warning('<?php echo e(__("Select both variant name and value")); ?>');
            }

            initializeAttributeRestrictions();
        });



        let inventory_item_id = 0;

        $(document).on('click', '.repeater_button .add', function (e) {
            // console.log('from repeater_button');

            let inventory_item = `<x-product::variant-info.repeater :colors="$colors" :sizes="$sizes" :all-available-attributes="$allAttributes" />`;

            /* 2nd culprit for appending multiple repeater and not maintaining the serial */

            if (inventory_item_id < 1) {
                inventory_item_id = $('.inventory_items_container .inventory_item').length;
            }
            let newId = inventory_item_id++;

            $('.inventory_items_container').append(inventory_item);
            $('.inventory_items_container .inventory_item:last-child').attr('data-id', newId); // use attr to persist in DOM

        });

    })(jQuery);
</script>
