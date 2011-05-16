<?php # -*- coding: utf-8 -*-
/*
Plugin Name: Custom Post Type Demo 5 - CPTs 'Books' with taxonomies, meta boxes, I18n.
Description: Creates two public Custom Post Types: <strong>Books</strong> and <strong>Movies</strong> in a cleaner OOP style.
Version:     1.0
Required:    3.1
Author:      Thomas Scholz
Author URI:  http://toscho.de
License:     GPL
*/
! defined( 'ABSPATH' ) and exit;


add_action( 'init', array ( 'CPT5_Controller', 'init' ) );

class CPT5_Controller
{
	/**
	 * Internal name of the post types.
	 *
	 * @var array
	 */
	protected $post_types = array ( 'book', 'movie' );

	/**
	 * Identifier for the language file.
	 *
	 * @var string
	 */
	protected $text_domain = 'plugin_cpt5';

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
	 * Creates the needed objects.
	 */
	public function __construct()
	{
		$this->register_cpt();
		$this->register_taxonomies();
	}
}






/**
 * Base class for a Custom Taxonomy
 *
 * @author Thomas Scholz <info@toscho.de>
 * @version 1.1
 */
class Toscho_Taxonomy_Base extends Toscho_CPT_And_Tax_Base
{
	public function extend_defaults()
	{
		$this->options['show_in_table_view'] = TRUE;
		$this->options['show_sorter']        = TRUE;
	}

	/**
	 * Post types to which the taxonomy should be applied.
	 *
	 * @var array
	 */
	protected $post_types = array ();

	public function run()
	{
		if ( empty ( $this->post_types ) )
		{
			return;
		}

		$args = $this->get_args();

		foreach ( $this->post_types as $post_type )
		{
			register_taxonomy( $this->name, $post_type, $args );
		}
	}
}