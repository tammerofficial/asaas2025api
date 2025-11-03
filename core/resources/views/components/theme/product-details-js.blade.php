<script>
    let attribute_store = JSON.parse('{!! json_encode($product_inventory_set) !!}');
    {{--window.quick_view_attribute_store = JSON.parse('{!! json_encode($product_inventory_set) !!}');--}}
    let additional_info_store = JSON.parse('{!! json_encode($additional_info_store) !!}');
    let available_options = $('.value-input-area');
    let selected_variant = '';

    function getAttributesForCart() {
        let selected_options = get_selected_options();

        let cart_selected_options = selected_options;
        let hashed_key = getSelectionHash(selected_options);

        // if selected attribute set is available
        if (additional_info_store[hashed_key]) {
            return additional_info_store[hashed_key]['pid_id'];
        }

        // if selected attribute set is not available
        if (Object.keys(selected_options).length) {
            toastr.error('{{__('Attribute not available')}}')
        }

        return '';
    }

    function get_selected_options() {
        let selected_options = {};
        let available_options = $('.value-input-area');
        // get all selected attributes in {key:value} format
        available_options.map(function (k, option) {
            let selected_option = $(option).find('li.active');
            let type = selected_option.closest('.size-lists').data('type');
            let value = String(selected_option.data('displayValue'));

            if (type && value) {
                selected_options[type] = value;
            }
        });

        let ordered_data = {};
        let selected_options_keys = Object.keys(selected_options).sort();
        selected_options_keys.map(function (e) {
            ordered_data[e] = selected_options[e];
        });

        return ordered_data;
    }

    function getSelectionHash(selected_options) {
        return MD5(JSON.stringify(selected_options));
    }

    (function ($) {
        'use script'

        $(document).ready(function () {
            $(document).on('click', '#login_submit_btn', function (e) {
                e.preventDefault();

                let el = $(this);
                let username = $('#login_form_order_page input[name=email]').val();
                let password = $('#login_form_order_page input[name=password]').val();
                let remember = $('#login_form_order_page input[name=remember]').val();

                el.text('{{__("Please Wait")}}');

                $.ajax({
                    type: 'post',
                    url: "{{route('tenant.user.ajax.login')}}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        username: username,
                        password: password,
                        remember: remember,
                    },
                    success: function (data) {
                        if (data.status === 'invalid') {
                            el.text('{{__("Login")}}')
                            toastr.warning(data.msg );
                        } else {
                            el.text('{{__("Login Success.. Redirecting ..")}}');
                            toastr.success(data.msg );

                            setTimeout(() => {
                                location.reload();
                            }, 300)
                        }
                    },
                    error: function (data) {
                        var response = data.responseJSON.errors;
                        $.each(response, function (value, index) {
                            toastr.error(index);
                        });
                        el.text('{{__("Login")}}');
                    }
                });
            });

            $(document).on('click', '.size-lists li', function (event) {
                let el = $(this);
                let value = el.data('displayValue');
                let parentWrap = el.parent().parent();
                el.addClass('active');
                el.siblings().removeClass('active');

                // selected attributes
                selectedAttributeSearch(this);

                parentWrap.find('input[type=text]').val(value);
                parentWrap.find('input[type=hidden]').val(el.data('value'));
            });

            $(document).on('click', '.value-input-area', function () {
                selectedAttributeSearch();
            });
        });
    })(jQuery)

    function selectedAttributeSearch(selected_item) {
        /*
        * search based on all selected attributes
        *
        * 1. get all selected attributes in {key:value} format
        * 2. search in attribute_store for all available matches
        * 3. display available matches (keep available matches selectable, and rest as disabled)
        * */

        let available_variant_types = [];
        let selected_options = {};

        $('.size-lists li').addClass('disabled');

        // get all selected attributes in {key:value} format
        available_options.map(function (k, option) {
            let selected_option = $(option).find('li.active');
            let type = selected_option.closest('.size-lists').data('type');
            let value = String(selected_option.data('displayValue'));

            if (type) {
                available_variant_types.push(type);
            }

            if (type && value) {
                selected_options[type] = value;
            }
        });


        syncImage(get_selected_options());
        syncPrice(get_selected_options());
        syncStock(get_selected_options());

        // search in attribute_store for all available matches
        let available_variants_selection = [];
        let selected_attributes_by_type = {};

        attribute_store.map(function (arr) {
            let matched = true;

            Object.keys(selected_options).map(function (type) {

                if (arr[type] !== selected_options[type]) {
                    matched = false;
                }
            })

            if (matched) {
                available_variants_selection.push(arr);

                // insert as {key: [value, value...]}
                Object.keys(arr).map(function (type) {
                    // not array available for the given key
                    if (!selected_attributes_by_type[type]) {
                        selected_attributes_by_type[type] = []
                    }

                    // insert value if not inserted yet
                    if (selected_attributes_by_type[type].indexOf(arr[type]) <= -1) {
                        selected_attributes_by_type[type].push(arr[type]);
                    }
                })
            }
        });

        // selected item not contain product then de-select all selected option here
        if (Object.keys(selected_attributes_by_type).length == 0) {
            $('.size-lists li.active').each(function () {
                let sizeItem = $(this).parent().parent();

                sizeItem.find('input[type=hidden]').val('');
                sizeItem.find('input[type=text]').val('');
            });

            $('.size-lists li.active').removeClass("active");
            $('.size-lists li.disabled').removeClass("disabled");

            let el = $(selected_item);
            let value = el.data('displayValue');

            el.addClass("active");
            $(this).find('input[type=hidden]').val(value);
            $(this).find('input[type=text]').val(el.data('value'));

            selectedAttributeSearch();
        }

        // keep only available matches selectable

        Object.keys(selected_attributes_by_type).map(function (type) {
            // initially, disable all buttons

            // make buttons selectable for the available options
            selected_attributes_by_type[type].map(function (value) {
                let available_buttons = $('.size-lists[data-type="' + type + '"] li[data-display-value="' + value + '"]');
                available_buttons.map(function (key, el) {
                    $(el).removeClass('disabled');
                })
            })
        });
        // todo check is empty object
        // selected_attributes_by_type
        selected_variant = selected_attributes_by_type;
    }

    function getSelectedOptions() {
        let selected_options = {};

        // get all selected attributes in {key:value} format
        available_options.map(function (k, option) {
            let selected_option = $(option).find('li.active');
            let type = selected_option.closest('.size-lists').data('type');
            let value = selected_option.data('displayValue');

            if (type && value) {
                selected_options[type] = value;
            }
        });

        return selected_options;
    }

    function validateSelectedAttributes() {
        let selected_options = get_selected_options();
        let hashed_key = getSelectionHash(selected_options);
        // validate if product has any attribute
        if (attribute_store.length && Object.keys(attribute_store[0] || {}).length > 0) {
            if (!Object.keys(selected_options).length) {
                return false;
            }
            if (!additional_info_store[hashed_key]) {
                return false;
            }
            return !!additional_info_store[hashed_key]['pid_id'];
        }
        return true;
    }

    function syncImage(selected_options) {
        //todo fire when attribute changed
        let hashed_key = getSelectionHash(selected_options);
        //single-main-image slick-slide slick-current slick-active
        let product_image_el = $('#shop_details_gallery_slider .long-img img');

        let img_original_src = product_image_el.parent().data('src');

        // if selection has any image to it
        if (additional_info_store[hashed_key]) {
            let attribute_image = additional_info_store[hashed_key].image;
            if (attribute_image) {
                product_image_el.attr('src', attribute_image);
                product_image_el.parent().attr('data-src', attribute_image);
                //change zoom image also
            }
        } else {
            product_image_el.attr('src', img_original_src);
            product_image_el.parent().attr('data-src', img_original_src);
            //change zoom image also
        }
    }

    function syncPrice(selected_options) {
        let hashed_key = getSelectionHash(selected_options);

        let product_price_el = $('#price');
        let product_main_price = Number(String(product_price_el.data('mainPrice'))).toFixed(2);
        let site_currency_symbol = product_price_el.data('currencySymbol');

        // if selection has any additional price to it

        if (additional_info_store[hashed_key]) {
            let attribute_price = additional_info_store[hashed_key]['additional_price'];
            if (attribute_price) {
                let price = Number(product_main_price) + Number(attribute_price);
                product_price_el.text(site_currency_symbol + Number(price).toFixed(2));
            }else {
                product_price_el.text(site_currency_symbol + product_main_price);
            }
        } else {
            product_price_el.text(site_currency_symbol + product_main_price);
        }
    }

    function syncStock(selected_options) {
        let hashed_key = getSelectionHash(selected_options);
        let product_stock_el = $('#stock');
        let product_item_left_el = $('#item_left');
        let quantity_btns = $('.quantity-btn');
        let quantity_div = $('.product-quantity');
        // if selection has any size and color to it

        quantity_btns.show();
        quantity_div.removeAttr('hidden');
        if (additional_info_store[hashed_key]) {
            let stock_count = additional_info_store[hashed_key]['stock_count'];

            let stock_message = '';
            if (Number(stock_count) > 0) {
                stock_message = `<span class="text-success">{{__('In Stock')}}</span>`;
                product_item_left_el.text(`{{__('Only!')}} ${stock_count} {{__('Item Left!')}}`);
                product_item_left_el.addClass('text-success');
                product_item_left_el.removeClass('text-danger');

            } else {
                quantity_btns.hide();
                quantity_div.attr('hidden', true);
                stock_message = `<span class="text-danger">{{__('Our fo Stock')}}</span>`;
                product_item_left_el.text(`{{__('No Item Left!')}}`);
                product_item_left_el.addClass('text-danger');
                product_item_left_el.removeClass('text-success');
                // quantity-btn

            }

            product_stock_el.html(stock_message);

        }else{
            product_stock_el.html(product_stock_el.data("stock-text"))
            product_item_left_el.html(product_item_left_el.data("stock-text"))
        }
    }


    $(document).on('click', '.add_to_cart_single_page', function (e) {
        e.preventDefault();

        let has_campaign = '{{empty($campaign_product) ? 0 : 1}}';
        let campaign_expired = '{{isset($is_expired) ? $is_expired : 0}}';

        let selected_size = $('#selected_size').val();
        let selected_color = $('#selected_color').val();

        let pid_id = getAttributesForCart();

        let product_id = '{{$product->id}}';
        let quantity = Number($('#quantity').val().trim());
        let price = $('#price').text().split(site_currency_symbol)[1];
        let attributes = {};
        let product_variant = pid_id;
        let productAttribute = selected_variant;

        attributes['price'] = price;

        // if selected attribute is a valid product item
        if (validateSelectedAttributes()) {
            $.ajax({
                url: '{{ route("tenant.shop.product.add.to.cart.ajax") }}',
                type: 'POST',
                data: {
                    product_id: product_id,
                    quantity: quantity,
                    pid_id: pid_id,
                    product_variant: product_variant,
                    selected_size: selected_size,
                    selected_color: selected_color,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function () {

                },
                success: function (data) {
                    if (data.quantity_msg)
                    {
                        toastr.warning(data.quantity_msg);
                    }
                    else if(data.error_msg)
                    {
                        toastr.error(data.error_msg);
                    }
                    else
                    {
                        toastr.success(data.msg, '{{__('Go to Cart')}}', '#', 60000);
                        let track_icon_list = $('.track-icon-list');
                        track_icon_list.hide();
                        track_icon_list.load(location.href + " .track-icon-list");
                        track_icon_list.fadeIn();
                    }
                },
                error: function (err) {
                    toastr.error('{{ __("An error occurred") }}');
                }
            });
        } else {
            toastr.error('{{ __("Select all attribute to proceed") }}')
        }
    });

    $(document).on('click', '.add_to_wishlist_single_page', function (e) {
        e.preventDefault();

        let selected_size = $('#selected_size').val();
        let selected_color = $('#selected_color').val();

        let pid_id = getAttributesForCart();

        let product_id = '{{$product->id}}';
        let quantity = Number($('#quantity').val().trim());
        let price = $('#price').text().split(site_currency_symbol)[1];
        let attributes = {};
        let product_variant = pid_id;
        let productAttribute = selected_variant;

        attributes['price'] = price;

        // if selected attribute is a valid product item
        if (validateSelectedAttributes()) {
            $.ajax({
                url: '{{ route("tenant.shop.product.add.to.wishlist.ajax") }}',
                type: 'POST',
                data: {
                    product_id: product_id,
                    quantity: quantity,
                    pid_id: pid_id,
                    product_variant: product_variant,
                    selected_size: selected_size,
                    selected_color: selected_color,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function () {

                },
                success: function (data) {
                    if (data.quantity_msg)
                    {
                        toastr.warning(data.quantity_msg);
                    }
                    else if(data.error_msg)
                    {
                        toastr.error(data.error_msg);
                    }
                    else
                    {
                        toastr.success(data.msg, '{{__('Go to Cart')}}', '#', 60000);
                        $('.track-icon-list').load(location.href + " .track-icon-list");
                    }
                },
                error: function (err) {
                    toastr.error('{{ __("An error occurred") }}')
                }
            });
        } else {
            toastr.error('{{ __("Select all attribute to proceed") }}')
        }
    });


    $(document).on('click', '.compare-btn', function (e) {
        e.preventDefault();

        let selected_size = $('#selected_size').val();
        let selected_color = $('#selected_color').val();

        let pid_id = getAttributesForCart();

        let product_id = '{{$product->id}}';
        let quantity = Number($('#quantity').val().trim());
        let price = $('#price').text().split(site_currency_symbol)[1];
        let attributes = {};
        let product_variant = pid_id;
        let productAttribute = selected_variant;

        attributes['price'] = price;

        // if selected attribute is a valid product item
        if (validateSelectedAttributes()) {
            $.ajax({
                url: '{{ route("tenant.shop.product.add.to.compare.ajax") }}',
                type: 'POST',
                data: {
                    product_id: product_id,
                    quantity: quantity,
                    pid_id: pid_id,
                    product_variant: product_variant,
                    selected_size: selected_size,
                    selected_color: selected_color,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function () {

                },
                success: function (data) {
                    if (data.quantity_msg)
                    {
                        toastr.warning(data.quantity_msg);
                    }
                    else if(data.error_msg)
                    {

                        toastr.error(data.error_msg);
                    }
                    else
                    {
                        toastr.success(data.msg, '{{__('Go to Cart')}}', '#', 60000);
                        $('.track-icon-list').load(location.href + " .track-icon-list");
                    }
                },
                error: function (err) {
                    toastr.error('{{ __("An error occurred") }}')
                }
            });
        } else {
            toastr.error('{{ __("Select all attribute to proceed") }}')
        }
    });

    $(document).on('click', '.but_now_single_page', function (e) {
        e.preventDefault();

        let selected_size = $('#selected_size').val() || '';
        let selected_color = $('#selected_color').val() || '';

        let pid_id = getAttributesForCart();

        let product_id = '{{$product->id}}';
        let quantity = Number($('#quantity').val().trim());
        let price = $('#price').text().split(site_currency_symbol)[1];
        let attributes = {};
        let product_variant = pid_id;
        let productAttribute = selected_variant ;
        let buy_btn = $('.btn-wrapper a');

        attributes['price'] = price;
        // if selected attribute is a valid product item
        if (validateSelectedAttributes()) {
            $.ajax({
                url: '{{ route("tenant.shop.product.buy.now.ajax") }}',
                type: 'POST',
                data: {
                    product_id: product_id,
                    quantity: quantity,
                    pid_id: pid_id,
                    product_variant: product_variant,
                    selected_size: selected_size,
                    selected_color: selected_color,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function () {
                    buy_btn.attr('disabled');
                },
                success: function (data) {

                    if (data.quantity_msg)
                    {
                        toastr.warning(data.quantity_msg);
                    }
                    else if(data.error_msg)
                    {
                        toastr.error(data.error_msg);
                    }

                    if(data.type === 'success')
                    {
                        toastr.success(data.msg);
                        setTimeout(()=>{
                            location.href = data.redirect;
                        }, 2000)
                    }
                },
                error: function (err) {
                    let errorMessage = err.responseText || err.statusText || 'An error occurred';
                    toastr.error(errorMessage);
                }
            });
        } else {
            toastr.error('{{ __("Select all attribute to proceed") }}')
        }
    });
</script>
