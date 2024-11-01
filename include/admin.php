<?php
/**
 * Add hooks declarations
 */
add_action('admin_menu', 'tg_add_admin_menu');
add_action('admin_init', 'tg_register_settings');
add_action('init', 'tg_add_shortcode_button');

// Handle AJAX requests, even for front ones
add_action('wp_ajax_tg_ajax', 'tg_ajax');
add_action('wp_ajax_nopriv_tg_ajax', 'tg_ajax');

add_action('admin_print_scripts', 'tg_add_ajax_script'); // Include JS file to do ajax calls on admin (same function called from front.php)

/**
 * Menu manager function
 *
 * This function adds a subpage of 'settings' menu in the WordPress administration
 *
 */
 function tg_warning() {
           ?>
		   <div id='targeter-warning' class='updated fade'><p><strong>Targeter App Client Id is not set! Please set it to use Targeter App on your blog
		   </strong></p></div>
		   
		   <?
		   
        }
        $options = get_option('tg_settings');
		if(!$options['tg_client_id'])
		   add_action('admin_notices', 'tg_warning'); 
		
function tg_add_admin_menu() {
    // Setup the settings page
    $mypage = add_options_page(TG_STR_ADMIN_MENU_PAGE_TITLE, TG_STR_ADMIN_MENU_TITLE, 'manage_options', 'tg_admin_menu', 'tg_display_admin_menu');

    // Add some scripts on this admin panel
    add_action('admin_print_scripts-'.$mypage, 'tg_admin_menu_script' );
    // Add some style to this admin panel
    add_action('admin_print_styles-'.$mypage, 'tg_admin_menu_style' );

    // Add a link to settings page in plugins list
    add_filter('plugin_action_links', 'tg_add_settings_link', 10, 2);
}

/**
 * Settings API registering function
 *
 * This function registers the settings group, section and fields for the plugin
 *
 */
function tg_register_settings() {
    // We need a group, with the option name and data validation function (so that WordPress can automatically save our option)
    register_setting( TG_CFG_SETTINGS_GROUP, 'tg_settings', 'tg_settings_validate' );
    // We need a section (regroupment of fields) with an id, a title, a description displaying function and a name of page on which it will be shown
    add_settings_section('tg_section', TG_STR_SETTINGS_SECTION_TITLE, 'tg_settings_description', TG_CFG_SETTINGS_SECTION);

    // We can now add our different fields
    // in the section we created earlier.
    // We pass an id, a label, a callback to display input (or other form element needed), the section page and section id
    add_settings_field('tg_client_id', TG_STR_CLIENT_ID, 'tg_show_client_id_field', TG_CFG_SETTINGS_SECTION, 'tg_section');
   add_settings_field('tg_enabled', TG_STR_ENABLED, 'tg_enabled_field', TG_CFG_SETTINGS_SECTION, 'tg_section');
}

/**
 * Options validation
 *
 * This function validates the options posted before saving them :
 * show_example_ui must be either 0 or 1
 *
 * @param   array  $input    The options posted by the user
 * @return  array  $newinput The options to save in DB
 */
function tg_settings_validate($input) {
    // Retrieve current options
    $newinput = get_option('tg_settings');
	$newinput['tg_client_id'] = trim($input['tg_client_id']);
   $newinput['tg_enabled'] = trim($input['tg_enabled']);
    // Validate show_example_ui (0 or 1)
   /* $newinput['show_example_ui'] = trim($input['show_example_ui']);
    if(!in_array($newinput['show_example_ui'], array(0, 1))) {
        $newinput['show_example_ui'] = 1;
    }

    // Validate send_mail (0 or 1)
    $newinput['send_mail'] = trim($input['send_mail']);
    if(!in_array($newinput['send_mail'], array(0, 1))) {
        $newinput['send_mail'] = 1;
    }
*/
    return $newinput;
}

/**
 * Settings description
 *
 * This function echoes a little description of the settings panel for the plugin, just after the title.
 *
 */
function tg_settings_description() {
    echo '<p>'.TG_STR_SETTINGS_DESCRIPTION.'</p>';
}

/**
 * 'Show Client ID' field
 *
 * This function echoes the field for the 'Client ID' field in the settings form
 *
 */
function tg_show_client_id_field() {
    // Retrieve options
    $options = get_option('tg_settings');

    // Default value = 1
    $val = $options['tg_client_id'];

    // Display field
    
    echo '<input id="client_id_text" name="tg_settings[tg_client_id]" type="text" value="'.$val.'"/>';
    
}

/**
 * 'Enabled' field
 *
 * This function echoes the field for the 'Enabled' field in the settings form
 *
 */
function tg_enabled_field() {
    // Retrieve options
    $options = get_option('tg_settings');

    // Default value = 1
    $val = !isset($options['tg_enabled']) || !empty($options['tg_enabled']) ? 1 : 0;

    // Display field
    echo '<select name="tg_settings[tg_enabled]">';
    echo '<option value="1" '.($val ? 'selected':'').'>'.TG_STR_YES.'</option>';
    echo '<option value="0" '.($val ? '':'selected').'>'.TG_STR_NO.'</option>';
    echo '</select>';
}

/**
 * Admin menu displaying function
 *
 * This function handles the printing of HTML code for the admin menu
 *
 */
function tg_display_admin_menu() {
    // Security check
    if (!current_user_can('manage_options'))  {
        wp_die( TG_STR_ERROR_RIGHT_ACCESS );
    }

    echo '<div class="wrap">';
    // Header
    echo '<div id="icon-themes" class="icon32"><br></div>';

    // Title beginning
    echo '<h2>'.TG_STR_ADMIN_MENU_PAGE_TITLE;

    
    

    // Title ending
    echo '</h2>';

    // Handling data submission for manual cron job launching
    if (!empty($_POST)) {
        // Launch manual cron!
        if(!empty($_POST['tg_launch_cron'])) {
            wp_schedule_single_event(time(), TG_CRON_EVENT, array(TRUE, time()));
        }
    }

    // Global form
    echo '<form method="post" action="options.php">';

    // Needed to display security hidden fields (_wp_nonce, referer etc.)
    settings_fields( TG_CFG_SETTINGS_GROUP );
    // Display registered settings fields
    do_settings_sections( TG_CFG_SETTINGS_SECTION );

    // Submit button
    echo '<p class="submit">
    <input type="submit" class="button-primary" value="'.TG_STR_SAVE_CHANGES.'" />
    </p>';

    echo '</form>'; // Global form end

    echo '</div>'; // .wrap
}

/**
 * Admin menu styles
 *
 * This function adds some stylesheets only on the plugin admin menu
 *
 */
function tg_admin_menu_style() {
    wp_enqueue_style('tg_admin_menu_style', TG_URL.'/css/admin.css');
}

/**
 * Admin menu scripts
 *
 * This function adds some JS files only on the plugin admin menu
 *
 */
function tg_admin_menu_script() {
    // wp_enqueue_script('tg_admin_menu_script', TG_URL.'/js/admin.js');
}

/**
 * Adds a link to settings page
 *
 * This function adds a link in the plugins page of WordPress, near the 'deactivate' link, pointing to our settings page
 *
 * @param  array  $links The current links that will be displayed for this plugin
 * @param  string $file  The current plugin file name (bootstrap)
 * @return array  $links The links to display, potentially with new ones
 */
 
function tg_add_settings_link($links, $file) {
    // Retrieve the folder only (we don't need the php file)
    $file = explode('/', $file);
    $file = $file[0];

    // Check if it's ours, and add our link
    if( $file == TG_PATH ) {
        $settings_link = '<a href="options-general.php?page=tg_admin_menu">'.TG_STR_SETTINGS.'</a>';
        array_unshift( $links, $settings_link ); // before other links
    }

    return $links;
}

/**
 * Attach shortcode button
 *
 * @return boolean
 */
function tg_add_shortcode_button() {
    // Only for users with rights
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return FALSE;
    }

    // Has user WYSIWYG enabled?
    if (get_user_option('rich_editing')) {
        add_filter('mce_external_plugins', 'tg_attach_button_script');
        add_filter('mce_buttons', 'tg_register_button');

        // We need some strings in the JS, localize it!
        add_filter('mce_external_languages', 'tg_shortcode_localization');

        return TRUE;
    }
}

/**
 * Adds shortcode button js
 *
 * @param  array $plugin_array The existing tinyMCE plugins
 * @return array $plugin_array The plugins array, with ours
 */
function tg_attach_button_script($plugin_array) {
    $plugin_array['tg_shortcode'] = TG_URL.'/js/shortcode.js';

    return $plugin_array;
}

/**
 * Register shortcodes buttons
 *
 * @param  array $buttons The existing tinyMCE buttons
 * @return array $buttons The buttons array, with ours
 */
function tg_register_button($buttons) {
    array_push($buttons, '|', 'tg_shortcode');

    return $buttons;
}

/**
 * Set localization file for shortcode tinymce plugin
 *
 * @return array          The path for language PHP file of our tinyMCE plugin
 */
function tg_shortcode_localization() {
    return array(
        'tg_shortcode' => TG_COMPLETE_PATH.'/include/localize_tinymce.php',
    );
}

/**
 * Ajax response
 *
 * This function returns a little text at each ajax call
 *
 */
function tg_ajax() {
    // Check to see if the submitted nonce matches with the generated nonce we created earlier (see lib.php)
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'tg_ajax_nonce' ) )
        die('Busted!');

    // Return a randomized string
    echo TG_STR_AJAX.' '.mt_rand();

    // We MUST exit
    exit;
}