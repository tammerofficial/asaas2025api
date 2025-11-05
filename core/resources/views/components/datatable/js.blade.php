<!-- Start datatable js -->
<!-- Ensure jQuery is loaded before DataTables -->
<script>
    // Wait for jQuery to be available
    (function() {
        function loadDataTables() {
            if (typeof jQuery === 'undefined') {
                // jQuery not loaded yet, wait a bit and try again
                setTimeout(loadDataTables, 100);
                return;
            }
            
            // jQuery is loaded, now load DataTables
            var scripts = [
                '{{global_asset("assets/common/js/jquery.dataTables.js")}}',
                '{{global_asset("assets/common/js/jquery.dataTables.min.js")}}',
                '{{global_asset("assets/common/js/dataTables.bootstrap4.min.js")}}',
                '{{global_asset("assets/common/js/dataTables.responsive.min.js")}}',
                '{{global_asset("assets/common/js/responsive.bootstrap.min.js")}}'
            ];
            
            var loaded = 0;
            scripts.forEach(function(src) {
                var script = document.createElement('script');
                script.src = src;
                script.onload = function() {
                    loaded++;
                    if (loaded === scripts.length) {
                        // All DataTables scripts loaded, initialize
                        (function($){
                            "use strict";
                            $(document).ready(function() {
                                $('.table-wrap > table').DataTable( {
                                    "order": [[0, "desc" ]],
                                    'columnDefs' : [{
                                        'targets' : 'no-sort',
                                        "orderable" : false
                                    }],
                                    'language': translatedDataTable()
                                });
                            });
                        })(jQuery);
                    }
                };
                document.head.appendChild(script);
            });
        }
        
        // Start loading after DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', loadDataTables);
        } else {
            loadDataTables();
        }
    })();
</script>
