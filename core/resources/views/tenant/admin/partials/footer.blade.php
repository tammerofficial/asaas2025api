<footer class="footer">
    <div class="container-fluid d-flex justify-content-between">
        <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">
            {!! get_footer_copyright_text() !!}
        </span>
        <span class="float-none float-sm-end mt-1 mt-sm-0 text-end"> v- <strong>{{get_static_option_central('get_script_version')}}</strong></span>
    </div>
</footer>
</div>
</div>
</div>

{{--<script src="{{global_asset('assets/landlord/admin/js/vendor.bundle.base.js')}}"></script>--}}
{{--<script src="{{global_asset('assets/landlord/admin/js/hoverable-collapse.js')}}"></script>--}}
{{--<script src="{{global_asset('assets/landlord/admin/js/off-canvas.js')}}"></script>--}}
{{--<script src="{{global_asset('assets/landlord/admin/js/misc.js')}}"></script>--}}
{{--<script src="{{global_asset('assets/landlord/common/js/axios.min.js')}}"></script>--}}
{{--<script src="{{global_asset('assets/landlord/common/js/sweetalert2.js')}}"></script>--}}
{{--<script src="{{global_asset('assets/common/js/countdown.jquery.js')}}"></script>--}}
{{--<script src="{{global_asset('assets/common/js/flatpickr.js')}}"></script>--}}
{{--<x-flatpicker.flatpickr-locale/>--}}
{{--<script src="{{global_asset('assets/common/js/toastr.min.js')}}"></script>--}}
{{--<script src="{{global_asset('assets/common/js/select2.min.js')}}"></script>--}}


<!-- Javascript Helpers -->
{{--<script src="{{ global_asset('assets/js/helpers.js') }}"></script>--}}

{!! \App\Services\AdminTheme\MetaDataHelpers::Init()->getRenderableCoreStylesJs() !!}
{{-- Load flatpickr locale AFTER flatpickr.js is loaded --}}
<x-flatpicker.flatpickr-locale/>

<script>
    window.appUrl = "{{ url('/') }}";
    window.currencySymbol = {
        currencyPosition: `{{get_static_option('site_currency_symbol_position')}}`,
        symbol: `{{site_currency_symbol()}}`
    };

    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "slideUp",
        "rtl": "{{get_user_lang_direction() == 1}}"
    }

    const translatedDataTable = () => {
        return {
            "decimal": "",
            "emptyTable": "{{__('No data available in table')}}",
            "info": "{{__('Showing')}} _START_ {{__('to')}} _END_ {{__('of')}} _TOTAL_ {{__('entries')}}",
            "infoEmpty": "{{__('Showing')}} 0 {{__('to')}} 0 {{__('of')}} 0 {{__('entries')}}",
            "infoFiltered": "({{__('filtered from')}} _MAX_ {{__('total entries')}})",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "{{__('Show')}} _MENU_ {{__('entries')}}",
            "loadingRecords": "{{__('Loading...')}}",
            "processing": "",
            "search": "{{__('Search:')}}",
            "zeroRecords": "{{__('No matching records found')}}",
            "paginate": {
                "first": "{{__('First')}}",
                "last": "{{__('Last')}}",
                "next": "{{__('Next')}}",
                "previous": "{{__('Previous')}}"
            },
            "aria": {
                "sortAscending": ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    }

    (function($){
        "use strict";

        $(document).ready(function ($) {
            sidebar();

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            $('select.select2').select2();
            $(document).on('click','.swal_delete_button',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure?")}}',
                    text: '{{__("You would not be able to revert this item!")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1F51FF',
                    cancelButtonColor: '#D2042D',
                    confirmButtonText: "{{__('Yes, delete it!')}}",
                    cancelButtonText: "{{__('Cancel')}}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            $(document).on('click','.swal_change_language_button',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure to make this language as a default language?")}}',
                    text: '{{__("Languages will be turn changed as default")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1F51FF',
                    cancelButtonColor: '#D2042D',
                    confirmButtonText: "{{__('Yes, Change it!')}}",
                    cancelButtonText: "{{__('Cancel')}}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            $(document).on('click','.swal_change_approve_payment_button',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure to approve this payment?")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1F51FF',
                    cancelButtonColor: '#D2042D',
                    confirmButtonText: "{{__('Yes, Accept it!')}}",
                    cancelButtonText: "{{__('Cancel')}}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            $(document).on('click','.swal_status_change',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure to change this status?")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1F51FF',
                    cancelButtonColor: '#D2042D',
                    confirmButtonText: "{{__('Yes, Change it!')}}",
                    cancelButtonText: "{{__('Cancel')}}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            let light = false;
            $(document).on('click', '.tenant_info_icon', function(){
                $('.tenant_info_list').slideToggle(300);
                if(light === false){
                    $('.tenant_info_icon i').removeClass('mdi-lightbulb-on-outline');
                    $('.tenant_info_icon i').addClass('mdi-lightbulb-on');
                    $('.tenant_info_list').addClass('open-info');
                    light = true;
                } else {
                    $('.tenant_info_icon i').addClass('mdi-lightbulb-on-outline');
                    $('.tenant_info_icon i').removeClass('mdi-lightbulb-on');
                    $('.tenant_info_list').removeClass('open-info');
                    light = false;
                }
            });

            $(document).on('click', '.submenu-disabled', function (e){
                e.preventDefault();

                let text = $(this).text();

                Swal.fire({
                    title: '{{__("Coming Soon")}}'+'\n'+text,
                    text: '{{__("This feature is currently under development and will be available soon. We are committed to continuously enhancing our application to provide you with the best possible user experience.")}}',
                    icon: 'info',
                    showCancelButton: false,
                    confirmButtonColor: '#3fc3ee',
                    confirmButtonText: "{{__('Got it')}}",
                });
            });
        });
    })(jQuery);

    window.addEventListener('click', function(e){
        if (!document.getElementById('tenant_info_list').contains(e.target)){
            if($('.open-info').length == 1)
            {
                $('.tenant_info_icon').trigger('click');
            }
        }
    });

    function sidebar()
    {
        let tax_system = `{{get_static_option('tax_system') ?? 'zone_wise_tax_system'}}`;
        let tax_manage_menu = $('#tax-manage-menu-items');
        let country_state = tax_manage_menu.find('ul').children().slice(0,2);
        let tax_class = tax_manage_menu.find('ul').children().slice(3,4);
        if(tax_system === 'zone_wise_tax_system'){
            country_state.fadeIn();
            tax_class.fadeOut();
        } else {
            country_state.fadeOut();
            tax_class.fadeIn();
        }
    }
</script>

<script>
    // Initialize flatpickr only if it's loaded
    $(document).ready(function() {
        if (typeof flatpickr !== 'undefined') {
            $(".date").flatpickr({
                enableTime: true,
                minDate: "today",
                time_12hr: true,
                altInput: true,
                defaultDate: "2018-04-24 16:57"
            });
        }
    });
</script>


{{--// search bar ==== filtering menu and sub menu--}}
<script>
    $(document).ready(function () {
        const $search = $('#menuSearch');
        const $noResults = $('#noResults');

        // Selector map
        const selectors = {
            item: '.nav-item, .menu-item',
            title: '.menu-title, .menu-section',
            submenu: '.sub-menu, .collapse > ul.nav', // tenant submenu is inside collapse > ul.nav
            collapse: '.collapse, .menu-sub-dropdown'
        };

        $search.on('input', function () {
            const term = $(this).val().toLowerCase().trim();
            let hasAnyMatch = false;

            if (!term) {
                $(selectors.item).show();
                $(selectors.collapse).removeClass('show');
                $noResults.addClass('d-none');
                return;
            }

            $(selectors.item).each(function () {
                const $item = $(this);

                if ($item.find('#menuSearch').length) return;

                const $title = $item.find(selectors.title).first();
                if (!$title.length) return;

                const text = $title.text().toLowerCase();
                const isMatch = text.includes(term);

                const $submenu = $item.children(selectors.collapse).find('ul.nav').first();
                const hasChildren = $submenu.length && $submenu.find(selectors.item).length > 0;

                if (hasChildren) {
                    let childHasMatch = false;

                    $submenu.find(selectors.item).each(function () {
                        const $child = $(this);
                        const childText = $child.find(selectors.title).first().text().toLowerCase();

                        if (childText.includes(term)) {
                            $child.show();
                            childHasMatch = true;
                            hasAnyMatch = true;
                        } else {
                            $child.hide();
                        }
                    });

                    if (isMatch || childHasMatch) {
                        $item.show().children(selectors.collapse).addClass('show');
                        hasAnyMatch = true;
                    } else {
                        $item.hide();
                    }
                } else {
                    if (isMatch) {
                        $item.show();
                        hasAnyMatch = true;
                    } else {
                        $item.hide();
                    }
                }
            });

            if (!hasAnyMatch) {
                $noResults.removeClass('d-none');
            } else {
                $noResults.addClass('d-none');
            }
        });

        $search.on('keydown', function (e) {
            if (e.key === 'Escape') {
                $(this).val('').trigger('input');
            }
        });
        // $('.search-icon').on('click', function () {
        //     if ($('body').hasClass('sidebar-icon-only')) {
        //         $('body').removeClass('sidebar-icon-only'); // Expand sidebar
        //         $('#menuSearch').focus(); // Focus on search input
        //     }
        //         });
    });
</script>

<!-- 🎯 Intro.js Tour Library (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/intro.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    // 🎯 Dashboard Tour Function
    // Usage: Call startDashboardTour() to begin the tour
    // Add data-intro and data-step attributes to elements you want to highlight
    function startDashboardTour(options = {}) {
        const defaultOptions = {
            prevLabel: '{{__("Previous")}}',
            nextLabel: '{{__("Next")}}',
            skipLabel: '{{__("Skip")}}',
            doneLabel: '{{__("Done")}}',
            showProgress: true,
            showBullets: true,
            exitOnOverlayClick: false,
            exitOnEsc: true,
            keyboardNavigation: true,
            tooltipClass: 'customTooltip',
            highlightClass: 'customHighlight',
            disableInteraction: false
        };

        const tourOptions = Object.assign({}, defaultOptions, options);
        
        if (typeof introJs !== 'undefined') {
            introJs().setOptions(tourOptions).start();
        }
    }

    // Auto-start tour on page load if data-auto-tour attribute exists
    $(document).ready(function() {
        if ($('body').attr('data-auto-tour') === 'true') {
            setTimeout(function() {
                startDashboardTour();
            }, 1000);
        }
    });

</script>





@yield('scripts')
@stack('scripts')
{!! \App\Services\AdminTheme\MetaDataHelpers::Init()->getThemesScriptsJs('metronic','footer') !!}

<!-- footer for PWA -->
@yield('pwa-footer')
</body>
</html>
