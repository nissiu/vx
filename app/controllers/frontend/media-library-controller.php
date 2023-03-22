<?php

namespace Voxel\Controllers\Frontend;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Media_Library_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'voxel_ajax_list_media', '@handle' );
	}

	protected function handle() {
		$user = wp_get_current_user();
		if ( ! $user ) {
			return;
		}

		$config = [];
		$posts_per_page = 9;
		$page = absint( $_GET['page'] ?? 1 );
		$offset = ( $page - 1 ) * $posts_per_page;

		$query_args = [
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'author' => $user->ID,
			'posts_per_page' => $posts_per_page,
			'offset' => $offset,
		];

		$attachments = get_posts( $query_args );
		foreach ( $attachments as $attachment ) {
			$config[] = [
				'source' => 'existing',
				'id' => $attachment->ID,
				'name' => wp_basename( get_attached_file( $attachment->ID ) ),
				'type' => $attachment->post_mime_type,
				'preview' => wp_get_attachment_image_url( $attachment->ID, 'medium' ),
			];
		}

		$next_file = get_posts( array_merge( $query_args, [
			'offset' => $page * $posts_per_page,
			'posts_per_page' => 1,
			'fields' => 'ids',
		] ) );

		return wp_send_json( [
			'success' => true,
			'files' => $config,
			'has_more' => ! empty( $next_file ),
		] );
	}

}
