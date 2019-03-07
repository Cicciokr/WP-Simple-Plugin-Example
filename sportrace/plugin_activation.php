<?php
    //It’s a standard plugin header.
//display in plugin list.
/*
	Plugin Name: Sport Race 
	Plugin URI:   
	Description: Manage sport race 
	Version: 1.0 
	Author: GitHub/Cicciokr
	Author URI:  
	*/

$mob_table_prefix = $wpdb->prefix;
define('MOB_TABLE_PREFIX', $mob_table_prefix);
//****If plugin is active**************************************
register_activation_hook(__FILE__, 'name_plugin_install');
register_deactivation_hook(__FILE__, 'name_plugin_uninstall');
add_action('admin_head', 'sportrace_admin_header');
function sportrace_admin_header()
{
	$page = (isset($_GET['page'])) ? esc_attr($_GET['page']) : false;
	if ('my_list_test' != $page)
		return;

	echo '<style type="text/css">';
	echo '.wp-list-table .column-id { width: 10% !important; }';
	echo '</style>';
}

//function will call when plugin will active
function name_plugin_install()
{
	global $wpdb;
	global $db_version;
    $table_name = $wpdb->prefix . "sportrace";
    $structure = "CREATE TABLE $table_name (
		        id INT(9) NOT NULL AUTO_INCREMENT,
		        name_race VARCHAR(200) NOT NULL,
				photo VARCHAR(2000) NULL,
		        PRIMARY KEY id (id)
		    );";
    //$wpdb->query($structure);

    $table_namep = $wpdb->prefix . "sportrace_members";
    $structurep = "CREATE TABLE $table_namep (
		        id INT(9) NOT NULL AUTO_INCREMENT,
		        id_sportrace INT(9) NOT NULL,
				name_member VARCHAR(500) NOT NULL,
				time_done VARCHAR(200),
				position INT(2),
		        PRIMARY KEY id (id)
		    );";
	//$wpdb->query($structurep);
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($structure);
dbDelta($structurep);
add_option("db_version", $db_version);
}
//function will call when plugin will deactive
function name_plugin_uninstall()
{
    global $wpdb;
    $table = MOB_TABLE_PREFIX . "sportrace";
    $structure = "drop table if exists $table";
    $wpdb->query($structure);
    $tablep = MOB_TABLE_PREFIX . "sportrace_members";
    $structurep = "drop table if exists $tablep";
    $wpdb->query($structurep);
}
//create menu at admin side
add_action('admin_menu', 'name_plugin_admin_menu');

function name_plugin_admin_menu()
{

    add_menu_page('Sport Race', 'Sport Race', 'manage_options', 'my-menu', '', 'dashicons-awards');
	add_submenu_page('my-menu', 'Race', 'Race', 'manage_options', 'sportrace/sportrace_page.php');
	add_submenu_page('my-menu', 'Member Race', 'Member Race', 'manage_options', 'sportrace/members_sportrace_page.php');
}


add_action( 'rest_api_init', function () {
	
		require plugin_dir_path( __FILE__ ) . 'wprest_sportrace.php';
		$controller = new Sportrace_Rest();
		$controller->register_routes();
	
} );

function load_wp_media_files() {
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );
 