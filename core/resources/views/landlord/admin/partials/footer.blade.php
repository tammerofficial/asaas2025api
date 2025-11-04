<footer class="footer">
    <div class="container-fluid d-flex justify-content-between">
        <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">
            {!! get_footer_copyright_text() !!}
        </span>
        <span
            class="float-none float-sm-end mt-1 mt-sm-0 text-end"> v- <strong>{{get_static_option_central('get_script_version')}}</strong></span>
    </div>
</footer>
</div>
</div>
</div>

<script src="{{global_asset('assets/landlord/admin/js/vendor.bundle.base.js')}}"></script>
<script src="{{global_asset('assets/landlord/admin/js/hoverable-collapse.js')}}"></script>
<script src="{{global_asset('assets/landlord/admin/js/off-canvas.js')}}"></script>
<script src="{{global_asset('assets/landlord/admin/js/misc.js')}}"></script>
<script src="{{global_asset('assets/landlord/common/js/axios.min.js')}}"></script>
<script src="{{global_asset('assets/landlord/common/js/sweetalert2.js')}}"></script>
<script src="{{global_asset('assets/common/js/flatpickr.js')}}"></script>
<x-flatpicker.flatpickr-locale/>
<script src="{{global_asset('assets/common/js/select2.min.js')}}"></script>
<script src="{{global_asset('assets/common/js/toastr.min.js')}}"></script>
<script src="{{global_asset('assets/common/js/fontawesome-iconpicker.min.js')}}"></script>
<script src="{{global_asset('assets/landlord/admin/js/jquery.nice-select.min.js')}}"></script>

<!-- 🎯 Intro.js Tour Library (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/intro.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function translatedDataTable() {
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

    (function ($) {
        "use strict";

        $(document).ready(function ($) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            $("body").tooltip({selector: '[data-bs-toggle=tooltip]'});
            $('select.select2').select2();

            $(document).on('click', '.swal_delete_button', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure?")}}',
                    text: '{{__("You would not be able to revert this item!")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1F51FF',
                    cancelButtonColor: '#D2042D',
                    confirmButtonText: "{{__('Yes, Accept it!')}}",
                    cancelButtonText: "{{__('Cancel')}}",

                }).then((result) => {
                    if (result.isConfirmed) {
                        let el = $(this);
                        el.removeClass('swal_delete_button btn-danger');
                        el.addClass('btn-secondary');

                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            $(document).on('click', '.swal_change_language_button', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure to make this language as a default language?")}}',
                    text: '{{__("Languages will be turn changed as default")}}',
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

            $(document).on('click', '.swal_change_approve_payment_button', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure to approve this payment?")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#00ce90',
                    cancelButtonColor: '#D2042D',
                    confirmButtonText: "{{__('Yes, Accept it!')}}",
                    cancelButtonText: "{{__('Cancel')}}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            $(document).on('click', '.close', function (e) {
                $('.alert').hide();
            });

            // search bar ==== filtering menu and sub menu
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
            });
        });
    })(jQuery);

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
</body>
</html>
