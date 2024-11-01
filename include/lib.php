<?php

/**

 * Add hooks declarations

 */

//add_action(TG_CRON_EVENT, 'tg_send_mail');

//add_action('init', 'tg_add_post_type');



/**

 * Cron job function handler

 *

 * This function will run once a day (or twice, or even once an hour) AND once at each pressure on the button available in admin panel

 * In this example, it sends a mail to the site admin

 *

 * @param   bool  $manual     Is this a manual call or not?

 */

function tg_send_mail($manual = FALSE) {

    // Retrieve plugin options to check if we have to show ui or not

    $options = get_option('tg_settings');



    // Default value = TRUE

    if(!isset($options['send_mail']) || !empty($options['send_mail'])) {

        $admin_email = get_bloginfo('admin_email');

        $message = 'This mail has been '.($manual ? 'manually' : 'automatically').' sent.';

        wp_mail($admin_email, 'This is a Cron Job example', $message);

    }

}

function tg_add_script(){

 $options = get_option('tg_settings');

 if($options['tg_client_id'] and $options['tg_enabled']):

 ?>
<script type="text/javascript">
  window._TA=function(b,g){var a=document;return/_TAC=1/.test(a.cookie)||/_TAC=1/.test(location.search)||a.getElementById(b)?window._TA:window._TA||function(){var e={_d:"//sets.targeterapp.com",_e:[],ready:function(a){this._e.push(a)},load:function(b,c,e){var d=a.createElement("script"),f=a.getElementsByTagName("script")[0];d.src=(/^\/\//.test(b)?"":this._d)+b;d.setAttribute("async","async");"string"===typeof c&&(d.id=c,c=e);"function"===typeof c&&(d.onload=c);f.parentNode.insertBefore(d,f)}};e.load("/?a="+
g+"&v=2",b);return e}()}.call(window,"ta-jssdk","<?=$options['tg_client_id']?>");
</script>
<?

 endif;

}

/**

 * Initialization function

 *

 * This function will add custom post types and taxonomies to handle some more contents and not only posts or pages

 *

 */

function tg_add_post_type() {

    // Retrieve plugin options to check if we have to show ui or not

    $options = get_option('tg_settings');



    // Default value = TRUE

    $show_ui = !isset($options['show_example_ui']) || !empty($options['show_example_ui']) ? TRUE : FALSE;



    // A custom non-hierarchical (like 'posts') post type (see http://codex.wordpress.org/Function_Reference/register_post_type for all available options)

    register_post_type(TG_CFG_EXAMPLE_POST_TYPE, array(

        'label' => TG_STR_LABEL_EXAMPLES,

        'public' => TRUE,

        'show_ui' => $show_ui,

        'has_archive' => TRUE,

        'rewrite' => array(

            'slug' => 'examples',

        ),

        'supports' => array('title', 'editor', 'comments', 'author', 'thumbnail')

    ));

    // A custom hierarchical (like 'categories') taxonomy (see http://codex.wordpress.org/Function_Reference/register_taxonomy for all available options)

    register_taxonomy( TG_CFG_FAMILY_TAXONOMY, TG_CFG_EXAMPLE_POST_TYPE, array(

        'hierarchical' => TRUE,

        'label' => TG_STR_LABEL_FAMILIES,

        'query_var' => TRUE,

        'rewrite' => array(

            'slug' => 'family',

        ),

    ));

    // We can apply existing taxonomy to an existing post type : tags apply to Examples

    register_taxonomy_for_object_type('post_tag', TG_CFG_EXAMPLE_POST_TYPE);

}



/**

 * Adding JS for ajax

 *

 * This function adds some JS files to do AJAX calls from admin or front

 *

 */

function tg_add_ajax_script() {

    // Embed the javascript file that makes the AJAX request

    wp_enqueue_script( 'tg_ajax', TG_URL . '/js/ajax.js', array( 'jquery' ) );



    wp_localize_script( 'tg_ajax', 'TG_AJAX', array(

        // URL to wp-admin/admin-ajax.php to process the request

        'ajaxurl'          => admin_url( 'admin-ajax.php' ),



        // generate a nonce with a unique ID "tg_ajax_nonce"

        // so that you can check it later when an AJAX request is sent

        'nonce' => wp_create_nonce( 'tg_ajax_nonce' ),

    ));

}