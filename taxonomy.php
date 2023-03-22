<?php

$term = \Voxel\Term::get( get_queried_object() );
$taxonomy = $term->taxonomy;

if ( ! $term->taxonomy->is_managed_by_voxel() ) {
	get_template_part('archive');
	return;
}

$template_id = $taxonomy->get_templates()['single'] ?? null;

if ( post_password_required( $template_id ) ) {
	return '';
}

if ( ! ( $template_id && \Elementor\Plugin::$instance->documents->get( $template_id )->is_built_with_elementor() ) ) {
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
