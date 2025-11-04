<footer class="footer">
    <div class="container-fluid d-flex justify-content-between">
        <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">
            {!! get_footer_copyright_text() !!}
        </span>
        <span class="float-none float-sm-end mt-1 mt-sm-0 text-end">
            v- <strong>{{get_static_option_central('get_script_version')}}</strong>
        </span>
    </div>
</footer>
</div>
</div>
</div>

<!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
<!-- 🚀 CDN JAVASCRIPT LIBRARIES (Fast & Cached) -->
<!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->

<!-- jQuery CDN (3.7.1 - Latest Stable) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js" 
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
        crossorigin="anonymous"></script>

<!-- Bootstrap Bundle CDN (if needed) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" 
        crossorigin="anonymous" 
        defer></script>

<!-- Axios CDN -->
<script src="https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/axios.min.js" 
        integrity="sha256-VQ8m0Dd2xi0z6QuAKMa04ufRMBxv92nP+UWSqT33HGg=" 
        crossorigin="anonymous" 
        defer></script>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.all.min.js" 
        integrity="sha256-iSkyJ41luwYhZX4JnDUop92wix0y8SBGAW5tCnnCfZ4=" 
        crossorigin="anonymous" 
        defer></script>

<!-- Flatpickr CDN -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js" 
        crossorigin="anonymous" 
        defer></script>
<x-flatpicker.flatpickr-locale/>

<!-- Select2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" 
        crossorigin="anonymous" 
        defer></script>

<!-- Toastr CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" 
        integrity="sha512-lbwH47l/tPXJYG9AcFNoJaTMhGvYWhVM9YI43CT+uteTRRaiLCui8snIgyAN8XWgNjNhCqlAUdzZptso6OCoFQ==" 
        crossorigin="anonymous" 
        defer></script>

<!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
<!-- 📦 LOCAL JAVASCRIPT (Custom & Required) -->
<!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->

<!-- Local scripts that depend on jQuery/Bootstrap -->
<script src="{{global_asset('assets/landlord/admin/js/hoverable-collapse.js')}}" defer></script>
<script src="{{global_asset('assets/landlord/admin/js/off-canvas.js')}}" defer></script>
<script src="{{global_asset('assets/landlord/admin/js/misc.js')}}" defer></script>
<script src="{{global_asset('assets/common/js/fontawesome-iconpicker.min.js')}}" defer></script>
<script src="{{global_asset('assets/landlord/admin/js/jquery.nice-select.min.js')}}" defer></script>

<!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
<!-- ⚡ INITIALIZATION SCRIPT (Optimized) -->
<!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->

<script>
// Wait for all scripts to load
document.addEventListener('DOMContentLoaded', function() {
    // Check if jQuery is loaded
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }

    function translatedDataTable() {
        return {
            "sEmptyTable": "{{__('No data available in table')}}",
            "sInfo": "{{__('Showing')}} _START_ {{__('to')}} _END_ {{__('of')}} _TOTAL_ {{__('entries')}}",
            "sInfoEmpty": "{{__('Showing 0 to 0 of 0 entries')}}",
            "sInfoFiltered": "({{__('filtered from')}} _MAX_ {{__('total entries')}})",
            "sInfoThousands": ",",
            "sLengthMenu": "{{__('Show')}} _MENU_ {{__('entries')}}",
            "sLoadingRecords": "{{__('Loading...')}}",
            "sProcessing": "{{__('Processing...')}}",
            "sSearch": "{{__('Search:')}}",
            "sZeroRecords": "{{__('No matching records found')}}",
            "oPaginate": {
                "sFirst": "{{__('First')}}",
                "sLast": "{{__('Last')}}",
                "sNext": "{{__('Next')}}",
                "sPrevious": "{{__('Previous')}}"
            },
            "oAria": {
                "sSortAscending": "{{__(': activate to sort column ascending')}}",
                "sSortDescending": "{{__(': activate to sort column descending')}}"
            }
        }
    }

    (function ($) {
        "use strict";

        $(document).ready(function ($) {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            $("body").tooltip({selector: '[data-bs-toggle=tooltip]'});
            
            // Initialize Select2 (with error handling)
            if (typeof $.fn.select2 !== 'undefined') {
                $('select.select2').select2();
            }

            // SweetAlert Delete Button
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

            // SweetAlert Change Language Button
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

            // SweetAlert Change Approve Payment Button
            $(document).on('click', '.swal_change_approve_payment_button', function (e) {
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

            // SweetAlert Change Tenant Status
            $(document).on('click', '.swal_status_change', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: $(this).data('title') ?? '{{__("Are you sure to change the status")}}',
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

            // SweetAlert Change Language Edit Title
            $(document).on('click', '.swal_change_language_edit_button', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure to make this language as a default language?")}}',
                    text: '{{__("Languages will be turn changed as default")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1F51FF',
                    cancelButtonColor: '#D2042D',
                    confirmButtonText: "{{__('Yes, Update it!')}}",
                    cancelButtonText: "{{__('Cancel')}}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            // Sidebar search functionality
            $(document).on('click', '.sidebar-search-wrap .icon', function () {
                let searchInput = $('.sidebar-search-input');
                if (searchInput.hasClass('d-none')) {
                    searchInput.removeClass('d-none');
                    searchInput.focus();
                } else {
                    searchInput.addClass('d-none');
                    searchInput.val('');
                    $('.nav-item').show();
                    $('.nav-menu-title').show();
                    $('.no-match-message').addClass('d-none');
                }
            });

            $(document).on('input', '.sidebar-search-input', function () {
                let $search = $(this);
                let $sidebar = $('.sidebar-wrapper');
                let $noResults = $sidebar.find('.no-match-message');
                let searchValue = $search.val().toLowerCase();

                if (searchValue.length === 0) {
                    $('.nav-item').show();
                    $('.nav-menu-title').show();
                    $noResults.addClass('d-none');
                    return;
                }

                $('.nav-menu-title').hide();

                let hasAnyMatch = false;

                $sidebar.find('.nav-item').each(function () {
                    let $item = $(this);
                    let text = $item.find('.menu-title').text().toLowerCase();

                    if (text.indexOf(searchValue) !== -1) {
                        $item.show();
                        hasAnyMatch = true;
                    } else {
                        $item.hide();
                    }
                });

                if (!hasAnyMatch) {
                    $noResults.removeClass('d-none');
                } else {
                    $noResults.addClass('d-none');
                }
            });

            $(document).on('keydown', '.sidebar-search-input', function (e) {
                if (e.key === 'Escape') {
                    $(this).val('').trigger('input');
                }
            });
        });
    })(jQuery);
</script>

@yield('scripts')

<!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
<!-- 📊 Performance Monitoring (Optional) -->
<!-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ -->
@if(config('app.debug') && config('app.env') === 'local')
<script>
    // Log page load performance
    window.addEventListener('load', function() {
        if (window.performance) {
            const perfData = window.performance.timing;
            const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
            const connectTime = perfData.responseEnd - perfData.requestStart;
            const renderTime = perfData.domComplete - perfData.domLoading;
            
            console.group('⚡ Page Performance');
            console.log('Total Load Time:', (pageLoadTime / 1000).toFixed(2) + 's');
            console.log('Server Response:', (connectTime / 1000).toFixed(2) + 's');
            console.log('DOM Render Time:', (renderTime / 1000).toFixed(2) + 's');
            console.groupEnd();
        }
    });
</script>
@endif

</body>
</html>

