<?php # -*- coding: utf-8 -*-
/**
 * Base class for a Custom Taxonomy.
 *
 *
 *
 * @author Thomas Scholz <info@toscho.de>
 * @version 1.1
 */
class Toscho_Taxonomy_Base extends Toscho_CPT_And_Tax_Base
{
	/**
	 * Post types to which the taxonomy should be applied.
	 *
	 * @var array
	 */
	public $post_types = array ();

	public function extend_defaults()
	{
		$this->options['show_in_table_view'] = TRUE;
		$this->options['show_sorter']        = TRUE;
	}

	/**
	 * Registers the taxonomy.
	 *
	 * @return void
	 */
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

	/**
	 * Add one ore more post types to which the taxonomy applies.
	 *
	 * @param  mixed $post_types Array or string.
	 * @return void
	 */
	public function add_post_type( $post_types )
	{
		$this->post_types = array_unique(
			array_merge( $this->post_types, (array) $post_types )
		);
	}

	/**
	 * Show name and number in the Right Now dashboard widget.
	 *
	 * @return void
	 */
	public function add_to_dashboard()
	{
		$num  = wp_count_terms( $this->name );
		// thousands separator etc.
		$num  = number_format_i18n( $num );
		// Singular or Plural.
		$text = _n(
			$this->labels['singular_name']
		,	$this->labels['name'], $num
		);

		// @todo map cap
        if ( current_user_can( 'manage_categories' ) )
        {
            $num  = "<a href='edit-tags.php?taxonomy=$this->name'>$num</a>";
            $text = "<a href='edit-tags.php?taxonomy=$this->name'>$text</a>";
        }

        $this->print_dashboard_row( $this->name, $num, $text );
	}
}