<?php # -*- coding: utf-8 -*-
/*
Plugin Name: Custom Post Type Demo 3 - Menus and names
Description: Creates a public Custom Post Type in its own main menu and with custom labels.
Version:     1.0
Required:    3.1
Author:      Thomas Scholz
Author URI:  http://toscho.de
License:     GPL
*/
! defined( 'ABSPATH' ) and exit;

add_action( 'init', 'cpt_demo_3' );

/**
 * Creates a visible post type.
 *
 * @return void
 */
function cpt_demo_3()
{
	$labels = array (
		// Usually plural. We use a singular here
		'name'               => 'CPT3'
	,	'singular_name'      => 'CPT3'
	,	'add_new'            => 'New'
	,	'add_new_item'       => 'Add New CPT3'
	,	'edit_item'          => 'Edit CPT3'
	,	'new_item'           => 'New CPT3'
	,	'view_item'          => 'View CPT3'
	,	'search_items'       => 'Search CPT3s'
	,	'not_found'          => 'No CPT3s found'
	,	'not_found_in_trash' => 'No CPT3s found in Trash'
	,	'parent_item_colon'  => 'Parent CPT3'
    );

	register_post_type(
		'cpt_demo_3'
	,	array (
			// visible
			'public'        => TRUE
			// Menu main name, usually plural
		,	'label'         => 'CPT3'
			// All labels
		,	'labels'        => $labels
			// Menu position
			//   5 - below Posts
			//  10 - below Media
			//  15 - below Links
			//  20 - below Pages
			//  25 - below comments
			//  60 - below first separator
			//  65 - below Plugins
			//  70 - below Users
			//  75 - below Tools
			//  80 - below Settings
			// 100 - below second separator
		,	'menu_position' => 100
		)
	);
}