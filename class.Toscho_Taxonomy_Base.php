<?php # -*- coding: utf-8 -*-
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
	public $post_types = array ();

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
	 * Show name and number in the right now dashboard widget.
	 *
	 * @return void
	 */
	public function add_to_dashboard()
	{
		$num  = wp_count_terms( $this->name );
		$num  = number_format_i18n( $num );
		$text = _n( $this->labels['singular_name'], $this->labels['name'], $num );

		// @todo map cap
        if ( current_user_can( 'manage_categories' ) )
        {
            $num  = "<a href='edit-tags.php?taxonomy=$this->name'>$num</a>";
            $text = "<a href='edit-tags.php?taxonomy=$this->name'>$text</a>";
        }
        $this->print_dashboard_row( $this->name, $num, $text );
	}

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