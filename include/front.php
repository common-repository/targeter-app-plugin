<?php
/**
 * Add hooks declarations
 */
add_action('wp_print_scripts', 'tg_add_ajax_script'); // Include JS file to do ajax calls on admin (same function called from front.php)

/**
 * Add some shortcodes
 */
//add_shortcode('skeleton', 'tg_shortcode');

/**
 * Converts shortcode to text
 *
 * @param array  $atts     Shortcode attributes ('num' in this case)
 * @param string $content  Shortcode content for enclosed ones (always '' in this case)
 * @return string          Replacement string for our shortcode
 */
function tg_shortcode($atts, $content = null) {
    // This shortcode takes an attribute : "num"
    extract(shortcode_atts(array('num' => 0), $atts));

    if(! $num = absint($num))
        $num = '';

    return sprintf(TG_STR_SHORTCODE, $num);
}