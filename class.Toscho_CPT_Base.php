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
}