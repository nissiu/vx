<?php
$post_type = \Voxel\Post_Type::get( 'profile' );
if ( ! $post_type->is_managed_by_voxel() ) {
	return;
}

$template_id = $post_type->get_templates()['single'] ?? null;
if ( post_password_required( $template_id ) ) {
	return;
}

if ( ! \Elementor\Plugin::$instance->documents->get( $template_id )->is_built_with_elementor() ) {
	return;
}

$author = \Voxel\User::get( get_the_author_meta('ID') );
if ( ! $author ) {
	return;
}

\Voxel\set_current_post( $author->get_or_create_profile() );

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