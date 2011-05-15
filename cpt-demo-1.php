<?php # -*- coding: utf-8 -*-
/*
Plugin Name: Custom Post Type Demo 1 - invisible
Description: Creates the most basic Custom Post Type. You won't see anything because the argument <code>public</code> deafults to <code>FALSE</code>.
Version:     1.0
Required:    3.1
Author:      Thomas Scholz
Author URI:  http://toscho.de
License:     GPL
*/
! defined( 'ABSPATH' ) and exit;

add_action( 'init', 'cpt_demo_1' );

/**
 * Creates a hidden post type.
 *
 * @return void
 */
function cpt_demo_1()
{
	register_post_type( 'cpt_demo_1' );
}