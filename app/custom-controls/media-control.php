<?php

namespace Voxel\Custom_Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Media_Control extends \Elementor\Control_Media {

	public function get_value( $control, $settings ) {
		$value = parent::get_value( $control, $settings );

		// get image id from dynamic tags
		if ( strncmp( $value['id'], '@tags()', 7 ) === 0 ) {
			$media = \Voxel\render( $value['id'] );

			if ( is_numeric( $media ) ) {
				$value['id'] = $media;
				$value['url'] = wp_get_attachment_image_url( $media, 'full' );
			} else {
				$value['id'] = '';
				$value['url'] = '';
			}
		}

		return $value;
	}

}
