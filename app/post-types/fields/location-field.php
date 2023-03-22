<?php

namespace Voxel\Post_Types\Fields;

use \Voxel\Form_Models;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Location_Field extends Base_Post_Field {

	protected $props = [
		'type' => 'location',
		'label' => 'Location',
		'placeholder' => '',
	];

	protected $supported_conditions = [
		'address' => [
			'label' => 'Address',
			'supported_conditions' => [ 'text' ],
		],
		'latitude' => [
			'label' => 'Latitude',
			'supported_conditions' => [ 'number' ],
		],
		'longitude' => [
			'label' => 'Longitude',
			'supported_conditions' => [ 'number' ],
		],
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'key' => $this->get_key_model(),
			'placeholder' => $this->get_placeholder_model(),
			'description' => $this->get_description_model(),
			'required' => $this->get_required_model(),
		];
	}

	public function sanitize( $value ) {
		$location = [
			'address' => $value['address'] ? sanitize_text_field( $value['address'] ) : null,
			'map_picker' => !! ( $value['map_picker'] ?? false ),
			'latitude' => $value['latitude'] ? round( floatval( $value['latitude'] ), 5 ) : null,
			'longitude' => $value['longitude'] ? round( floatval( $value['longitude'] ), 5 ) : null,
		];

		if ( is_null( $location['address'] ) || is_null( $location['latitude'] ) || is_null( $location['longitude'] ) ) {
			return null;
		}

		$location['latitude'] = \Voxel\clamp( $location['latitude'], -90, 90 );
		$location['longitude'] = \Voxel\clamp( $location['longitude'], -180, 180 );
		return $location;
	}

	public function update( $value ): void {
		if ( empty( $value ) ) {
			delete_post_meta( $this->post->get_id(), $this->get_key() );
		} else {
			update_post_meta( $this->post->get_id(), $this->get_key(), wp_slash( wp_json_encode( $value ) ) );
		}
	}

	public function get_value_from_post() {
		$value = (array) json_decode( get_post_meta(
			$this->post->get_id(), $this->get_key(), true
		), ARRAY_A );

		return [
			'address' => $value['address'] ?? null,
			'map_picker' => $value['map_picker'] ?? false,
			'latitude' => $value['latitude'] ?? null,
			'longitude' => $value['longitude'] ?? null,
		];
	}

	protected function editing_value() {
		if ( ! $this->post ) {
			$value = [];
		} else {
			$value = $this->get_value();
			if ( ! is_array( $value ) ) {
				$value = [];
			}
		}

		return [
			'address' => $value['address'] ?? null,
			'map_picker' => $value['map_picker'] ?? false,
			'latitude' => $value['latitude'] ?? null,
			'longitude' => $value['longitude'] ?? null,
		];
	}

	protected function frontend_props() {
		\Voxel\enqueue_maps();
		return [
			'placeholder' => $this->props['placeholder'] ?: $this->props['label'],
		];
	}

	protected function _get_distance_from_nearby_ref() {
		$value = $this->get_value();
		if ( ! ( $value['latitude'] ) && $value['longitude'] ) {
			return null;
		}

		global $_voxel_nearby_ref;
		if ( ! is_array( $_voxel_nearby_ref ) ) {
			return null;
		}

		$latFrom = $_voxel_nearby_ref['lat'];
		$lngFrom = $_voxel_nearby_ref['lng'];

		return round( \Voxel\st_distance_sphere(
			$_voxel_nearby_ref['lat'],
			$_voxel_nearby_ref['lng'],
			$value['latitude'],
			$value['longitude']
		) );
	}

	public function exports() {
		return [
			'type' => \Voxel\T_OBJECT,
			'label' => $this->get_label(),
			'properties' => [
				'address' => [
					'label' => 'Address',
					'type' => \Voxel\T_STRING,
					'callback' => function() {
						return $this->get_value()['address'];
					},
				],
				'lat' => [
					'label' => 'Latitude',
					'type' => \Voxel\T_NUMBER,
					'callback' => function() {
						return $this->get_value()['latitude'];
					},
				],
				'lng' => [
					'label' => 'Longitude',
					'type' => \Voxel\T_NUMBER,
					'callback' => function() {
						return $this->get_value()['longitude'];
					},
				],
				'short_address' => [
					'label' => 'Short address',
					'type' => \Voxel\T_STRING,
					'callback' => function() {
						$address = $this->get_value()['address'];
						if ( ! $address ) {
							return null;
						}

						$parts = explode( ',', $address );
						return trim( $parts[0] );
					},
				],
				'distance' => [
					'label' => 'Distance',
					'description' => 'To be used with the Nearby order in post feed widgets.',
					'type' => \Voxel\T_OBJECT,
					'properties' => [
						'meters' => [
							'label' => 'Meters',
							'type' => \Voxel\T_NUMBER,
							'callback' => function() {
								return $this->_get_distance_from_nearby_ref();
							},
						],
						'kilometers' => [
							'label' => 'Kilometers',
							'type' => \Voxel\T_NUMBER,
							'callback' => function() {
								$distance = $this->_get_distance_from_nearby_ref();
								if ( ! is_numeric( $distance ) ) {
									return null;
								}

								return $distance / 1000;
							},
						],
						'kilometers' => [
							'label' => 'Kilometers',
							'type' => \Voxel\T_NUMBER,
							'callback' => function() {
								$distance = $this->_get_distance_from_nearby_ref();
								if ( ! is_numeric( $distance ) ) {
									return null;
								}

								return round( $distance / 1000, 3 );
							},
						],
						'miles' => [
							'label' => 'Miles',
							'type' => \Voxel\T_NUMBER,
							'callback' => function() {
								$distance = $this->_get_distance_from_nearby_ref();
								if ( ! is_numeric( $distance ) ) {
									return null;
								}

								return round( $distance / 1609.344, 3 );
							},
						],
					],
				],
			],
		];
	}
}
