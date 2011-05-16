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

abstract class Toscho_CPT_And_Tax_Base
{
	/**
	 * Internal name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Identifier for the language file.
	 *
	 * @var string
	 */
	protected $text_domain;

	/**
	 * Menu names and more.
	 *
	 * @var array
	 */
	protected $labels       = array ();

	/**
	 * Who can do what?
	 *
	 * @var array
	 */
	protected $capabilities = array ();

	/**
	 * Register arguments.
	 *
	 * @var array
	 */
	protected $args         = array (
		// visible
		'public'              => TRUE
		// Add it to custom menus
    ,	'show_in_nav_menus'   => TRUE
    	// Parents and children.
	,	'hierarchical'        => TRUE
	,	'rewrite'             => TRUE
	,	'show_ui'             => TRUE
	);

	/**
	 * Messages in yellow boxes.
	 *
	 * @var array
	 */
	protected $update_messages = array ();

	/**
	 * More options.
	 *
	 * @var array
	 */
	protected $options      = array (
		// Text in help tab.
		'help_text'          => ''
		// Right now dashboard widget.
	,	'show_on_dashboard'  => TRUE
		// Drop down at the top
	,	'show_in_favorites'  => TRUE
	);

	/**
	 * Constructor.
	 *
	 * Calls the register functions.
	 */
	public function __construct( $post_type, $text_domain = 'default' )
	{
		$this->post_type   = $post_type;
		$this->text_domain = $text_domain;
	}

	/**
	 * Labels for the backend.
	 *
	 * @param  array $labels
	 * @return void
	 */
	public function set_labels( array $labels )
	{
		$this->labels = $labels;
	}

	/**
	 * Getter for labels.
	 *
	 * @return array
	 */
	public function get_labels()
	{
		return $this->labels;
	}

	/**
	 * Capabilities for editing, publishing etc.
	 *
	 * @param  array $capabilities
	 * @return void
	 */
	public function set_caps( array $capabilities )
	{
		$this->capabilities = $capabilities;
	}

	/**
	 * Getter for capabilities.
	 *
	 * @return array
	 */
	public function get_caps()
	{
		return $this->capabilities;
	}

	/**
	 * More arguments for the register call.
	 *
	 * @param  array $args
	 * @return void
	 */
	public function set_args( array $args )
	{
		$this->args = $args;
	}

	/**
	 * Construct the arguments.
	 *
	 * @return array $args
	 */
	public function get_args()
	{
		$args                 = $this->args;
		$args['capabilities'] = $this->get_caps();
		$args['labels']       = $this->get_labels();
		return $args;
	}

	/**
	 * Change the default options.
	 *
	 * @param  array $options
	 * @return void
	 */
	public function set_options( array $options )
	{
		$this->options = array_merge( $this->options, $options );
	}

	/**
	 * Calls the register function.
	 *
	 * @return void
	 */
	abstract public function run();

	/**
	 * Show name and number in the right now dashboard widget.
	 *
	 * @return void
	 */
	abstract public function add_to_dashboard();

	/**
	 * Messages for updates (yellow boxes).
	 *
	 * @return void
	 */
	abstract public function add_update_messages();

}

class Toscho_CPT_Base extends Toscho_CPT_And_Tax_Base
{
}

class Toscho_Taxonomy_Base extends Toscho_CPT_And_Tax_Base
{

	/**
	 * More options.
	 *
	 * @var array
	 */
	protected $options      = array (
		// Text in help tab.
		'help_text'          => ''
		// Backend post type list.
	,	'show_in_table_view' => TRUE
		// Drop down sorter in backend.
	,	'show_sorter'        => TRUE
		// Right now box.
	,	'show_on_dashboard'  => TRUE
		// Drop down at the top
	,	'show_in_favorites'  => TRUE
	);

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