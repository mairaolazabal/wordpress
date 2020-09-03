<?php

namespace Mesmerize;


class HeaderSliderControl extends \Kirki_Customize_Control
{
    
    public $type = "header-slider";
    public $slide_label = '';
    public $limit = 7;
    
    public function __construct($manager, $id, $args = array())
    {
        
        parent::__construct($manager, $id, $args);
        
        $this->slide_label = isset($args['row_label']) ? ucfirst($args['row_label']) : 'Slide';
        if (isset($args['choices']['max_elements'])) {
            $this->limit = $args['choices']['max_elements'];
        }
        
    }
    
    public function enqueue()
    {
        
        $jsRoot = mesmerize_pro_uri("/customizer/assets/js");
//        wp_enqueue_script('customizer-section-settings-controls', plugins_url('mesmerize-companion/assets/js/customizer/customizer-section-settings-controls.js'));
        wp_enqueue_script('slider-control', $jsRoot . "/slider-control.js");
        
    }
    
    public function json()
    {
        
        $json                     = parent::json();
        $json['add_button_label'] = 'Add new ' . $this->slide_label;
        $json['slide_label']      = $this->slide_label;
        $json['limit']            = $this->limit;
        $json['overlay_shapes']   = mesmerize_get_header_shapes_overlay();
        
        $json['overlay_shapes_values'] = array();
        foreach ($json['overlay_shapes'] as $k => $shape) {
            if ($k != 'none') {
                $json['overlay_shapes_values'][$k] = mesmerize_get_header_shape_overlay_value($k);
            } else {
                $json['overlay_shapes_values']['none'] = 'none';
            }
        }
        $json['default_slide'] = $this->get_default_slide();
        
        return $json;
    }
    
    protected function content_template()
    {
        ?>

        <label>
            <# if ( data.label ) { #>
            <span class="customize-control-title">{{{ data.label }}}</span>
            <# } #>
        </label>
        <ul class="slider-fields"></ul>
        <button class="button-secondary slider-add">{{{ data.add_button_label }}}</button>
        
        <?php
    }
    
    protected function get_default_slide()
    {
        $default = require mesmerize_pro_dir("/inc/header-options/slider-options/default-slide.php");
        return $default;
    }
    
}
