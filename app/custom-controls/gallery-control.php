<?php

namespace Voxel\Custom_Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Gallery_Control extends \Elementor\Control_Gallery {

	public function get_value( $control, $settings ) {
		$value = parent::get_value( $control, $settings );

		// get image ids from dynamic tags
		if ( strncmp( $value[0]['id'] ?? '', '@tags()', 7 ) === 0 ) {
			$ids = explode( ',', \Voxel\render( $value[0]['id'] ) );
			$value = array_map( function( $id ) {
				return [
					'id' => $id,
					'url' => '',
				];
			}, $ids );
		}

		return $value;
	}

}
