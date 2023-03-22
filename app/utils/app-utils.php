<?php

namespace Voxel;

if ( ! defined('ABSPATH') ) {
	exit;
}

/**
 * Retrieve a value from the config array in app/config.php
 * using the dot path access syntax for nested arrays.
 * e.g. \Voxel\config( 'post_types.field_types' );
 *
 * @since 1.0
 */
function config( $path, $default = null ) {
	static $config;
	if ( is_null( $config ) ) {
		$config = require_once locate_template( 'app/config/config.php' );
	}

	$keys = explode( '.', $path );
	$value = $config;
	foreach ( $keys as $key ) {
		if ( ! isset( $value[ $key ] ) ) {
			return $default;
		}

		$value = $value[ $key ];
	}

	return $value;
}

/**
 * Get a theme option stored in the wp_options table. Theme options
 * are stored as JSON and option keys are prefix with "voxel:".
 * Supports retrieveing nested values using the dot path access syntax.
 *
 * @since 1.0
 */
function get( $option, $default = null, $force_get = false ) {
	static $theme_options = [];

	$keys = explode( '.', $option );
	$option_group = $keys[0];

	// if option group isn't present, load it now
	if ( ! isset( $theme_options[ $option_group ] ) || $force_get ) {
		if ( $force_get ) {
			wp_cache_delete( 'voxel:'.$option_group, 'options' );
		}

		$option_value = json_decode( get_option( 'voxel:'.$option_group, '' ), ARRAY_A );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			$option_value = [];
		}

		$theme_options[ $option_group ] = (array) $option_value;
	}

	$options = $theme_options[ $option_group ];
	unset( $keys[0] );

	// recursively go through the option group to get the option in the specified path
	foreach ( $keys as $key ) {
		if ( ! isset( $options[ $key ] ) ) {
			return $default;
		}

		$options = $options[ $key ];
	}

	return $options;
}

/**
 * Set, update, or delete a theme option stored in the wp_options table.
 * Supports targeting nested values. Set value to `null` to delete.
 *
 * @since 1.0
 */
function set( $option, $value, $autoload = null ) {
	$keys = explode( '.', $option );
	$option_group = $keys[0];

	$options = \Voxel\get( $option_group );
	$original_options = &$options;
	array_shift( $keys );

	// recursively go through the option group to get the option in the specified path
	$last_index = count( $keys ) - 1;
	foreach ( $keys as $index => $key ) {
		if ( $index === $last_index ) {
			if ( $value === null ) {
				unset( $options[ $key ] );
			} else {
				$options[ $key ] = $value;
			}
			break;
		}

		if ( ! isset( $options[ $key ] ) ) {
			$options[ $key ] = [];
		}

		$options = &$options[ $key ];
	}

	if ( empty( $keys ) ) {
		if ( $value === null ) {
			delete_option( 'voxel:'.$option_group );
		} elseif ( ! is_array( $value ) ) {
			throw new \Exception( 'Voxel: Option groups can only contain an array as value.' );
		} else {
			update_option( 'voxel:'.$option_group, wp_json_encode( $value ), $autoload );
		}
	} else {
		// update option group
		update_option( 'voxel:'.$option_group, wp_json_encode( $original_options ), $autoload );
	}

	// refresh cache
	\Voxel\get( $option_group, null, true );
}
