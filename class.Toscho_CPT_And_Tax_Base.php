<?php # -*- coding: utf-8 -*-

abstract class Toscho_CPT_And_Tax_Base
{
	/**
	 * Internal name.
	 *
	 * Lowercase.
	 * Do not use hyphens: http://core.trac.wordpress.org/ticket/15970
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
	public function __construct( $name, $text_domain = 'default' )
	{
		$this->name        = $name;
		$this->text_domain = $text_domain;
		add_action( 'contextual_help', array ( $this, 'add_help_text' ), 10, 3 );
		$this->extend_defaults();
	}

	/**
	 * Extends the default values with object specific fields.
	 *
	 * Pseudo child constructor.
	 *
	 * @return void
	 */
	abstract public function extend_defaults();

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
	 * Messages for update boxes.
	 *
	 * @param  array $update_messages
	 * @return void
	 */
	public function set_update_messages( array $update_messages )
	{
		$this->update_messages = $update_messages;
	}

	/**
	 * Getter for labels.
	 *
	 * @return array
	 */
	public function get_update_messages()
	{
		return $this->update_messages;
	}

	/**
	 * Insert custom text into the help tab.
	 *
	 * @param  string $contextual_help Original text. Usually just two links.
	 * @param  string $screen_id       Name of the current screen
	 * @param  object $screen          Object with information about the screen.
	 * @return string $help            New text if set in $options.
	 */
	public function add_help_text( $contextual_help, $screen_id, $screen )
	{
		$help = trim( $this->options['help_text'] );

		if ( $this->name != $screen->id or '' == $help )
		{
			return $contextual_help;
		}

		return $help;
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
}