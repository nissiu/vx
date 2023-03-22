<?php

namespace Voxel\Custom_Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Relation_Control extends \Elementor\Base_Data_Control {

	public function get_type() {
		return 'voxel-relation';
	}

	protected function get_default_settings() {
		return [
			'label_block' => true,
			'vx_group' => '',
			'vx_target' => '',
			'vx_side' => 'left',
		];
	}

	public function content_template() {
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="vx-relation-list elementor-control-input-wrapper"></div>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		</div>
		<?php
	}

}