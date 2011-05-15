<?php # -*- coding: utf-8 -*-
/*
Plugin Name: Custom Post Type Demo 2 - visible
Description: Creates a public Custom Post Type. Now you'll see a second entry <i>Posts</i> in the menu below <i>Comments</i>. The edit form has a title, a text field an the save box.
Version:     1.0
Required:    3.1
Author:      Thomas Scholz
Author URI:  http://toscho.de
License:     GPL
*/
! defined( 'ABSPATH' ) and exit;

add_action( 'init', 'cpt_demo_2' );

/**
 * Creates a visible post type.
 *
 * @return void
 */
function cpt_demo_2()
{
	register_post_type( 'cpt_demo_2', array ( 'public' => TRUE ) );
}