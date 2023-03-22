<?php

namespace Voxel\Post_Types\Order_By;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Rating_Order extends Base_Search_Order {

	protected $props = [
		'type' => 'rating',
		'order' => 'DESC',
		'mode' => 'weighted',
	];

	public function get_label(): string {
		return 'Rating';
	}

	public function get_models(): array {
		return [
			'order' => $this->get_order_model(),
			'mode' => [
				'type' => \Voxel\Form_Models\Select_Model::class,
				'label' => 'Mode',
				'width' => '1/1',
				'choices' => [
					'weighted' => 'Weighted average',
					'simple' => 'Simple average',
				],
			],
		];
	}

	public function setup( \Voxel\Post_Types\Index_Table $table ): void {
		$table->add_column( '`rating` SMALLINT' );
		$table->add_key( 'KEY(`rating`)' );
	}

	public function index( \Voxel\Post $post ): array {
		$stats = $post->repository->get_review_stats();

		if ( $this->props['mode'] === 'simple' ) {
			$average = is_numeric( $stats['average'] ) ? floatval( $stats['average'] ) : null;
			return [
				'rating' => ! is_null( $average ) ? round( $average * 10000 ) : 'NULL',
			];
		} else {
			$total = array_sum( $stats['by_score'] );
			$sum = 0;
			foreach ( $stats['by_score'] as $level => $count ) {
				if ( $count > 0 ) {
					$sum += $this->get_weighted_rating_for_level( $level, $count ) * $count;
				}
			}

			if ( $total > 0 ) {
				$average = $sum / $total;
			} else {
				$average = null;
			}

			return [
				'rating' => ! is_null( $average ) ? round( $average * 10000 ) : 'NULL',
			];
		}
	}

	public function query( \Voxel\Post_Types\Index_Query $query, array $args, array $clause_args ): void {
		$query->orderby( sprintf(
			'`rating` %s',
			$this->props['order'] === 'ASC' ? 'ASC' : 'DESC'
		) );
	}

	protected function get_weighted_rating_for_level( $level, $count ) {
		if ( $level === 0 ) {
			return 0;
		}

		$variation = 0.4;
		$sign = $level < 0 ? -1 : 1;
		$percentage = log( $count, 1000 );
		$weight = ( ( $percentage * $variation ) - ( $variation / 2 ) ) * $sign;
		return $level + $weight;
	}
}
