<?php # -*- coding: utf-8 -*-
/**
 * This is a base class to create custom post types and custom taxonomies in
 * WordPress. You have to extend it, samples should be in the same directory as
 * this class.
 * The main purpose here is to take out as much repeated work as possible to
 * keep the child classes clean and short.
 *
 * In child classes you shouldn’t need to extend the constructor.
 * Use extend_defaults() instead.
 *
 * Don’t forget to flush the rewrite rules to get pretty permalinks. Either go
 * to wp-admin/options-permalink.php or add flush_rewrite_rules to the
 * activation hook of your plugin.
 *
 * @author     Thomas Scholz <info@toscho.de>
 * @package    ToschoTools
 * @subpackage classes
 * @version    0.1
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */
abstract class Toscho_CPT_And_Tax_Base
{
	/**
	 * Internal name.
	 *
	 * Lowercase!
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
	 * Here we use just the arguments taxonomies and CPTs share.
	 * Use #extend_defaults() to overwrite other arguments.
	 *
	 * @var array
	 */
	protected $args = array (
		// visible
		'public'              => TRUE
		// Add it to custom menus
    ,	'show_in_nav_menus'   => TRUE
    	// Parents and children.
	,	'hierarchical'        => TRUE
		// Use a slug in pretty permalinks
	,	'rewrite'             => TRUE
		// backend interface
	,	'show_ui'             => TRUE
	);

	/**
	 * Messages in yellow boxes.
	 *
	 * @see set_update_messages()
	 * @var array
	 */
	protected $update_messages = array ();

	/**
	 * More options.
	 *
	 * Enable other features besides the register_* call.
	 *
	 * @var array
	 */
	protected $options = array (
		// Text in help tab.
		'help_text'          => ''
		// Right now dashboard widget.
	,	'show_on_dashboard'  => TRUE
	);

	/**
	 * Constructor.
	 *
	 * Calls the register functions and sets basic information.
	 *
	 * @param  string $name Name of the taxonomie or the CPT. Use [a-z][a-z\d].
	 *                      Anything else may be buggy.
	 * @param  string $text_domain Identifier for language files.
	 * @return void
	 */
	public function __construct( $name, $text_domain = 'default' )
	{
		$this->name        = $name;
		$this->text_domain = $text_domain;
		add_filter( 'contextual_help', array ( $this, 'add_help_text' ), 10, 3 );
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
	 * @link   http://codex.wordpress.org/Function_Reference/register_taxonomy
	 * @link   http://codex.wordpress.org/Function_Reference/register_post_type
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
	 * @link   http://codex.wordpress.org/Roles_and_Capabilities
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
	 * Prepare the arguments.
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
	 * Change the default extra options.
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
	 * Getter for messages.
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
	 * Called by add_filter( 'contextual_help', ... )
	 *
	 * @param  string $contextual_help Original text. Usually just two links.
	 * @param  string $screen_id       Name of the current screen
	 * @param  object $screen          Object with information about the screen.
	 * @return string $help            New text if one is set in $options.
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

	/**
	 * Prints a table row in the right now widget.
	 *
	 * Helper for add_to_dashboard()
	 *
	 * @param  string $name CPT or taxonomy name
	 * @param  int    $num Amount of CPTs or taxonomy items
	 * @param  string $text Public name of the item
	 * @return void
	 */
	protected function print_dashboard_row( $name, $num, $text )
	{
        echo "<td class='first b b-{$name}s'>$num</td>
        	<td class='t {$name}s'>$text</td></tr><tr>";
	}
}