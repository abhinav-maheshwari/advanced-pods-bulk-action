<?php
/*
Plugin Name: Advanced Pods Bulk Action
Plugin URI: 
Description: Adds session support to Pods Management UI so that bulk actions do not result in long URIs
Version: 1.0
Author: iPragmatech Solutions Pvt. Ltd.
Author URI: http://www.ipragmatech.com
*/

function pods_log($tag, $msg) {
	error_log($tag.':'.$msg);
}

function pods_v_set_bulk_ids($value) {
	$_SESSION['action_bulk_ids'] = $value;
}

function pods_v_bulk_ids() {
	return $_SESSION['action_bulk_ids'];
}

function isset_pods_v_bulk_ids() {
	return isset($_SESSION['action_bulk_ids']);
}

/**
 * Main function which manages the bulk ids in session and clears 
 * when new search happens or menu is clicked
 */
function pods_ui_pre_init( $args ) {
	if (isset($_REQUEST['referrer_pg']) && $_REQUEST['action_bulk'] == '-1'){
		$pg = $_REQUEST['referrer_pg'];
	} else if (isset ($_REQUEST['pg'])) {
		$pg = $_REQUEST['pg'];
	} else {
		$pg = 1;
	}

	foreach ($_POST as $var => $value) {
		if ($var == 'action_bulk_ids' && isset($pg)) {
			pods_log('pods_ui_pre_init', "Saving bulk ids in for Page[$pg]:".print_r($value, true));
			$bulk_id_array = pods_v_bulk_ids();
			$bulk_id_array[$pg] = $value;
			pods_v_set_bulk_ids($bulk_id_array);
		}
		$_GET[$var] = $value;
	}
	 
	if ( isset_pods_v_bulk_ids() && count(pods_v_bulk_ids()) > 0) {
		$bulk_ids = call_user_func_array('array_merge', pods_v_bulk_ids());
	}
	else {
		$bulk_ids = array();
	}

	// New Search
	if (isset($_REQUEST['search']) && !isset($_REQUEST['pg'])) {
		pods_log('pods_ui_pre_init','Initializing bulk ids for new search');
		pods_v_set_bulk_ids(array());
	}

	// Apply button has been clicked. Run the bulk action.
	else if (isset($_REQUEST['action_bulk']) && $_REQUEST['action_bulk'] != '-1') {
		pods_log('pods_ui_pre_init', 'Processing bulk action and clearing bulk ids: '.print_r($bulk_ids, true));
		$_GET['action_bulk_ids'] = $bulk_ids;
		pods_v_set_bulk_ids(array());
	}

	// Management screen has been opened using the menu
	else if (!isset($_REQUEST['action_bulk']) && !isset($_REQUEST['pg']) && !isset($_REQUEST['action_bulk'])) {
		pods_log('pods_ui_pre_init', 'Opening manage and clearing bulk ids');
		pods_v_set_bulk_ids(array());
	}
	else {
		echo '<input id="bulk_ids" name="bulk_ids" type="hidden" value="'.implode($bulk_ids, ",").'"/>';
	}

	return $args;
}
add_filter( 'pods_ui_pre_init', 'pods_ui_pre_init', 10, 3 );


function pods_ui_hidden_vars($args) {
	pods_log( 'pods_ui_hidden_vars', 'Excluding action_bulk_ids from hidden');
	$args[] = 'action_bulk_ids';
	return $args;
}
add_filter( 'pods_ui_hidden_vars', 'pods_ui_hidden_vars', 10, 3);

/**
 * Start session in admin
*/
function start_session() {
	pods_log( 'pods_manage_view', 'Starting session' );
	session_start();
}
add_action( 'admin_init', 'start_session', 1 );


function pods_pagination_fix( $suffix ) {
	// include on all pods-manage screens
	if( stripos(get_current_screen()->id,'pods-manage') ) {
		wp_enqueue_script( 'pods_pagination_fix', plugin_dir_url( __FILE__ ) . 'pods-pagination-fix.js', array(),'1.0.0', true );
	}
}

add_action( 'admin_enqueue_scripts', 'pods_pagination_fix');
