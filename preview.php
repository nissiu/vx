<?php

$template_id = $post->ID;
$post_for_preview = \Voxel\get_post_for_preview( $template_id );

if ( $post_for_preview->post_type->get_key() === 'elementor_library' ) {
	get_header();
	if ( \Voxel\get_page_setting( 'voxel_hide_header', $template_id ) !== 'yes' ) {
		\Voxel\print_header();
	}

	the_content();

	if ( \Voxel\get_page_setting( 'voxel_hide_footer', $template_id ) !== 'yes' ) {
		\Voxel\print_footer();
	}
	get_footer();
	return;
}

\Voxel\set_current_post( $post_for_preview );

if ( post_password_required( $template_id ) ) {
	return '';
}

if ( ! \Elementor\Plugin::$instance->documents->get( $template_id )->is_built_with_elementor() ) {
	return '';
}

$frontend = \Elementor\Plugin::$instance->frontend;
add_action( 'wp_enqueue_scripts', [ $frontend, 'enqueue_styles' ] );
\Voxel\enqueue_template_css( $template_id );

get_header();

if ( \Voxel\get_page_setting( 'voxel_hide_header', $template_id ) !== 'yes' ) {
	\Voxel\print_header();
}

echo $frontend->get_builder_content_for_display( $template_id );

if ( \Voxel\get_page_setting( 'voxel_hide_footer', $template_id ) !== 'yes' ) {
	\Voxel\print_footer();
}

get_footer();
