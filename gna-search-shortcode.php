<?php
/*
Plugin Name: GNA Search Shortcode
Version: 0.9.5
Plugin URI: http://wordpress.org/plugins/gna-search-shortcode/
Author: Chris Dev
Author URI: http://webgna.com/
Description: [searchform] shortcode for any pages, posts or widgets
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gna-search-shortcode
*/

if(!defined('ABSPATH'))exit; //Exit if accessed directly

include_once('gna-search-shortcode-core.php');

register_activation_hook(__FILE__, array('GNA_SearchShortcode', 'activate_handler'));		//activation hook
register_deactivation_hook(__FILE__, array('GNA_SearchShortcode', 'deactivate_handler'));	//deactivation hook