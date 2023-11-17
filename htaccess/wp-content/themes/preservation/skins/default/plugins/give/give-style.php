<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'preservation_give_get_css' ) ) {
	add_filter( 'preservation_filter_get_css', 'preservation_give_get_css', 10, 2 );
	function preservation_give_get_css( $css, $args ) {

		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS

#give-recurring-form .form-row input[type="email"],
#give-recurring-form .form-row input[type="password"],
#give-recurring-form .form-row input[type="tel"],
#give-recurring-form .form-row input[type="text"],
#give-recurring-form .form-row input[type="url"],
#give-recurring-form .form-row select,
#give-recurring-form .form-row textarea,
form.give-form .form-row input[type="email"],
form.give-form .form-row input[type="password"],
form.give-form .form-row input[type="tel"],
form.give-form .form-row input[type="text"],
form.give-form .form-row input[type="url"],
form.give-form .form-row select,
form.give-form .form-row textarea,
form[id*="give-form"] .form-row input[type="email"],
form[id*="give-form"] .form-row input[type="password"],
form[id*="give-form"] .form-row input[type="tel"],
form[id*="give-form"] .form-row input[type="text"],
form[id*="give-form"] .form-row input[type="url"],
form[id*="give-form"] .form-row select,
form[id*="give-form"] .form-row textarea {
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
	{$fonts['input_text-transform']}
	{$fonts['input_letter-spacing']}
}
.wp-widget-give_forms_widget button.js-give-embed-form-modal-opener,
.wp-widget-give_forms_widget a.js-give-embed-form-modal-closer,
.give-btn {
	{$fonts['button_font-family']}
	{$fonts['button_font-size']}
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}

#give-recurring-form h3.give-section-break,
#give-recurring-form h4.give-section-break,
#give-recurring-form legend, form.give-form h3.give-section-break,
form.give-form h4.give-section-break, form.give-form legend, 
form[id*="give-form"] h3.give-section-break,
form[id*="give-form"] h4.give-section-break,
form[id*="give-form"] legend,
[id*="give-form"] fieldset legend,
form[id*="give-form"] .give-donation-amount .give-currency-symbol,
form[id*="give-form"] .give-donation-amount #give-amount,
form[id*="give-form"] .give-donation-amount #give-amount-text,
form[id*="give-form"] #give-final-total-wrap .give-donation-total-label,
form[id*="give-form"] #give-final-total-wrap .give-final-total-amount,
.give-card__category span,
[id*="give-form"] .give-goal-progress .raised,
.give-wrap .give-card__progress .raised {
	{$fonts['h5_font-family']}
}
CSS;
		}

		return $css;
	}
}

