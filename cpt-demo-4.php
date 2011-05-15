<?php # -*- coding: utf-8 -*-
/*
Plugin Name: Custom Post Type Demo 4 - CPT 'Cats' with taxonomies and a meta box
Description: Creates a public Custom Post Type in its own main menu, with custom labels, two taxonomies and a meta box.
Version:     1.0
Required:    3.1
Author:      Thomas Scholz
Author URI:  http://toscho.de
License:     GPL
*/
! defined( 'ABSPATH' ) and exit;

// A simple function would be too messy. So we use a class now.

add_action( 'init', array ( 'CPT4', 'init' ) );

class CPT4
{
	/**
	 * Internal name of the post type.
	 *
	 * @var string
	 */
	protected $post_type = 'cat';

	/**
	 * Handler for the action 'init'. Instantiates this class.
	 *
	 * We use a global variable to make the access from other scripts and
	 * filters easier.
	 *
	 * @return void
	 */
	public static function init()
	{
		$class = __CLASS__ ;

		// Named global variable to make access for other scripts easier.
		$GLOBALS[ $class ] = new $class;
	}

	/**
	 * Constructor.
	 *
	 * Calls the register functions.
	 */
	public function __construct()
	{
		$this->register_cpt();
		$this->register_taxonomies();
		$this->register_metabox();
	}

	/**
	 * Registers our post type.
	 */
	public function register_cpt()
	{
		register_post_type(
			$this->post_type
		,	array (
			// Include it in the export file
				'can_export'          => TRUE
			// Anyone who can edit pages can edit cats
		    ,	'capability_type'     => 'page'
		    // Shows up on the nav menu page.
		    ,	'description'         => 'Cat profiles'
		    // Is searchable
		    ,	'exclude_from_search' => FALSE
		    // /cat/ will show an archive of all cats and /cat/feed/ a newsfeed.
		    ,	'has_archive'         => TRUE
		    // Menu name
			,	'label'               => 'Cats'
			// All other names
			,	'labels'              => $this->get_cpt_labels()
			// Cats can have parent cats.
		    ,	'hierarchical'        => TRUE
		    // Icon for the menu.
		    ,	'menu_icon'           => plugins_url('/cat-icon.png', __FILE__)
		    /*   5 - below Posts
		     *  10 - below Media
		     *  20 - below Pages
		     *  60 - below first separator
		     * 100 - below second separator
		     */
		    ,	'menu_position'       => 100
			// visible
		    ,	'public'              => TRUE
		    // Enable all sorts of URIs
		    ,	'publicly_queryable'  => TRUE
		    // /?cat=123 will query for the post with this number
		    ,	'query_var'           => $this->post_type
		    // Nice permalinks
			,	'rewrite'             => array ( 'slug' => $this->post_type )
			// Add it to custom menus
		    ,	'show_in_nav_menus'   => TRUE
		    // Show up in the left hand menu
		    ,	'show_ui'             => TRUE
		    // Components of the editor.
		    ,	'supports'            => array ( 'editor', 'title', 'page-attributes', 'thumbnail' )
		    // We use the built-in taxonomies too.
		    ,	'taxonomies'          => array ( 'category', 'post_tag' )
			)
		);
	}

	/**
	 * Registers the taxonomies.
	 */
	public function register_taxonomies()
	{
		// By default tag labels are used for non-hierarchical types and category labels for hierarchical ones.
		$labels = $this->get_tax_labels();

		$args = array (
			// We use the default, but you can ovveride it here.
			'label'             => $labels['name']
		,	'labels'            => $labels
			// Has feed
		,	'public'            => TRUE
		,	'hierarchical'      => TRUE
		,	'show_in_nav_menus' => TRUE
		,	'show_ui'           => TRUE
		,	'show_tagcloud'     => TRUE
		,	'rewrite'           => array (
				'slug'         => 'color'
				// Allow hierarchical URLs.
			,	'hierarchical' => TRUE
			)
		,	'query_var'         => 'color'
		);

		// taxonomy, object type, arguments
		register_taxonomy( 'color', $this->post_type, $args );
	}

	/**
	 * Registers a meta box.
	 */
	public function register_metabox()
	{

	}

	/**
	 * Returns the labels for the CPT.
	 *
	 * @return array  List of labels.
	 */
	protected function get_cpt_labels()
	{
		$labels = array (
			// Usually plural. We use a singular here
			'name'               => 'Cats'
		,	'singular_name'      => 'Cat'
		,	'add_new'            => 'New Cat'
		,	'add_new_item'       => 'Add New Cat'
		,	'edit_item'          => 'Edit Cat'
		,	'new_item'           => 'New Cat'
		,	'view_item'          => 'View Cat'
		,	'search_items'       => 'Search Cats'
		,	'not_found'          => 'No Cats found'
		,	'not_found_in_trash' => 'No Cats found in Trash'
		,	'parent_item_colon'  => 'Parent Cat:'
	    );

	}

	/**
	 * Returns the labels for the taxonomy 'color'.
	 *
	 * @return array  List of labels.
	 */
	protected function get_tax_labels()
	{
		// By default tag labels are used for non-hierarchical types and
		// category labels for hierarchical ones.
		$labels = array (
			// General name, plural
			'name'                       => 'Colors'
			// Single item name
		,	'singular_name'              => 'Color'
			// Menu name
		,	'menu_name'                  => 'Colors'
			// Search heading
		,	'search_items'               => 'Search colors'
		,	'popular_items'              => 'Popular colors'
		,	'all_items'                  => 'All colors'
		,	'edit_item'                  => 'Edit color'
		,	'update_item'                => 'Update color'
		,	'add_new_item'               => 'Add new color'
		,	'new_item_name'              => 'New color name'
		,	'separate_items_with_commas' => 'Separate colors with comma'
		,	'add_or_remove_items'        => 'Add or remove colors'
		,	'choose_from_most_used'      => 'Choose from most used colors'
			// Next both are for hierarchical taxonomies only.
		,	'parent_item'                => 'Parent color'
		,	'parent_item_colon'          => 'Parent color:'
		);

		return $labels;
	}
}