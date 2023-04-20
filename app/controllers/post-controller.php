<?php

namespace Voxel\Controllers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Post_Controller extends Base_Controller {

	protected function hooks() {
		$this->on( 'add_meta_boxes', '@display_fields' );
		$this->on( 'save_post', '@save_post', 1000, 2 );
		$this->on( 'add_meta_boxes', '@add_verification_metabox', 70 );
		$this->on( 'add_meta_boxes', '@add_author_metabox', 80 );
		$this->on( 'voxel/admin/save_post', '@save_verification_status' );
		$this->on( 'voxel/admin/save_post', '@save_author' );
		$this->on( 'voxel/admin/save_post', '@backend_index_post', 1000 );
		$this->on( 'voxel/admin/save_post', '@send_notifications', 1500 );
		$this->on( 'trashed_post', '@unindex_on_trash' );

		$this->on( 'voxel/post-type-archive', '@print_archive_template' );

		$this->on( 'voxel/post/wall-stats-updated', '@wall_stats_updated' );
		$this->on( 'voxel/post/review-stats-updated', '@review_stats_updated' );
		$this->on( 'voxel/post/timeline-stats-updated', '@timeline_stats_updated' );

		$this->on( 'voxel/post/wall-reply-stats-updated', '@wall_reply_stats_updated' );
		$this->on( 'voxel/post/review-reply-stats-updated', '@review_reply_stats_updated' );
		$this->on( 'voxel/post/timeline-reply-stats-updated', '@timeline_reply_stats_updated' );

		$this->on( 'voxel_ajax_admin.get_fields_form', '@get_fields_form' );
		$this->on( 'add_meta_boxes', '@remove_taxonomy_metaboxes' );

		$this->filter( 'wp_revisions_to_keep', '@limit_revision_count', 10, 2 );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo' );
		add_theme_support( 'woocommerce' );
	}

	protected function display_fields() {
		$post = \Voxel\Post::get( get_post() );
		if ( ! ( $post && $post->is_managed_by_voxel() ) ) {
			return;
		}

		add_meta_box(
			'voxel_post_fields',
			__( 'Fields', 'voxel-backend' ).sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $post->get_edit_link() ), __( 'Edit in frontend form', 'voxel-backend' ) ),
			function() use ( $post ) {
				require locate_template( 'templates/backend/edit-post-metabox.php' );
			},
			$post->post_type->get_key(),
			'normal',
			'high'
		);
	}

	protected function get_fields_form() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_admin_edit_post' );

			$post_type = \Voxel\Post_Type::get( $_GET['post_type'] ?? null );
			if ( ! ( $post_type && $post_type->is_managed_by_voxel() ) ) {
				throw new \Exception( __( 'Post type not provided.', 'voxel-backend' ) );
			}

			$post = \Voxel\Post::get( $_GET['post_id'] ?? null );
			if ( $post && ! $post->is_editable_by_current_user() ) {
				throw new \Exception( __( 'You cannot edit this post.', 'voxel-backend' ) );
			}

			if ( $post && $post->post_type && $post->post_type->get_key() !== $post_type->get_key() ) {
				throw new \Exception( __( 'You cannot edit this post.', 'voxel-backend' ) );
			}

			if ( ! $post && ! current_user_can( $post_type->wp_post_type->cap->create_posts ) ) {
				throw new \Exception( __( 'You cannot edit this post.', 'voxel-backend' ) );
			}

			$widget = new \Voxel\Widgets\Create_Post( [
				'id' => '7e8fe52',
				'elType' => 'widget',
				'widgetType' => 'ts-create-post',
				'elements' => [],
				'settings' => [
					'ts_post_type' => $post_type->get_key(),
					'_ts_admin_mode' => true,
					'_ts_admin_mode_nonce' => wp_create_nonce( 'vx_create_post_admin_mode' ),
				],
			], [] );

			require locate_template( 'templates/backend/edit-post-fields.php' );
		} catch ( \Exception $e ) {
			printf( '<p title="%s" style="display: none;">%s</p>', $e->getMessage(), __( 'An error occurred.', 'voxel-backend' ) );
			exit;
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function print_archive_template( $post_type ) {
		if ( ! ( $post_type && $post_type->is_managed_by_voxel() ) ) {
			return;
		}

		$template_id = $post_type->get_templates()['archive'] ?? null;

		if ( post_password_required( $template_id ) ) {
			return;
		}

		if ( ! \Elementor\Plugin::$instance->documents->get( $template_id )->is_built_with_elementor() ) {
			return;
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
	}

	protected function save_post( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['vx_admin_save_post_nonce'] ?? '', 'vx_admin_save_post_nonce' )  ) {
			return;
		}

		if ( is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		do_action( 'voxel/admin/save_post', \Voxel\Post::get( $post ) );
	}

	protected function add_verification_metabox() {
		$post = \Voxel\Post::get( get_post() );
		if ( ! ( $post && $post->is_managed_by_voxel() ) ) {
			return;
		}

		add_meta_box(
			'vx_verification',
			_x( 'Verification Status', 'Post verification status metabox title', 'voxel' ),
			function() use ( $post ) {
				wp_nonce_field( 'vx_admin_save_post_nonce', 'vx_admin_save_post_nonce' );
				?>
				<select name="vx_verification_status" style="width: 100%; margin-top: 5px;">
					<option value="verified" <?php selected( $post->is_verified() ) ?>><?= __( 'Verified', 'voxel-backend' ) ?></option>
					<option value="unverified" <?php selected( ! $post->is_verified() ) ?>><?= __( 'Unverified', 'voxel-backend' ) ?></option>
				</select>
			<?php },
			null,
			'side',
		);
	}

	public function add_author_metabox() {
		$post = \Voxel\Post::get( get_post() );
		if ( ! ( $post && $post->is_managed_by_voxel() ) ) {
			return;
		}

		add_meta_box(
			'vx_author',
			_x( 'Author', 'author metabox', 'voxel-backend' ),
			function() use ( $post ) {
				$author = $post->get_author();

				$config = [];
				if ( $author ) {
					$config = [
						'author' => [
							'id' => $author->get_id(),
							'avatar' => $author->get_avatar_markup(),
							'display_name' => $author->get_display_name(),
							'roles' => $author->get_roles(),
							'edit_link' => $author->get_edit_link(),
						],
					];
				}

				wp_nonce_field( 'vx_admin_save_post_nonce', 'vx_admin_save_post_nonce' );
				require locate_template( 'templates/backend/author-metabox.php' );
			},
			null,
			'side',
		);
	}

	protected function save_verification_status( $post ) {
		$current_status = $post->is_verified();
		$new_status = ( $_POST['vx_verification_status'] ?? null ) === 'verified';
		if ( $current_status !== $new_status ) {
			$post->set_verified( $new_status );
		}
	}

	protected function save_author( $post ) {
		$new_author_id = is_numeric( $_POST['vx_author'] ?? null ) ? absint( $_POST['vx_author'] ) : null;
		if ( $new_author_id !== null && $new_author_id !== absint( $post->get_author_id() ) && ( $new_author = \Voxel\User::get( $new_author_id ) ) ) {
			global $wpdb;
			$wpdb->update( $wpdb->posts, [
				'post_author' => $new_author->get_id(),
			], $where = [ 'ID' => $post->get_id() ] );
		}
	}

	protected function backend_index_post( $post ) {
		if ( $post->is_managed_by_voxel() ) {
			$post = \Voxel\Post::force_get( $post->get_id() );
			$post->should_index() ? $post->index() : $post->unindex();
		}
	}

	protected function send_notifications( $post ) {
		if ( ! $post->is_managed_by_voxel() ) {
			return;
		}

		$current_status = $_POST['post_status'] ?? null;
		$previous_status = $_POST['original_post_status'] ?? null;

		if ( $current_status === 'publish' && in_array( $previous_status, [ 'pending', 'rejected' ], true ) ) {
			( new \Voxel\Events\Post_Approved_Event( $post->post_type ) )->dispatch( $post->get_id() );
		}

		if ( $current_status === 'rejected' && $previous_status !== 'rejected' ) {
			( new \Voxel\Events\Post_Rejected_Event( $post->post_type ) )->dispatch( $post->get_id() );
		}
	}

	protected function unindex_on_trash( $post_id ) {
		$post = \Voxel\Post::get( $post_id );
		if ( $post && $post->is_managed_by_voxel() ) {
			$post->unindex();
		}
	}

	protected function wall_stats_updated( $post_id ) {
		$this->_maybe_reindex_activiy_column( 'wall', $post_id );
	}

	protected function review_stats_updated( $post_id ) {
		$this->_maybe_reindex_activiy_column( 'reviews', $post_id );
		$this->_maybe_reindex_rating_column( $post_id );
	}

	protected function timeline_stats_updated( $post_id ) {
		$this->_maybe_reindex_activiy_column( 'timeline', $post_id );
	}

	protected function _maybe_reindex_activiy_column( $activity_type, $post_id, $is_reply = false ) {
		$post = \Voxel\Post::get( $post_id );

		if ( ! ( $post && $post->should_index() ) ) {
			return;
		}

		$orderbys = $post->post_type->get_search_orders();
		foreach ( $orderbys as $orderby_group ) {
			foreach ( (array) $orderby_group->get_prop('clauses') as $clause ) {
				if (
					$clause['type'] === 'latest-activity'
					&& $clause['activity'] === $activity_type
					&& ( ! $is_reply || ( $is_reply && $clause['include_replies'] ) )
				) {
					$post->index();
					return;
				}
			}
		}
	}

	protected function wall_reply_stats_updated( $post_id ) {
		$this->_maybe_reindex_activiy_column( 'wall', $post_id, true );
	}

	protected function review_reply_stats_updated( $post_id ) {
		$this->_maybe_reindex_activiy_column( 'reviews', $post_id, true );
	}

	protected function timeline_reply_stats_updated( $post_id ) {
		$this->_maybe_reindex_activiy_column( 'timeline', $post_id, true );
	}

	protected function _maybe_reindex_rating_column( $post_id ) {
		$post = \Voxel\Post::get( $post_id );
		if ( ! ( $post && $post->should_index() ) ) {
			return;
		}

		$orderbys = $post->post_type->get_search_orders();
		foreach ( $orderbys as $orderby_group ) {
			foreach ( (array) $orderby_group->get_prop('clauses') as $clause ) {
				if ( $clause['type'] === 'rating' ) {
					$post->index();
					return;
				}
			}
		}
	}

	protected function remove_taxonomy_metaboxes() {
		$screen = get_current_screen();
		if ( ! ( $screen && $screen->post_type ) ) {
			return;
		}

		$post_type = \Voxel\Post_Type::get( $screen->post_type );
		if ( ! ( $post_type && $post_type->is_managed_by_voxel() ) ) {
			return;
		}

		foreach ( $post_type->get_fields() as $field ) {
			if ( $field->get_type() === 'taxonomy' && ( $taxonomy_key = $field->get_prop('taxonomy') ) ) {
				remove_meta_box( $taxonomy_key.'div', $post_type->get_key(), 'side' );
			}
		}
	}

	protected function limit_revision_count( $num, $post ) {
		if ( ! ( $post instanceof \WP_Post && $post->post_type && post_type_supports( $post->post_type, 'revisions' ) ) ) {
			return 0;
		}

		$max = \Voxel\get( 'settings.db.max_revisions', 5 );
		return absint( $max );
	}
}
