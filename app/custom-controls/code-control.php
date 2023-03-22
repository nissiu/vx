<?php

namespace Voxel\Custom_Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Code_Control extends \Elementor\Control_Code {

	public function get_value( $control, $settings ) {
		$value = parent::get_value( $control, $settings );
		if ( strncmp( $value, '@tags()', 7 ) === 0 ) {
			$value = \Voxel\render( $value );
		}

		return $value;
	}
}
