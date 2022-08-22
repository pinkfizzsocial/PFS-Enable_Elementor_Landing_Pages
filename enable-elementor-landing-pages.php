<?php
/*
* Plugin Name: PFS-Enable Elementor Landing Pages
* Description: Elementor Landing Pages can error with 404, this will enable Elementor Landing Pages
* Version: 1.0.1
* Author: Pink Fizz Social
* Author URI: http://pinkfizz.social
* License: GPL2
*/

/**
 * Fixes landing page 404 when non-standard permalinks are enabled.
 *
 * @param \WP_Query $query
 */
function elementor_pre_get_posts( \WP_Query $query ) {
	if (
		// If the post type includes the Elementor landing page CPT.
		class_exists( '\Elementor\Modules\LandingPages\Module' )
		&& is_array( $query->get( 'post_type' ) )
		&& in_array( \Elementor\Modules\LandingPages\Module::CPT, $query->get( 'post_type' ), true )
		// If custom permalinks are enabled.
		&& '' !== get_option( 'permalink_structure' )
		// If the query is for a front-end page.
		&& ( ! is_admin() || wp_doing_ajax() )
		&& $query->is_main_query()
		// If the query is for a page.
		&& isset( $query->query['page'] )
		// If the query is not for a static home/blog page.
		&& ! is_home()
		// If the page name has been set and is not for a path.
		&& ! empty( $query->query['pagename'] )
		&& false === strpos( $query->query['pagename'], '/' )
		// If the name has not already been set.
		&& empty( $query->query['name'] ) ) {
		$query->set( 'name', $query->query['pagename'] );
	}
}
add_action( 'pre_get_posts', 'elementor_pre_get_posts', 100 );