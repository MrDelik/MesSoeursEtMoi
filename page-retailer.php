<?php
/*
 * Template name: Retailer
 */

if( !current_user_can('administrator') || !is_user_logged_in() || get_user_meta(get_current_user_id(), 'isRetailer', true) == 'false'){
	wp_redirect( get_permalink( get_page_by_title('shop') ) );
	exit;
}
get_header();

/* Hook Display popup window */
do_action('nasa_before_page_wrapper');
?>

<?php while (have_posts()) :
	the_post();
	the_content();
endwhile; ?>

<?php
do_action('nasa_after_page_wrapper');

get_footer();
