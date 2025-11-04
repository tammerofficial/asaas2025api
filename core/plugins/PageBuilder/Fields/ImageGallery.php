<?php


namespace Plugins\PageBuilder\Fields;


use Plugins\PageBuilder\Helpers\Traits\FieldInstanceHelper;
use Plugins\PageBuilder\PageBuilderField;

class ImageGallery extends PageBuilderField
{
    use FieldInstanceHelper;

    /**
     * render field markup
     * */
    public function render()
    {
        $output = '';
        $output .= $this->field_before();
        $output .= $this->label();
        
        $output .= '<div class="media-upload-btn-wrapper mb-2">';
        $output .= '<div class="img-wrap">';
        
        // Display existing gallery images
        $value = $this->value();
        if (!empty($value)) {
            $output .= render_gallery_image_attachment_preview($value);
        }
        
        $output .= '</div>';
        $output .= '<input type="hidden" id="' . $this->name() . '" name="' . $this->name() . '" value="' . $value . '">';
        $output .= '<button type="button" class="btn btn-info media_upload_form_btn" ';
        $output .= 'data-btntitle="' . __('Select Images') . '" ';
        $output .= 'data-modaltitle="' . __('Upload Images') . '" ';
        $output .= 'data-toggle="modal" ';
        $output .= 'data-mulitple="true" ';
        $output .= 'data-target="#media_upload_modal" ';
        $output .= 'data-fieldid="' . $this->name() . '">';
        $output .= __('Select Images') . '</button>';
        $output .= '</div>';
        
        if (isset($this->args['dimensions'])) {
            $output .= '<small class="form-text text-muted">' . __('recommended image size is') . ' ' . $this->args['dimensions'] . '</small>';
        } else {
            $output .= '<small class="form-text text-muted">' . __('allowed image format: jpg,jpeg,png') . '</small>';
        }

        $output .= $this->field_after();

        return $output;
    }
}
