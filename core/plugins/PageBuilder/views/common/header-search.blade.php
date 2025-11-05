<div class="header-search-section common-header-search-section style-{{$data['style']}}"
     data-padding-top="{{$data['padding_top']}}"
     data-padding-bottom="{{$data['padding_bottom']}}"
     id="{{$data['section_id']}}">
    @if($data['style'] === 'inline')
        <form class="header-search-form" action="{{ url()->current() }}" method="GET">
            <input type="text" name="q" class="search-input" placeholder="{{$data['placeholder']}}">
            <button type="submit" class="search-btn"><i class="las la-search"></i></button>
        </form>
    @else
        <button class="header-search-toggle"><i class="las la-search"></i></button>
        <div class="header-search-dropdown" style="display:none;">
            <form class="header-search-form" action="{{ url()->current() }}" method="GET">
                <input type="text" name="q" class="search-input" placeholder="{{$data['placeholder']}}">
                <button type="submit" class="search-btn"><i class="las la-search"></i></button>
            </form>
        </div>
        <script>
            (function(){
                const btn = document.currentScript.previousElementSibling.previousElementSibling;
                const dropdown = document.currentScript.previousElementSibling;
                if (btn && dropdown) {
                    btn.addEventListener('click', function(){
                        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
                    });
                }
            })();
        </script>
    @endif
</div>

<style>
.common-header-search-section { display:flex; align-items:center; }
.header-search-form { display:flex; align-items:center; gap:8px; }
.header-search-form .search-input { border:1px solid #ddd; border-radius:6px; padding:8px 12px; min-width:220px; }
.header-search-form .search-btn { background:var(--main-color-one, #007bff); color:#fff; border:none; border-radius:6px; padding:8px 12px; }
.header-search-toggle { background:transparent; border:none; font-size:20px; }
.header-search-dropdown { position:absolute; margin-top:10px; z-index:50; background:#fff; padding:10px; border-radius:8px; box-shadow:0 10px 30px rgba(0,0,0,.1); }
@media (max-width: 768px) { .header-search-form .search-input { min-width:160px; } }
</style>


