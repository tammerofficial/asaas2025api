<div class="header-logo-section common-header-logo-section align-{{$data['alignment']}}"
     data-padding-top="{{$data['padding_top']}}"
     data-padding-bottom="{{$data['padding_bottom']}}"
     id="{{$data['section_id']}}">
    <a href="{{$data['link_url']}}" class="header-logo-link">
        {!! render_image_markup_by_attachment_id($data['logo_id'], 'full', '', $data['alt_text']) !!}
    </a>
</div>

<style>
.common-header-logo-section.align-left { justify-content: flex-start; display: flex; }
.common-header-logo-section.align-center { justify-content: center; display: flex; }
.common-header-logo-section.align-right { justify-content: flex-end; display: flex; }
.header-logo-link img { max-height: 50px; width: auto; display: block; }
@media (max-width: 768px) {
  .header-logo-link img { max-height: 42px; }
}
</style>


