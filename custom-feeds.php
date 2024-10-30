<?php
/*
Plugin Name: Custom Feeds
Version: 1.0
Plugin URI: http://envintus.com/
Description: Add custom RSS feeds to your WordPress installation.
Author: Hunter Satterwhite
Author URI: https://linkedin.com/in/hsatterwhite
License: GPL v3

Custom Feeds Plugin
Copyright (C) 2008-2014, Hunter Satterwhite - hsatterwhite@envintus.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

define( 'CFEEDS_PATH', plugin_dir_path( __FILE__ ) );

function custom_feeds_menu_item() {
    add_options_page( 'Custom Feeds', 'Custom Feeds', 'manage_options', basename( __FILE__ ), function() {
        include_once( CFEEDS_PATH . 'lib/options.php' );
    } );
}
add_action( 'admin_menu', 'custom_feeds_menu_item' );

require_once( CFEEDS_PATH . 'lib/Custom_Feeds.php' );

global $custom_feeds;
$custom_feeds = new Custom_Feeds();