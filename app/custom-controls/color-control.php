<?php

namespace Voxel\Custom_Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Color_Control extends \Elementor\Control_Color {

	public function get_value( $control, $settings ) {
		$value = parent::get_value( $control, $settings );
		if ( strncmp( $value, '@tags()', 7 ) === 0 ) {
			$value = \Voxel\render( $value );
		}

		return $value;
	}
}
