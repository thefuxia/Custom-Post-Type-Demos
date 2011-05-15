<?php # -*- coding: utf-8 -*-
/*
Plugin Name: Custom Post Type Demo 4 - Taxonomies and a meta box
Description: Creates a public Custom Post Type in its own main menu, with custom labels, two taxonomies and a meta box.
Version:     1.0
Required:    3.1
Author:      Thomas Scholz
Author URI:  http://toscho.de
License:     GPL
*/
! defined( 'ABSPATH' ) and exit;

add_action( 'init', 'cpt_demo_4' );

/**
 * Creates a visible post type.
 *
 * @return void
 */
function cpt_demo_4()
{
	$labels = array (
		// Usually plural. We use a singular here
		'name'               => 'CPT4'
	,	'singular_name'      => 'CPT4'
	,	'add_new'            => 'New'
	,	'add_new_item'       => 'Add New CPT4'
	,	'edit_item'          => 'Edit CPT4'
	,	'new_item'           => 'New CPT4'
	,	'view_item'          => 'View CPT4'
	,	'search_items'       => 'Search CPT4s'
	,	'not_found'          => 'No CPT4s found'
	,	'not_found_in_trash' => 'No CPT4s found in Trash'
	,	'parent_item_colon'  => 'Parent CPT4'
    );

	register_post_type(
		'cpt_demo_3'
	,	array (
			// visible
			'public'        => TRUE
			// Menu main name, usually plural
		,	'label'         => 'CPT4'
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