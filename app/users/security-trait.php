<?php

namespace Voxel\Users;

if ( ! defined('ABSPATH') ) {
	exit;
}

trait Security_Trait {

	public function is_confirmed() {
		return ! get_user_meta( $this->get_id(), 'voxel:confirmation', true );
	}

	public function send_confirmation_code() {
		$code = \Voxel\random_string(5);
		$subject = _x( 'Account confirmation', 'auth', 'voxel' );
		$message = sprintf( _x( 'Your confirmation code is %s', 'auth', 'voxel' ), $code );

		wp_mail( $this->get_email(), $subject, \Voxel\email_template( $message ), [
			'Content-type: text/html; charset: '.get_bloginfo( 'charset' ),
		] );

		// give user 30 minutes to enter correct code
		update_user_meta( $this->get_id(), 'voxel:confirmation', wp_slash( wp_json_encode( [
			'code' => password_hash( $code, PASSWORD_DEFAULT ),
			'expires' => time() + ( 30 * MINUTE_IN_SECONDS ),
		] ) ) );
	}

	public function verify_confirmation_code( $code ) {
		$confirmation = json_decode( get_user_meta( $this->get_id(), 'voxel:confirmation', true ), ARRAY_A );
		if ( ! is_array( $confirmation ) || empty( $confirmation['code'] ) || empty( $confirmation['expires'] ) ) {
			throw new \Exception( __( 'Invalid request.', 'voxel' ) );
		}

		if ( $confirmation['expires'] < time() ) {
			throw new \Exception( __( 'Please try again.', 'voxel' ) );
		}

		if ( ! password_verify( $code, $confirmation['code'] ) ) {
			throw new \Exception( _x( 'Code is not correct.', 'auth', 'voxel' ) );
		}
	}

	public function send_recovery_code() {
		$code = \Voxel\random_string(10);
		$subject = _x( 'Account recovery', 'auth', 'voxel' );
		$message = sprintf( _x( 'Your recovery code is %s', 'auth', 'voxel' ), $code );

		wp_mail( $this->get_email(), $subject, \Voxel\email_template( $message ), [
			'Content-type: text/html; charset: '.get_bloginfo( 'charset' ),
		] );

		// give user 2 minutes to enter correct code
		update_user_meta( $this->get_id(), 'voxel:recovery', wp_slash( wp_json_encode( [
			'code' => password_hash( $code, PASSWORD_DEFAULT ),
			'expires' => time() + ( 2 * MINUTE_IN_SECONDS ),
		] ) ) );
	}

	public function verify_recovery_code( $code ) {
		$recovery = json_decode( get_user_meta( $this->get_id(), 'voxel:recovery', true ), ARRAY_A );
		if ( ! is_array( $recovery ) || empty( $recovery['code'] ) || empty( $recovery['expires'] ) ) {
			throw new \Exception( __( 'Invalid request.', 'voxel' ) );
		}

		if ( $recovery['expires'] < time() ) {
			throw new \Exception( _x( 'Recovery session has expired.', 'auth', 'voxel' ) );
		}

		if ( ! password_verify( $code, $recovery['code'] ) ) {
			throw new \Exception( _x( 'Code is not correct.', 'auth', 'voxel' ) );
		}
	}

	public function send_email_update_code( $email ) {
		$code = \Voxel\random_string(5);
		$subject = _x( 'Update email address', 'auth', 'voxel' );
		$message = sprintf( _x( 'Your confirmation code is %s', 'auth', 'voxel' ), $code );

		wp_mail( $email, $subject, \Voxel\email_template( $message ), [
			'Content-type: text/html; charset: '.get_bloginfo( 'charset' ),
		] );

		// give user 2 minutes to enter correct code
		update_user_meta( $this->get_id(), 'voxel:email_update', wp_slash( wp_json_encode( [
			'code' => password_hash( $code, PASSWORD_DEFAULT ),
			'expires' => time() + ( 5 * MINUTE_IN_SECONDS ),
			'email' => $email,
		] ) ) );
	}

	public function verify_email_update_code( $code ) {
		$update = json_decode( get_user_meta( $this->get_id(), 'voxel:email_update', true ), ARRAY_A );
		if ( ! is_array( $update ) || empty( $update['code'] ) || empty( $update['expires'] ) ) {
			throw new \Exception( __( 'Invalid request.', 'voxel' ) );
		}

		if ( $update['expires'] < time() ) {
			throw new \Exception( _x( 'Code has expired.', 'auth', 'voxel' ) );
		}

		if ( ! password_verify( $code, $update['code'] ) ) {
			throw new \Exception( _x( 'Code is not correct.', 'auth', 'voxel' ) );
		}

		return $update['email'] ?? null;
	}
}
