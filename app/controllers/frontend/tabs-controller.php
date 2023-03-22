<?php

namespace Voxel\Controllers\Frontend;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Tabs_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'voxel_ajax_tabs.load', '@load_tab' );
		$this->on( 'voxel_ajax_nopriv_tabs.load', '@load_tab' );
	}

	protected function load_tab() {
		try {
			$template_id = $_GET['template_id'] ?? null;
			$post_id = $_GET['post_id'] ?? null;
			$widget_id = $_GET['widget_id'] ?? null;
			$tab_key = $_GET['tab'] ?? null;

			// check if widget exists
			$allowed_widgets = \Voxel\get_custom_page_settings( $template_id )['template_tabs'] ?? [];
			if ( ! isset( $allowed_widgets[ $widget_id ] ) ) {
				throw new \Exception( __( 'Invalid request.', 'voxel' ) );
			}

			// check if post exists
			$post = \Voxel\Post::get( $post_id );
			if ( ! $post ) {
				throw new \Exception( __( 'Invalid request.', 'voxel' ) );
			}

			\Voxel\set_current_post( $post );

			// check if template exists
			$doc = \Elementor\Plugin::$instance->documents->get( $template_id );
			if ( ! $doc ) {
				throw new \Exception( __( 'Invalid request.', 'voxel' ) );
			}

			// evaluate document visibility rules
			$behavior = \Voxel\get_page_setting( '_voxel_visibility_behavior', $template_id );
			$rules = \Voxel\get_page_setting( '_voxel_visibility_rules', $template_id );
			if ( is_array( $rules ) && ! empty( $rules ) ) {
				$rules_passed = \Voxel\evaluate_visibility_rules( $rules );
				if ( $behavior === 'hide' ) {
					$should_render = $rules_passed ? false : true;
				} else {
					$should_render = $rules_passed ? true : false;
				}

				if ( ! $should_render ) {
					throw new \Exception( __( 'Access denied.', 'voxel' ) );
				}
			}

			// find widget in template
			$widget = null;
			$data = $doc->get_elements_data();
			$path = explode( '.', $allowed_widgets[ $widget_id ] );

			while ( ! empty( $path ) ) {
				$index = array_shift( $path );
				if ( ! isset( $data[ $index ] ) ) {
					break;
				}

				// evaluate element visibility rules
				$behavior = $data[ $index ]['settings']['_voxel_visibility_behavior'] ?? null;
				$rules = $data[ $index ]['settings']['_voxel_visibility_rules'] ?? null;
				if ( is_array( $rules ) && ! empty( $rules ) ) {
					$rules_passed = \Voxel\evaluate_visibility_rules( $rules );
					if ( $behavior === 'hide' ) {
						$should_render = $rules_passed ? false : true;
					} else {
						$should_render = $rules_passed ? true : false;
					}

					if ( ! $should_render ) {
						throw new \Exception( __( 'Access denied.', 'voxel' ) );
					}
				}

				if ( empty( $path ) && $data[ $index ]['elType'] === 'widget' && $data[ $index ]['widgetType'] === 'ts-template-tabs' ) {
					$widget = $data[ $index ];
					break;
				}

				$data = $data[ $index ]['elements'];
			}

			if ( ! $widget ) {
				throw new \Exception( __( 'Invalid request.', 'voxel' ) );
			}

			$tabs = (array) ( $widget['settings']['ts_tabs'] ?? [] );
			$tab = null;

			foreach ( $tabs as $t ) {
				if ( ( $t['url_key'] ?? '' ) === $tab_key ) {
					$tab = $t;
					break;
				}
			}

			if ( ! $tab ) {
				throw new \Exception( __( 'Invalid request.', 'voxel' ) );
			}

			// evaluate tab visibility rules
			$behavior = $tab['_voxel_visibility_behavior'] ?? null;
			$rules = $tab['_voxel_visibility_rules'] ?? null;
			if ( is_array( $rules ) && ! empty( $rules ) ) {
				$rules_passed = \Voxel\evaluate_visibility_rules( $rules );
				if ( $behavior === 'hide' ) {
					$should_render = $rules_passed ? false : true;
				} else {
					$should_render = $rules_passed ? true : false;
				}

				if ( ! $should_render ) {
					throw new \Exception( __( 'Access denied.', 'voxel' ) );
				}
			}

			echo '<div class="tab-wrapper">';

			do_action( 'voxel/before_render_tab_template' );

			if ( is_admin() ) {
				\Voxel\print_template_css( $tab['template_id'] ?? null );
			}

			\Voxel\print_template( \Voxel\render( $tab['template_id'] ?? null ) );
			wp_print_styles( (array) ( $GLOBALS['wp_styles']->queue ?? [] ) );
			wp_print_scripts( (array) ( $GLOBALS['wp_scripts']->queue ?? [] ) );

			echo '</div>';
			exit;
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}
}
