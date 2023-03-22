<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base_Widget extends \Elementor\Widget_Base {

	protected $vx_key_cache = [];

	protected function apply_controls( $option_group ) {
		$controls = $option_group.'::controls';
		$controls( $this );
	}

	/**
	 * Workaround to getting the widget's template id.
	 *
	 * @link https://github.com/elementor/elementor/issues/7495#issuecomment-1019656235
	 * @since 1.0
	 */
	protected function _get_template_id() {
		if ( is_admin() ) {
			$current_doc = \Elementor\Plugin::$instance->documents->get_current();
			return $current_doc ? $current_doc->get_id() : get_the_ID();
		}

		ini_set( 'zend.exception_ignore_args', 0 );
		$e = new \Exception();
		$trace = $e->getTrace();
		foreach ( $trace as $row ) {
			if ( $row['function'] === 'get_builder_content' && isset( $row['args'][0] ) ) {
				$template_id = $row['args'][0];
				break;
			}
		}

		return $template_id ?? get_the_ID();
	}

	protected function vx_color( $label, $selectors, $selector_value = null ) {
		if ( is_string( $selectors ) && ! is_null( $selector_value ) ) {
			$selector_key = $selectors;
			$selectors = [
				$selector_key => $selector_value,
			];
		}

		$control_key = sprintf( '%s-%s', $this->get_current_section()['section'], sanitize_title( $label ) );
		if ( ! isset( $this->vx_key_cache[ $control_key ] ) ) {
			$this->vx_key_cache[ $control_key ] = 0;
		} else {
			$this->vx_key_cache[ $control_key ]++;
		}

		$count = $this->vx_key_cache[ $control_key ];
		$suffix = $count === 0 ? '' : '-'.$count;
		$control_key .= $suffix;

		$this->add_control( $control_key, [
			'label' => $label,
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => $selectors,
		] );
	}
}
