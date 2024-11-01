<?php
/*
Plugin Name: Simple Cu3er
Plugin URI: http://www.beapi.fr
Description: Creates a cu3er flash gallery of your recent custom post type items for your Front Page or Header
Author: BeAPI
Author URI: http://www.beapi.fr
Version: 1.0.1
----
 
Based on Cu3er Post Elements - Daniel Sachs - 18elements.com

Copyright 2010 - BeAPI (email: amaury@beapi.fr)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'SIMPLE_CU3ER_VER', '1.0.1' );
define( 'SIMPLE_CU3ER_URL', plugins_url('/', __FILE__) );
define( 'SIMPLE_CU3ER_DIR', dirname(__FILE__) );

// No font ? Kill HTTP Request
if ( strpos($_SERVER["REQUEST_URI"], 'font.swf') !== false ) {
	status_header(404);
	die();
}

require_once( dirname(__FILE__) . '/inc/class.base.php' );
require_once( dirname(__FILE__) . '/inc/class.client.php' );
require_once( dirname(__FILE__) . '/inc/functions.tpl.php' );

register_activation_hook( __FILE__, array('Simple_Cu3er_Base', 'activate') );
register_deactivation_hook( __FILE__, array('Simple_Cu3er_Base', 'deactivate') );

add_action( 'plugins_loaded', 'initSimpleCu3er' );
function initSimpleCu3er() {
	global $simple_cu3er;
	
	// Load translations
	load_plugin_textdomain ( 'simple-cu3er', false, basename(rtrim(dirname(__FILE__), '/')) . '/languages' );
	
	$simple_cu3er['client'] = new Simple_Cu3er_Client();
	
	if ( is_admin() ) {
		require_once( dirname(__FILE__) . '/inc/class.admin.php' );
		$simple_cu3er['admin'] = new Simple_Cu3er_Admin();
	}
}
?>