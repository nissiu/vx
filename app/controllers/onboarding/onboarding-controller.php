<?php

namespace Voxel\Controllers\Onboarding;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Onboarding_Controller extends \Voxel\Controllers\Base_Controller {

	protected function authorize() {
		return current_user_can( 'administrator' );
	}

	protected function hooks() {
		$this->on( 'admin_menu', '@add_menu_page' );
		$this->on( 'after_switch_theme', '@start_onboarding' );
		$this->on( 'voxel_ajax_onboarding.verify_license', '@verify_license' );
		$this->on( 'voxel_ajax_onboarding.prepare_install', '@prepare_install' );
		$this->on( 'voxel_ajax_onboarding.start_blank', '@start_blank' );
	}

	protected function add_menu_page() {
		add_menu_page(
			__( 'Onboarding', 'voxel' ),
			__( 'Onboarding', 'voxel' ),
			'manage_options',
			'voxel-onboarding',
			function() {
				wp_enqueue_script( 'vx:onboarding.js' );
				require locate_template( 'templates/backend/onboarding/onboarding.php' );
			},
			\Voxel\get_image('post-types/wizard.png'),
			'0.20'
		);
	}

	protected function prepare_install() {
		try {
			if ( ! \Voxel\is_elementor_active() ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
				require_once ABSPATH.'wp-admin/includes/file.php';
				WP_Filesystem();

				if ( ! file_exists( WP_PLUGIN_DIR.'/elementor/elementor.php' ) ) {
					$package_url = 'https://downloads.wordpress.org/plugin/elementor.3.11.5.zip';
					$download_to = \Voxel\uploads_dir('elementor.zip');
					@unlink( $download_to );

					$download_file = download_url( $package_url, $timeout = 600 );
					if ( is_wp_error( $download_file ) ) {
						throw new \Exception( _x( 'Couldn\'t download Elementor: ', 'onboarding', 'voxel-backend' ).$download_file->get_error_message() );
					}

					@copy( $download_file, $download_to );
					unlink( $download_file );

					// unzip
					$unzip_to = WP_PLUGIN_DIR.'/';
					$result = unzip_file( $download_to, $unzip_to );
					if ( is_wp_error( $result ) ) {
						throw new \Exception( _x( 'Unpacking failed: ', 'onboarding', 'voxel-backend' ).$result->get_error_message() );
					}

					// zip file is no longer needed
					@unlink( $download_to );
				}

				activate_plugin( 'elementor/elementor.php' );
			}

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function verify_license() {
		try {
			// \Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_admin_onboarding' ); // @todo
			if ( ! current_user_can('administrator') ) {
				throw new Exception( __( 'Invalid request.', 'voxel' ) );
			}

			$license_key = strtoupper( sanitize_text_field( $_GET['license_key'] ?? null ) );
			if ( empty( $license_key ) || ! str_starts_with( $license_key, 'VX' ) ) {
				throw new \Exception( _x( 'Invalid license key.', 'onboarding', 'voxel-backend' ) );
			}

			if ( strlen( $license_key ) !== 29 || substr_count( $license_key, '-' ) !== 4 ) {
				throw new \Exception( _x( 'Invalid license key.', 'onboarding', 'voxel-backend' ) );
			}

			$environment = sanitize_text_field( $_GET['environment'] ?? null );
			if ( ! in_array( $environment, [ 'staging', 'production' ], true ) ) {
				throw new \Exception( _x( 'Site environment not provided.', 'onboarding', 'voxel-backend' ) );
			}

			$request_url = add_query_arg( [
				'action' => 'voxel_licenses.verify',
				'mode' => 'update',
				'environment' => $environment,
				'license_key' => $license_key,
				'site_url' => home_url('/'),
			], 'https://getvoxel.io/?vx=1' );

			$request = wp_remote_get( $request_url, [
				'timeout' => 10,
			] );

			$response = (array) json_decode( wp_remote_retrieve_body( $request ) );
			if ( ! isset( $response['success'] ) ) {
				throw new \Exception( _x( 'Verification request failed, please try again.', 'onboarding', 'voxel-backend' ) );
			}

			if ( ! $response['success'] ) {
				throw new \Exception( $response['message'] ?? _x( 'Could not verify license.', 'onboarding', 'voxel-backend' ) );
			}

			\Voxel\set( 'license', [
				'key' => $license_key,
				'env' => $environment,
				'active' => true,
				'last_checked' => date( 'Y-m-d H:i:s', time() ),
			] );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function start_blank() {
		\Voxel\set( 'onboarding', [ 'done' => true ] );
		return wp_send_json( [
			'success' => true,
		] );
	}

	protected function start_onboarding() {
		if ( ! \Voxel\get( 'onboarding.done' ) && ! \Voxel\get( 'onboarding.redirected' ) ) {
			\Voxel\set( 'onboarding.redirected', true );
			wp_redirect( admin_url( 'admin.php?page=voxel-onboarding&tab=welcome' ) );
			exit;
		}
	}
}
