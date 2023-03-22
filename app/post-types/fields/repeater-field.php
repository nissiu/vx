<?php

namespace Voxel\Post_Types\Fields;

use \Voxel\Form_Models;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Repeater_Field extends Base_Post_Field {

	protected $props = [
		'type' => 'repeater',
		'label' => 'Repeater',
		'min' => null,
		'max' => null,
		'fields' => [],
		'row_label' => null,

		'additions_enabled' => false,
		'additions_field' => null,
		'additions_mode' => 'multiple',

		'l10n_item' => 'Item',
		'l10n_add_row' => 'Add row',
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'key' => $this->get_model( 'key', [ 'width' => '1/2' ] ),
			'description' => $this->get_description_model(),
			'required' => $this->get_required_model(),
			'min' => [
				'type' => Form_Models\Number_Model::class,
				'label' => 'Minimum repeater items',
				'width' => '1/2',
			],
			'max' => [
				'type' => Form_Models\Number_Model::class,
				'label' => 'Maximum repeater items',
				'width' => '1/2',
			],
			'row_label' => function() { ?>
				<div class="ts-form-group ts-col-1-1">
					<label>Row label</label>
					<select v-model="field.row_label">
						<option value=""></option>
						<template v-if="field.additions_enabled">
							<option value="addition:label">Addition: Label</option>
							<option value="addition:price">Addition: Price</option>
						</template>
						<template v-for="field in field.fields">
							<option v-if="['text','number','phone','email','date','select','url','taxonomy'].includes(field.type)" :value="field.key">{{ field.label }}</option>
						</template>
					</select>
				</div>
			<?php },

			'l10n_item' => [
				'type' => Form_Models\Text_Model::class,
				'label' => 'Default row label',
				'width' => '1/2',
			],

			'l10n_add_row' => [
				'type' => Form_Models\Text_Model::class,
				'label' => 'Add row label',
				'width' => '1/2',
			],

			'additions_enabled' => [
				'type' => Form_Models\Switcher_Model::class,
				'v-if' => '!repeater',
				'label' => 'Generate product additions from repeater items',
				'width' => '1/1',
			],

			'additions_field' => function() { ?>
				<div class="ts-form-group ts-col-1-1" v-if="!repeater && field.additions_enabled">
					<label>Product field</label>
					<select v-model="field.additions_field">
						<option v-for="field in $root.getFieldsByType('product')" :value="field.key">
							{{ field.label }}
						</option>
					</select>
				</div>
			<?php },

			'additions_mode' => [
				'v-if' => '!repeater && field.additions_enabled',
				'type' => Form_Models\Select_Model::class,
				'label' => 'Additions mode',
				'choices' => [
					'single' => 'Single: Customers can select only one addition during checkout',
					'multiple' => 'Multiple: Customers can select multiple additions during checkout',
				],
			],
		];
	}

	public function sanitize( $rows ) {
		if ( ! is_array( $rows ) ) {
			return [];
		}

		$sanitized = [];
		foreach ( (array) $rows as $row_index => $row ) {
			if ( $this->props['additions_enabled'] && isset( $row['meta:additions'] ) ) {
				$sanitized[ $row_index ]['meta:additions'] = [
					'price' => abs( (float) ( $row['meta:additions']['price'] ?? 0 ) ),
					'label' => sanitize_text_field( $row['meta:additions']['label'] ?? '' ),
					'has_quantity' => !! ( $row['meta:additions']['has_quantity'] ?? false ),
					'min' => is_numeric( $row['meta:additions']['min'] ) ? abs( $row['meta:additions']['min'] ) : null,
					'max' => is_numeric( $row['meta:additions']['max'] ) ? abs( $row['meta:additions']['max'] ) : null,
				];
			}

			foreach ( $this->get_fields() as $field ) {
				$field->set_repeater_index( $row_index );
				if ( ! isset( $row[ $field->get_key() ] ) ) {
					$sanitized[ $row_index ][ $field->get_key() ] = null;
				} else {
					$sanitized[ $row_index ][ $field->get_key() ] = $field->sanitize( $row[ $field->get_key() ] );
				}
			}
		}

		return $sanitized;
	}

	public function validate( $rows ): void {
		foreach ( $rows as $row_index => $row ) {
			foreach ( $this->get_fields() as $field ) {
				$field->set_repeater_index( $row_index );

				try {
					$field->check_validity( $row[ $field->get_key() ] );
				} catch ( \Exception $e ) {
					throw $e;
				}
			}

			if ( isset( $row['meta:additions'] ) ) {
				if ( empty( $row['meta:additions']['label'] ) ) {
					throw new \Exception(
						\Voxel\replace_vars( _x( 'Label is required for "@field_name" items.', 'field validation', 'voxel' ), [
							'@field_name' => $this->get_label(),
						] )
					);
				}

				if ( $row['meta:additions']['has_quantity'] ) {
					if ( $row['meta:additions']['min'] === null || $row['meta:additions']['max'] === null || $row['meta:additions']['min'] > $row['meta:additions']['max'] ) {
						throw new \Exception(
							\Voxel\replace_vars( _x( 'Provided quantity values for "@field_name" item are not valid.', 'field validation', 'voxel' ), [
								'@field_name' => $this->get_label(),
							] )
						);
					}
				}
			}
		}
	}

	public function update( $rows ): void {
		$rows = $this->_prepare_rows_for_storage( $rows );

		if ( empty( $rows ) ) {
			delete_post_meta( $this->post->get_id(), $this->get_key() );
		} else {
			update_post_meta( $this->post->get_id(), $this->get_key(), wp_slash( wp_json_encode( $rows ) ) );
		}
	}

	public function update_value_in_repeater( $rows ) {
		return $this->_prepare_rows_for_storage( $rows );
	}

	protected function _prepare_rows_for_storage( $rows ) {
		foreach ( $rows as $row_index => $row ) {
			foreach ( $this->get_fields() as $field ) {
				$field->set_post( $this->post );
				$field->set_repeater_index( $row_index );

				if ( $row[ $field->get_key() ] === null ) {
					unset( $rows[ $row_index ][ $field->get_key() ] );
					continue;
				}

				$value = $field->update_value_in_repeater( $row[ $field->get_key() ] );
				if ( $value === null ) {
					unset( $rows[ $row_index ][ $field->get_key() ] );
					continue;
				}

				$rows[ $row_index ][ $field->get_key() ] = $value;
			}

			if ( empty( $row ) ) {
				unset( $rows[ $row_index ] );
			}
		}

		return $rows;
	}

	public function get_value_from_post() {
		return (array) json_decode( get_post_meta(
			$this->post->get_id(), $this->get_key(), true
		), ARRAY_A );
	}

	public function get_fields() {
		$fields = [];

		$config = $this->props['fields'] ?? [];
		$field_types = \Voxel\config('post_types.field_types');

		foreach ( $config as $field_data ) {
			if ( ! is_array( $field_data ) || empty( $field_data['type'] ) || empty( $field_data['key'] ) ) {
				continue;
			}

			if ( isset( $field_types[ $field_data['type'] ] ) ) {
				$field = new $field_types[ $field_data['type'] ]( $field_data );
				$field->set_post_type( $this->post_type );
				$field->set_repeater( $this );
				$field->set_step( $this->get_step() );

				if ( $this->post ) {
					$field->set_post( $this->post );
				}

				$fields[ $field->get_key() ] = $field;
			}
		}

		return $fields;
	}

	protected function frontend_props(): array {
		wp_enqueue_script( 'jquery-ui-sortable' );

		$value = $this->get_value();
		$fields = $this->get_fields();

		$rows = [];
		foreach ( (array) $value as $repeater_index => $row ) {
			foreach ( $fields as $_field ) {
				$field = clone $_field;
				$field->set_repeater_index( $repeater_index );
				$rows[ $repeater_index ][ $field->get_key() ] = $field->get_frontend_config();
			}

			if ( $this->props['additions_enabled'] ) {
				$rows[ $repeater_index ]['meta:additions'] = [
					'key' => 'meta:additions',
					'label' => $row['meta:additions']['label'] ?? '',
					'price' => $row['meta:additions']['price'] ?? 0,
					'has_quantity' => $row['meta:additions']['has_quantity'] ?? false,
					'min' => $row['meta:additions']['min'] ?? null,
					'max' => $row['meta:additions']['max'] ?? null,
				];
			}

			$rows[ $repeater_index ]['meta:state'] = [
				'key' => 'meta:state',
				'collapsed' => true,
			];
		}

		$config = array_map( function( $field ) {
			$field = clone $field;
			$field->set_repeater_index(-1); // to be used as blueprint for new rows, value must be null
			return $field->get_frontend_config();
		}, $fields );

		if ( $this->props['additions_enabled'] ) {
			$config['meta:additions'] = [
				'key' => 'meta:additions',
				'label' => '',
				'price' => null,
				'has_quantity' => false,
				'min' => null,
				'max' => null,
			];
		}

		$config['meta:state'] = [
			'key' => 'meta:state',
			'collapsed' => false,
			'label' => '',
		];

		return [
			'fields' => $config,
			'rows' => $rows,
			'row_label' => $this->props['row_label'],
			'additions' => [
				'enabled' => !! $this->props['additions_enabled'],
			],
			'l10n' => [
				'item' => $this->props['l10n_item'],
				'add_row' => $this->props['l10n_add_row'],
			],
		];
	}

	public function get_field_templates() {
		$templates = [];
		foreach ( $this->get_fields() as $field ) {
			if ( $template = locate_template( sprintf( 'templates/widgets/create-post/%s-field.php', $field->get_type() ) ) ) {
				$templates[] = $template;
			}

			if ( $field->get_type() === 'repeater' ) {
				$templates = array_merge( $templates, $field->get_field_templates() );
			}
		}

		return $templates;
	}

	protected function get_row( $index ) {
		$rows = $this->get_value();
		if ( ! ( is_array( $rows ) && isset( $rows[ $index ] ) ) ) {
			return null;
		}

		return $rows[ $index ];
	}

	public function exports() {
		$properties = array_filter( array_map( function( $field ) {
			return $field->exports();
		}, $this->get_fields() ) );

		if ( $this->props['additions_enabled'] ) {
			$properties['addition:id'] = [
				'label' => 'ID',
				'type' => \Voxel\T_STRING,
				'callback' => function( $index ) {
					$row = $this->get_row( $index )['meta:additions'] ?? null;
					$form_id = substr( md5( $this->post->get_id().'-'.$this->props['additions_field'] ), 0, 6 );
					$addition_id = substr( md5(
						join( '-', [ $this->get_key(), $index, $row['label'] ?? '', $row['price'] ?? '' ] )
					), 0, 10 );

					return base64_encode( wp_json_encode( [
						'form' => $form_id,
						'type' => $this->get_key(),
						'id' => $addition_id,
					] ) );
				},
			];

			$properties['addition:label'] = [
				'label' => 'Label',
				'type' => \Voxel\T_STRING,
				'callback' => function( $index ) {
					return $this->get_row( $index )['meta:additions']['label'] ?? null;
				},
			];

			$properties['addition:price'] = [
				'label' => 'Price',
				'type' => \Voxel\T_NUMBER,
				'callback' => function( $index ) {
					return $this->get_row( $index )['meta:additions']['price'] ?? null;
				},
			];

			$properties['addition:has_quantity'] = [
				'label' => 'Has quantity?',
				'type' => \Voxel\T_STRING,
				'callback' => function( $index ) {
					return ( $this->get_row( $index )['meta:additions']['has_quantity'] ?? null ) ? '1' : '';
				},
			];

			$properties['addition:min'] = [
				'label' => 'Min quantity',
				'type' => \Voxel\T_NUMBER,
				'callback' => function( $index ) {
					return $this->get_row( $index )['meta:additions']['min'] ?? null;
				},
			];

			$properties['addition:max'] = [
				'label' => 'Max quantity',
				'type' => \Voxel\T_NUMBER,
				'callback' => function( $index ) {
					return $this->get_row( $index )['meta:additions']['max'] ?? null;
				},
			];
		}

		return [
			'label' => $this->get_label(),
			'type' => \Voxel\T_OBJECT,
			'properties' => $properties,
			'loopable' => true,
			'loopcount' => function() {
				$value = $this->get_value();
				return $value === null ? 0 : count( $this->get_value() );
			},
		];
	}
}
