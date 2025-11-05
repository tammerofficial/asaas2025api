<div class="header-menu-section common-header-menu-section align-{{$data['alignment']}} {{$data['extra_class']}}"
     data-padding-top="{{$data['padding_top']}}"
     data-padding-bottom="{{$data['padding_bottom']}}"
     id="{{$data['section_id']}}">
    <nav class="header-menu-nav">
        {!! render_frontend_menu($data['menu_id']) !!}
    </nav>
</div>

<style>
.common-header-menu-section.align-left { justify-content: flex-start; display: flex; }
.common-header-menu-section.align-center { justify-content: center; display: flex; }
.common-header-menu-section.align-right { justify-content: flex-end; display: flex; }
.header-menu-nav ul { 
    display: flex; 
    gap: 24px; 
    align-items: center; 
    list-style: none;
    margin: 0;
    padding: 0;
}
.header-menu-nav ul li { 
    list-style: none !important;
    margin: 0;
    padding: 0;
    display: inline-block !important;
}
.header-menu-nav ul li a { 
    color: var(--heading-color, #111); 
    font-weight: 500; 
    text-decoration: none;
    display: inline-block;
    position: relative;
    padding: 8px 0;
    transition: all 0.3s ease;
}
.header-menu-nav ul li a:hover { 
    color: var(--main-color-one, #007bff); 
}
.header-menu-nav ul li a::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background-color: #fff;
    transition: width 0.3s ease;
}
.header-menu-nav ul li a:hover::after {
    width: 100%;
}
@media (max-width: 991px) {
  .header-menu-nav ul { flex-wrap: wrap; gap: 12px; }
}
</style>


