<?php # -*- coding: utf-8 -*-
/**
 * Base class for a Custom Post Types
 *
 * @author Thomas Scholz <info@toscho.de>
 * @version 1.1
 */
class Toscho_CPT_Base extends Toscho_CPT_And_Tax_Base
{
	public function extend_defaults()
	{
		// Add the CPT to the list of pages to show on the front page.
		add_filter(
			'wp_dropdown_pages'
		,	array ( $this, 'add_cpt_to_front_page_dropdown' )
		,	10
		,	1
		);
		$this->options['offer_as_front_page'] = TRUE;
	}

	public function run()
	{
		register_post_type(
			$this->name, $this->get_args()
		);
	}

	/**
	 * Show name and number in the Right Now dashboard widget.
	 *
	 * @return void
	 */
	public function add_to_dashboard()
	{
		$posts = wp_count_posts( $this->name );
		$num   = number_format_i18n( $posts->publish );
		$text  = _n( $this->labels['singular_name'], $this->labels['name'], $posts->publish );

		// @todo map cap
        if ( current_user_can( 'edit_pages' ) )
        {
            $num  = "<a href='edit.php?post_type=$this->name'>$num</a>";
            $text = "<a href='edit.php?post_type=$this->name'>$text</a>";
        }

        $this->print_dashboard_row( $this->name, $num, $text );
	}

	/**
	 * Adds CPTs to the list of available pages for a static front page.
	 *
	 * @param  string $select Existing select list.
	 * @return string
	 */
	function add_cpt_to_front_page_dropdown( $select )
	{
		if (
			! $this->options['offer_as_front_page']
			or FALSE === strpos( $select, '<select name="page_on_front"' )
		)
		{
			return $select;
		}

		$cpt_posts = get_posts(
			array(
				'post_type'      => $this->name
			,	'nopaging'       => TRUE
			,	'numberposts'    => -1
			,	'order'          => 'ASC'
			,	'orderby'        => 'title'
			,	'posts_per_page' => -1
			)
		);

		if ( ! $cpt_posts ) // no posts found.
		{
			return $select;
		}

		$current = get_option( 'page_on_front', 0 );

		$options = walk_page_dropdown_tree(
			$cpt_posts
		,	0
		,	 array(
				'depth'                 => 0
			 ,	'child_of'              => 0
			 ,	'selected'              => $current
			 ,	'echo'                  => 0
			 ,	'name'                  => 'page_on_front'
			 ,	'id'                    => ''
			 ,	'show_option_none'      => ''
			 ,	'show_option_no_change' => ''
			 ,	'option_none_value'     => ''
			)
		);

		return str_replace( '</select>', $options . '</select>', $select );
	}
}