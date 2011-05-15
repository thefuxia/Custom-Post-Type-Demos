<?php # -*- coding: utf-8 -*-
/**
 * Creates a checkbox.
 *
 * @author Thomas Scholz, <info@toscho.de>
 * @version 1.1
 *
 */
class Toscho_Meta_Box_Checkbox
{
	protected $vars, $nonce;

	/**
	 * Constructor
	 *
	 * @param array $params
	 * @return void
	 */
	public function __construct( array $params )
	{
		$defaults    = array (
			'key'           => NULL
		,	'title'         => 'MISSING TITLE!'
			// page, post, custom or array
		,	'posttypes'	    => array ( 'post', 'page' )
		,	'context'       => 'side'
		,	'priority'      => 'low'
		,	'input_type'    => 'checkbox'
		,	'desc'          => ''
		,	'label'         => ''
		,	'filter_prefix' => strtolower( __CLASS__ )
		);

		$this->vars  = array_merge( $defaults, $params );
		$this->nonce = wp_create_nonce( $this->vars['title'] );

		add_action( 'admin_init', array ( $this, 'register_boxes' ) );
		add_action( 'save_post',  array ( $this, 'save' ) );
	}

	/**
	 * Registers the meta box to all post types.
	 *
	 * @return void
	 */
	public function register_boxes()
	{
		$posttypes = apply_filters(
			$this->vars['filter_prefix'] . '_post_types'
		,	(array) $this->vars['posttypes']
		);

		foreach ( $posttypes as $posttype )
		{
			add_meta_box(
				$this->vars['key'],
				$this->vars['title'],
				array ( $this, 'print_meta_box' ),
				$posttype,
				'side',
				$this->vars['priority']
			);
		}
	}

	/**
	 * Saves the content to the post meta.
	 *
	 * @return void
	 */
	public function save( $post_id )
	{
		$this->is_allowed_save( $post_id ) and
			update_post_meta( $post_id, $this->vars['key'],
				empty ( $_POST[ $this->vars['key'] ] ) ? 'off' : 'on' );
	}

	/**
	 * Checks if we should trigger the save action.
	 *
	 * @param  int  $post_id
	 * @return bool
	 */
	protected function is_allowed_save( $post_id )
	{
		// Check integrity, proper action and permission
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return FALSE;
		}
		if ( ! wp_verify_nonce( $this->nonce, $this->vars['title'] ) )
		{
			return FALSE;
		}
		if (    ! current_user_can( 'edit_post', $post_id )
			and ! current_user_can( 'edit_page', $post_id )
		)
		{
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Draws the meta box into the editor page.
	 *
	 * @param  object $data
	 * @return void
	 */
	public function print_meta_box( $data )
	{
		$id    = $this->get_id( $data );
		$value = get_post_meta( $id, $this->vars['key'], TRUE );

		print $this->meta_box_markup( $value );
	}

	/**
	 * Markup displayed on the edit page.
	 *
	 * @param  string $value
	 * @return string
	 */
	protected function meta_box_markup( $value )
	{
		$nonce   = $this->nonce_input();
		$checked = 'off' == $value ? '' : 'checked="checked"';
		$key     = $this->vars['key'];
		$label   = $this->vars['label'];
		$desc    = empty( $this->vars['desc'] ) ? '' : '<p>' .  $this->vars['desc'] . '</p>';
		$result  = <<<METABOXMARKUP
$nonce
<input name="$key" id="{$key}_id" $checked type="checkbox" />
<label for="{$key}_id">$label</label>$desc
METABOXMARKUP;

		return $result;
	}

	/**
	 * Creates an input[type=hidden] with an nonce (number used once).
	 *
	 * @see http://codex.wordpress.org/Function_Reference/wp_create_nonce
	 * return string
	 */
	protected function nonce_input()
	{
		$key     = $this->vars['key'];
		return "<input type='hidden' name='{$key}_noncename'
				id='{$key}_noncename'	value='{$this->nonce}' />";
	}

	/**
	 * Returns the current post’s ID.
	 *
	 * @param  object $data
	 * @return int
	 */
	protected function get_id( $data = NULL )
	{
		if ( isset ( $data->ID ) && 0 !== $data->ID )
		{
			return $data->ID;
		}

		global $id;

		if ( ! isset ( $id ) && isset ( $_REQUEST['post_ID'] ) )
		{
			$id = (int) $_REQUEST['post_ID'];
		}

		if ( $this->is_page_preview() && ! isset ( $id ) )
		{
			$id = (int) $_GET['preview_id'];
		}

		if ( empty ( $id ) and isset ( $_GET['post'] ) )
		{
			$id = $_GET['post'];
		}

		return $id;
	}

	/**
	 * Checks the state of the currently seen page.
	 *
	 * @return bool
	 */
	public function is_page_preview()
	{
		if ( ! isset ( $_GET['post_id'] ) )
		{
			return FALSE;
		}
		$id = 0;
		isset ( $_GET['preview_id'] ) and $id = (int) $_GET['preview_id'];

		if ( $id == 0 )
		{
			$id = (int) $_GET['post_id'];
		}

		$preview = $_GET['preview'];

		if ( $id > 0 && $preview == 'true' )
		{
			global $wpdb;

			$type = $wpdb->get_results(
				"SELECT post_type FROM $wpdb->posts WHERE ID=$id"
			);

			if ( count( $type )
				&& ( $type[0]->post_type == 'page' )
				&& current_user_can( 'edit_page' )
			)
			{
				return TRUE;
			}
		}

		return FALSE;
	}
}