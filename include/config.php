<?php
/**
 * Remember plugin path & URL
 */
define('TG_PATH', plugin_basename( realpath(dirname( __FILE__ ).'/..')  ));
define('TG_COMPLETE_PATH', WP_PLUGIN_DIR.'/'.TG_PATH);
define('TG_URL', WP_PLUGIN_URL.'/'.TG_PATH);

/**
 * Translation domain name for this plugin
 */
define('TG_DOMAIN', 'tg_');

/**
 * Table names + prefix
 */
define('TG_CFG_PREFIX', 'tg_');
define('TG_SETTINGS', TG_CFG_PREFIX.'example');


/**
 * Settings group name
 */
define('TG_CFG_SETTINGS_GROUP', 'tg_settings_group');
define('TG_CFG_SETTINGS_SECTION', 'tg_settings_section');