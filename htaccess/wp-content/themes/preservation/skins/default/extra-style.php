<?php
// Add plugin-specific colors and fonts to the custom CSS
if (!function_exists('preservation_cf7_get_css')){
add_filter('preservation_filter_get_css', 'preservation_cf7_get_css', 10, 2);
	function preservation_cf7_get_css($css, $args) {
        if (isset($css['fonts']) && isset($args['fonts'])) {
            $fonts = $args['fonts'];
            $css['fonts'] .= <<<CSS

.sc_services.sc_services_default .sc_services_item_number,
.sc_services.sc_services_hover .sc_services_item_number,
.sc_services.sc_services_panel .sc_services_item .sc_services_item_number,
.sc_services.sc_services_alter .sc_services_item_number,
.sc_services.sc_services_modern .sc_services_item_number,
.sc_services.sc_services_breezy .sc_services_item_number,
.sc_services.sc_services_cool .sc_services_item_number,
.sc_services.sc_services_extra .sc_services_item_number,
.sc_services.sc_services_strong .sc_services_item_number,
.sc_services.sc_services_minimal .sc_services_item_number,
.sc_services.sc_services_creative .sc_services_item_number,
.sc_services.sc_services_shine .sc_services_item_number,
.sc_services.sc_services_motley .sc_services_item_number,
.sc_services.sc_services_classic .sc_services_item_number,
.sc_services.sc_services_fashion .sc_services_item_number,
.sc_services.sc_services_backward .sc_services_item_number,
.sc_services.sc_services_accent .sc_services_item_number,
.sc_services.sc_services_price .sc_services_item_number,
.sc_services.sc_services_price2 .sc_services_item_number {
    {$fonts['decoration_font-family']}
}

CSS;
        }

        return $css;
    }
}

