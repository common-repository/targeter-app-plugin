<?php

/*

Plugin Name: Targeter App

Plugin URI: http://targeterapp.com

Description: This is a plugin for embedding the Targeter App code snippet

Version: 0.4

Author: Somesh Mukherjee

Author URI: http://somesh.info

*/



/**

 * Some configuration constants and/or variables included in this file

 */

require 'include/config.php';



/**

 * Load translation module (before lang.php inclusion)

 */

load_plugin_textdomain(TG_DOMAIN, FALSE, TG_PATH . '/translation' );



/**

 * On admin or front, we need the global library, the lang file and widgets declaration

 */

require 'include/lang.php';

require 'include/lib.php';

require 'include/widget.php';



/*Add a hook on wp head to insert the script */

 add_action('wp_head', 'tg_add_script',1);

/**

 * On the admin pages, we need the admin library, on front, we need front one

 */

if(is_admin()) {

    require 'include/admin.php';

}

else {

    require 'include/front.php';

}



/**

 * Function Activate doesn't do anything. Its a skeleton we might need to use later

 * 

 *

 */

 





function tg_activate() {

   



}



/**

 * Function called at plugin deactivation

 *

 * This function could drop tables, delete options, unregister scheduled actions etc, but doesn't do anything now

 *

 */

function tg_deactivate() {



}



// Register (de)activation functions

register_activation_hook( __FILE__, 'tg_activate' );

register_deactivation_hook( __FILE__, 'tg_deactivate' );

?>