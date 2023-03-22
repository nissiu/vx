<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pricing_Plan extends Base_Widget {

	public function get_name() {
		return 'ts-pricing-plan';
	}

	public function get_title() {
		return __( 'Pricing plans (VX)', 'voxel-elementor' );
	}

	public function get_icon() {
		return 'vxi vxi-badge';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {
		$plans = \Voxel\Membership\Plan::active();
		$default_plan = \Voxel\Membership\Plan::get_or_create_default_plan();
		$options = [
			'default' => $default_plan->get_label(),
		];

		foreach ( $plans as $plan ) {
			$pricing = $plan->get_pricing();
			foreach ( [ 'live', 'test' ] as $mode ) {
				if ( is_null( $pricing[ $mode ] ) || empty( $pricing[ $mode ]['prices'] ) ) {
					continue;
				}

				foreach ( $pricing[ $mode ]['prices'] as $price_id => $price ) {
					if ( ! $price['active'] ) {
						continue;
					}

					$option_key = sprintf(
						'%s@%s%s',
						$plan->get_key(),
						$mode === 'test' ? 'test:' : '',
						$price_id
					);

					$option_label = \Voxel\currency_format( $price['amount'], $price['currency'] );
					if ( $period = \Voxel\Membership\Plan::get_price_period( $price ) ) {
						$option_label .= sprintf( ' / %s', $period );
					}

					$options[ $option_key ] = sprintf(
						'%s%s &mdash; %s',
						$mode === 'test' ? '[TEST] ' : '',
						$plan->get_label(),
						$option_label
					);
				}
			}
		}

		$this->start_controls_section( 'ts_prices_section', [
			'label' => __( 'Price groups', 'voxel-elementor' ),
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new \Elementor\Repeater;
		$repeater->add_control( 'group_label', [
			'label' => __( 'Group label', 'voxel-elementor' ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => 'Monthly',
		] );

		$repeater->add_control( 'prices', [
			'label' => __( 'Choose prices', 'voxel-elementor' ),
			'type' => \Elementor\Controls_Manager::SELECT2,
			'multiple' => true,
			'options' => $options,
			'label_block' => true,
		] );

			$this->add_control( 'ts_price_groups', [
				'label' => __( 'Items', 'voxel-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			] );

			$this->end_controls_section();



			$this->start_controls_section(
				'plans_general',
				[
					'label' => __( 'General', 'voxel-elementor' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

				$this->add_responsive_control(
					'plans_columns',
					[
						'label' => __( 'Number of columns', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 6,
						'step' => 1,
						'default' => 3,
						'selectors' => [
							'{{WRAPPER}} .ts-plans-list' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
						],
					]
				);

				$this->add_responsive_control(
					'pplans_gap',
					[
						'label' => __( 'Item gap', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
						],

						'selectors' => [
							'{{WRAPPER}} .ts-plans-list' => 'grid-gap: {{SIZE}}{{UNIT}};',
						],

					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					[
						'name' => 'pplans_border',
						'label' => __( 'Border', 'voxel-elementor' ),
						'selector' => '{{WRAPPER}} .ts-plan-container',
					]
				);


				$this->add_responsive_control(
					'pplans_radius',
					[
						'label' => __( 'Border radius', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-plan-container' => 'border-radius: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'pplans_bg',
					[
						'label' => __( 'Background', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-plan-container' => 'background: {{VALUE}}',
						],

					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'pplans_shadow',
						'label' => __( 'Box Shadow', 'voxel-elementor' ),
						'selector' => '{{WRAPPER}} .ts-plan-container',
					]
				);

				$this->add_control(
					'plan_body',
					[
						'label' => __( 'Plan body', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_responsive_control(
					'pplans_spacing',
					[
						'label' => __( 'Body padding', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-plan-body' => 'padding: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'panel_gap',
					[
						'label' => __( 'Body content gap', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-plan-body' => 'grid-gap: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'plan_image',
					[
						'label' => __( 'Plan image', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_responsive_control(
					'plan_img_pad',
					[
						'label' => __( 'Image padding', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .ts-plan-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'plan_img_max',
					[
						'label' => __( 'height', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 500,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-plan-image img' => 'height: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'panel_pricing',
					[
						'label' => __( 'Plan pricing', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'pricing_align',
					[
						'label' => __( 'Align', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'left',
						'options' => [
							'left'  => __( 'Left', 'voxel-elementor' ),
							'center' => __( 'Center', 'voxel-elementor' ),
							'flex-end' => __( 'Right', 'voxel-elementor' ),
						],

						'selectors' => [
							'{{WRAPPER}} .ts-plan-pricing' => 'justify-content: {{VALUE}}',
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'price_typo',
						'label' => __( 'Price typography', 'voxel-elementor' ),
						'selector' => '{{WRAPPER}} .ts-plan-price',
					]
				);

				$this->add_responsive_control(
					'price_col',
					[
						'label' => __( 'Price text color', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-plan-price' => 'color: {{VALUE}}',
						],

					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'period_typo',
						'label' => __( 'Period typography', 'voxel-elementor' ),
						'selector' => '{{WRAPPER}} .ts-price-period',
					]
				);

				$this->add_responsive_control(
					'period_col',
					[
						'label' => __( 'Period text color', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-price-period' => 'color: {{VALUE}}',
						],

					]
				);

				$this->add_control(
					'plan_name_section',
					[
						'label' => __( 'Plan name', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'content_align',
					[
						'label' => __( 'Align content', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'left',
						'options' => [
							'left'  => __( 'Left', 'voxel-elementor' ),
							'center' => __( 'Center', 'voxel-elementor' ),
							'flex-end' => __( 'Right', 'voxel-elementor' ),
						],

						'selectors' => [
							'{{WRAPPER}} .ts-plan-details' => 'justify-content: {{VALUE}}',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'name_typo',
						'label' => __( 'Name typography', 'voxel-elementor' ),
						'selector' => '{{WRAPPER}} .ts-plan-name',
					]
				);

				$this->add_responsive_control(
					'name_col',
					[
						'label' => __( 'Name text color', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-plan-name' => 'color: {{VALUE}}',
						],

					]
				);



				$this->add_control(
					'plan_list_section',
					[
						'label' => __( 'Plan features', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'list_align',
					[
						'label' => __( 'Align content', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'left',
						'options' => [
							'left'  => __( 'Left', 'voxel-elementor' ),
							'center' => __( 'Center', 'voxel-elementor' ),
							'flex-end' => __( 'Right', 'voxel-elementor' ),
						],

						'selectors' => [
							'{{WRAPPER}} .ts-plan-features ul' => 'align-items: {{VALUE}}',
						],
					]
				);

				$this->add_responsive_control(
					'list_gap',
					[
						'label' => __( 'Item gap', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
						],

						'selectors' => [
							'{{WRAPPER}} .ts-plan-features ul' => 'grid-gap: {{SIZE}}{{UNIT}};',
						],

					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'list_typo',
						'label' => __( 'Typography', 'voxel-elementor' ),
						'selector' => '{{WRAPPER}} .ts-plan-features ul li span',
					]
				);

				$this->add_responsive_control(
					'list_col',
					[
						'label' => __( 'Color', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-plan-features ul li span' => 'color: {{VALUE}}',
						],

					]
				);

				$this->add_responsive_control(
					'list_ico_col',
					[
						'label' => __( 'Icon color', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-plan-features ul li i' => 'color: {{VALUE}}',
							'{{WRAPPER}} .ts-plan-features ul li svg' => 'fill: {{VALUE}}',
						],

					]
				);

				$this->add_responsive_control(
					'list_ico_size',
					[
						'label' => __( 'Icon size', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-plan-features ul li i' => 'font-size: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .ts-plan-features ul li svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'list_ico_right_pad',
					[
						'label' => __( 'Icon right padding', 'voxel-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-plan-features ul li i' => 'padding-right: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .ts-plan-features ul li svg' => 'margin-right: {{SIZE}}{{UNIT}};',
						],
					]
				);




			$this->end_controls_section();

			$this->start_controls_section(
				'pltabs_section',
				[
					'label' => __( 'Tabs', 'voxel-elementor' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

				$this->start_controls_tabs(
					'pltabs_tabs'
				);

					/* Normal tab */

					$this->start_controls_tab(
						'pltabs_normal',
						[
							'label' => __( 'Normal', 'voxel-elementor' ),
						]
					);


						$this->add_control(
							'pltabs_tabs_heading',
							[
								'label' => __( 'Tabs', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::HEADING,
								'separator' => 'before',
							]
						);

						$this->add_control(
							'pltabs_disable',
							[
								'label' => __( 'Disable tabs', 'voxel-elementor' ),
								'description' => __( 'Disable label on tablet', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::SWITCHER,

								'return_value' => 'none',
								'selectors' => [
									'{{WRAPPER}} .ts-plan-tabs' => 'display: none;',
								],
							]
						);

						$this->add_control(
							'pltabs_justify',
							[
								'label' => __( 'Justify', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::SELECT,
								'default' => 'left',
								'options' => [
									'left'  => __( 'Left', 'voxel-elementor' ),
									'center' => __( 'Center', 'voxel-elementor' ),
									'flex-end' => __( 'Right', 'voxel-elementor' ),
									'space-between' => __( 'Space between', 'voxel-elementor' ),
									'space-around' => __( 'Space around', 'voxel-elementor' ),
								],

								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs' => 'justify-content: {{VALUE}}',
								],
							]
						);

						$this->add_control(
							'pltabs_padding',
							[
								'label' => __( 'Padding', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', '%', 'em' ],
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);

						$this->add_control(
							'pltabs_margin',
							[
								'label' => __( 'Margin', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', '%', 'em' ],
								'default' => [
									'unit' => 'px',
									'bottom' => 15,
									'right' => 15,
									'left' => 0,
									'top' => 0,
								],
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);

						$this->add_group_control(
							\Elementor\Group_Control_Typography::get_type(),
							[
								'name' => 'pltabs_text',
								'label' => __( 'Tab typography' ),
								'selector' => '{{WRAPPER}} .ts-generic-tabs li a',
							]
						);

						$this->add_group_control(
							\Elementor\Group_Control_Typography::get_type(),
							[
								'name' => 'pltabs_active',
								'label' => __( 'Active tab typography' ),
								'selector' => '{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a',
							]
						);


						$this->add_control(
							'pltabs_text_color',
							[
								'label' => __( 'Text color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a' => 'color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_active_text_color',
							[
								'label' => __( 'Active text color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a' => 'color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_bg_color',
							[
								'label' => __( 'Background', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a' => 'background-color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_bg_active_color',
							[
								'label' => __( 'Active background', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a' => 'background-color: {{VALUE}}',
								],

							]
						);

						$this->add_group_control(
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'pltabs_border',
								'label' => __( 'Border', 'voxel-elementor' ),
								'selector' => '{{WRAPPER}} .ts-generic-tabs li a',
							]
						);

						$this->add_control(
							'pltabs_border_active',
							[
								'label' => __( 'Active border color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a' => 'border-color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_radius',
							[
								'label' => __( 'Border radius', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px'],
								'range' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									],
								],

								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a' => 'border-radius: {{SIZE}}{{UNIT}};',
								],
							]
						);


					$this->end_controls_tab();

					/* Hover tab */

					$this->start_controls_tab(
						'pltabs_hover',
						[
							'label' => __( 'Hover', 'voxel-elementor' ),
						]
					);

						$this->add_control(
							'pltabs_tabs_h',
							[
								'label' => __( 'Timeline tabs', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::HEADING,
								'separator' => 'before',
							]
						);

						$this->add_control(
							'pltabs_text_color_h',
							[
								'label' => __( 'Text color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a:hover' => 'color: {{VALUE}}',
								],

							]
						);



						$this->add_control(
							'pltabs_active_text_color_h',
							[
								'label' => __( 'Active text color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a:hover' => 'color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_border_color_h',
							[
								'label' => __( 'Border color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a:hover' => 'border-color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_border_h_active',
							[
								'label' => __( 'Active border color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a:hover' => 'border-color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_bg_color_h',
							[
								'label' => __( 'Background', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a:hover' => 'background-color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_active_color_h',
							[
								'label' => __( 'Active background', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a:hover' => 'background-color: {{VALUE}}',
								],

							]
						);


					$this->end_controls_tab();

				$this->end_controls_tabs();

			$this->end_controls_section();

			$this->start_controls_section(
				'primary_btn',
				[
					'label' => __( 'Primary button', 'voxel-elementor' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

				$this->start_controls_tabs(
					'primary_btn_tabs'
				);

					/* Normal tab */

					$this->start_controls_tab(
						'primary_btn_normal',
						[
							'label' => __( 'Normal', 'voxel-elementor' ),
						]
					);



						$this->add_group_control(
							\Elementor\Group_Control_Typography::get_type(),
							[
								'name' => 'primary_btn_typo',
								'label' => __( 'Button typography', 'voxel-elementor' ),
								'selector' => '{{WRAPPER}} .ts-btn-2',
							]
						);


						$this->add_responsive_control(
							'primary_btn_radius',
							[
								'label' => __( 'Border radius', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px', '%' ],
								'range' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									],
									'%' => [
										'min' => 0,
										'max' => 100,
									],
								],
								'default' => [
									'unit' => 'px',
									'size' => 5,
								],
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2' => 'border-radius: {{SIZE}}{{UNIT}};',
								],
							]
						);

						$this->add_responsive_control(
							'primary_btn_c',
							[
								'label' => __( 'Text color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2' => 'color: {{VALUE}}',
								],

							]
						);

						$this->add_responsive_control(
							'primary_btn_padding',
							[
								'label' => __( 'Padding', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', '%', 'em' ],
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);

						$this->add_responsive_control(
							'primary_btn_height',
							[
								'label' => __( 'Height', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px' ],
								'range' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									],
								],
								'selectors' => [
									'{{WRAPPER}}  .ts-btn-2' => 'height: {{SIZE}}{{UNIT}};',
								],
							]
						);


						$this->add_responsive_control(
							'primary_btn_bg',
							[
								'label' => __( 'Background color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2' => 'background: {{VALUE}}',
								],

							]
						);

						$this->add_group_control(
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'primary_btn_border',
								'label' => __( 'Border', 'voxel-elementor' ),
								'selector' => '{{WRAPPER}} .ts-btn-2',
							]
						);


						$this->add_responsive_control(
							'primary_btn_icon_size',
							[
								'label' => __( 'Icon size', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px', '%' ],
								'range' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									],
									'%' => [
										'min' => 0,
										'max' => 100,
									],
								],
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2 i' => 'font-size: {{SIZE}}{{UNIT}};',
									'{{WRAPPER}} .ts-btn-2 svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
								],
							]
						);

						$this->add_responsive_control(
							'primary_btn_icon_pad',
							[
								'label' => __( 'Text/Icon spacing', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px' ],
								'range' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									],
								],
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2' => 'grid-gap: {{SIZE}}{{UNIT}};padding-right: 0px;',
								],
							]
						);

						$this->add_responsive_control(
							'primary_btn_icon_color',
							[
								'label' => __( 'Icon color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2 i' => 'color: {{VALUE}}',
									'{{WRAPPER}} .ts-btn-2 svg' => 'fill: {{VALUE}}',
								],

							]
						);
					$this->end_controls_tab();
					/* Hover tab */

					$this->start_controls_tab(
						'primary_btn_hover',
						[
							'label' => __( 'Hover', 'voxel-elementor' ),
						]
					);

						$this->add_responsive_control(
							'primary_btn_c_h',
							[
								'label' => __( 'Text color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2:hover' => 'color: {{VALUE}}',
								],

							]
						);

						$this->add_responsive_control(
							'primary_btn_bg_h',
							[
								'label' => __( 'Background color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2:hover' => 'background: {{VALUE}}',
								],

							]
						);

						$this->add_responsive_control(
							'primary_btn_border_h',
							[
								'label' => __( 'Border color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2:hover' => 'border-color: {{VALUE}}',
								],

							]
						);

						$this->add_responsive_control(
							'primary_btn_icon_color_h',
							[
								'label' => __( 'Icon color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2:hover i' => 'color: {{VALUE}}',
								],

							]
						);



					$this->end_controls_tab();

				$this->end_controls_tabs();

			$this->end_controls_section();

			$this->start_controls_section(
				'scnd_btn',
				[
					'label' => __( 'Secondary button', 'voxel-elementor' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

				$this->start_controls_tabs(
					'scnd_btn_tabs'
				);

					/* Normal tab */

					$this->start_controls_tab(
						'scnd_btn_normal',
						[
							'label' => __( 'Normal', 'voxel-elementor' ),
						]
					);



						$this->add_group_control(
							\Elementor\Group_Control_Typography::get_type(),
							[
								'name' => 'scnd_btn_typo',
								'label' => __( 'Button typography', 'voxel-elementor' ),
								'selector' => '{{WRAPPER}} .ts-btn-1',
							]
						);


						$this->add_responsive_control(
							'scnd_btn_radius',
							[
								'label' => __( 'Border radius', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px', '%' ],
								'range' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									],
									'%' => [
										'min' => 0,
										'max' => 100,
									],
								],
								'default' => [
									'unit' => 'px',
									'size' => 5,
								],
								'selectors' => [
									'{{WRAPPER}} .ts-btn-1' => 'border-radius: {{SIZE}}{{UNIT}};',
								],
							]
						);

						$this->add_responsive_control(
							'scnd_btn_c',
							[
								'label' => __( 'Text color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-1' => 'color: {{VALUE}}',
								],

							]
						);

						$this->add_responsive_control(
							'scnd_btn_padding',
							[
								'label' => __( 'Padding', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', '%', 'em' ],
								'selectors' => [
									'{{WRAPPER}} .ts-btn-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);

						$this->add_responsive_control(
							'scnd_btn_height',
							[
								'label' => __( 'Height', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px' ],
								'range' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									],
								],
								'selectors' => [
									'{{WRAPPER}}  .ts-btn-1' => 'height: {{SIZE}}{{UNIT}};',
								],
							]
						);


						$this->add_responsive_control(
							'scnd_btn_bg',
							[
								'label' => __( 'Background color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-1' => 'background: {{VALUE}}',
								],

							]
						);

						$this->add_group_control(
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'scnd_btn_border',
								'label' => __( 'Border', 'voxel-elementor' ),
								'selector' => '{{WRAPPER}} .ts-btn-1',
							]
						);


						$this->add_responsive_control(
							'scnd_btn_icon_size',
							[
								'label' => __( 'Icon size', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px', '%' ],
								'range' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									],
									'%' => [
										'min' => 0,
										'max' => 100,
									],
								],
								'selectors' => [
									'{{WRAPPER}} .ts-btn-1 i' => 'font-size: {{SIZE}}{{UNIT}};',
									'{{WRAPPER}} .ts-btn-1 svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
								],
							]
						);

						$this->add_responsive_control(
							'scnd_btn_icon_pad',
							[
								'label' => __( 'Text/Icon spacing', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px' ],
								'range' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									],
								],
								'selectors' => [
									'{{WRAPPER}} .ts-btn-1' => 'grid-gap: {{SIZE}}{{UNIT}};',
								],
							]
						);

						$this->add_responsive_control(
							'scnd_btn_icon_color',
							[
								'label' => __( 'Icon color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-1 i' => 'color: {{VALUE}}',
									'{{WRAPPER}} .ts-btn-1 svg' => 'fill: {{VALUE}}',
								],

							]
						);
					$this->end_controls_tab();
					/* Hover tab */

					$this->start_controls_tab(
						'scnd_btn_hover',
						[
							'label' => __( 'Hover', 'voxel-elementor' ),
						]
					);

						$this->add_responsive_control(
							'scnd_btn_c_h',
							[
								'label' => __( 'Text color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-1:hover' => 'color: {{VALUE}}',
								],

							]
						);

						$this->add_responsive_control(
							'scnd_btn_bg_h',
							[
								'label' => __( 'Background color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-1:hover' => 'background: {{VALUE}}',
								],

							]
						);

						$this->add_responsive_control(
							'scnd_btn_border_h',
							[
								'label' => __( 'Border color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-1:hover' => 'border-color: {{VALUE}}',
								],

							]
						);

						$this->add_responsive_control(
							'scnd_btn_icon_color_h',
							[
								'label' => __( 'Icon color', 'voxel-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-1:hover i' => 'color: {{VALUE}}',
								],

							]
						);



					$this->end_controls_tab();

				$this->end_controls_tabs();

			$this->end_controls_section();

			$this->start_controls_section(
				'ts_ui_icons',
				[
					'label' => __( 'Icons', 'voxel-elementor' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

				$this->add_control(
					'plan_list_icon',
					[
						'label' => __( 'Feature icon', 'text-domain' ),
						'type' => \Elementor\Controls_Manager::ICONS,
					]
				);

				$this->add_control(
					'ts_arrow_right',
					[
						'label' => __( 'Right arrow', 'text-domain' ),
						'type' => \Elementor\Controls_Manager::ICONS,
					]
				);


			$this->end_controls_section();
		foreach ( $plans as $plan ) {
			$key = sprintf( 'ts_plan:%s', $plan->get_key() );

			$this->start_controls_section( $key.':section', [
				'label' => sprintf( __( 'Plan: %s', 'voxel-elementor' ), $plan->get_label() ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			] );

			$this->add_control( $key.':image', [
				'label' => __( 'Choose image', 'voxel-elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
			] );

			$repeater = new \Elementor\Repeater;
			$repeater->add_control( 'text', [
				'label' => __( 'Text', 'voxel-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			] );


			$this->add_control( $key.':features', [
				'label' => __( 'Features', 'voxel-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			] );

			$this->end_controls_section();
		}
	}

	protected function render( $instance = [] ) {
		$groups = $this->get_settings_for_display( 'ts_price_groups' );
		$prices = [];
		foreach ( $groups as $group ) {
			if ( ! is_array( $group['prices'] ) || empty( $group['prices'] ) ) {
				continue;
			}

			foreach ( $group['prices'] as $price_key ) {
				$price_id = substr( strrchr( $price_key, '@' ), 1 );
				$plan_key = str_replace( '@'.$price_id, '', $price_key );
				$mode = substr( $price_id, 0, 5 ) === 'test:' ? 'test' : 'live';
				$price_id = str_replace( 'test:', '', $price_id );

				$plan = \Voxel\Membership\Plan::get( $plan_key );
				if ( ! $plan ) {
					continue;
				}

				if ( $plan->get_key() === 'default' ) {
					$price = [
						'type' => 'one_time',
						'currency' => \Voxel\get( 'settings.stripe.currency', 'USD' ),
						'amount' => 0,
						'active' => true,
					];
				} else {
					$pricing = $plan->get_pricing();
					if ( empty( $pricing[ $mode ] ) || empty( $pricing[ $mode ]['prices'][ $price_id ] ) ) {
						continue;
					}

					$price = $pricing[ $mode ]['prices'][ $price_id ];
					if ( ! $price['active'] ) {
						continue;
					}
				}

				if ( $this->get_settings_for_display( sprintf( 'ts_plan:%s:image', $plan->get_key() ) ) ) {
					$image = \Elementor\Group_Control_Image_Size::get_attachment_image_html(
						$this->get_settings_for_display(),
						'thumbnail',
						sprintf( 'ts_plan:%s:image', $plan->get_key() )
					);
				} else {
					$image = '';
				}

				$prices[] = [
					'price_id' => $price_id,
					'key' => $price_key,
					'group' => $group['_id'],
					'label' => $plan->get_label(),
					'is_free' => floatval( $price['amount'] ) === 0.0,
					'amount' => \Voxel\currency_format( $price['amount'], strtoupper( $price['currency'] ) ),
					'period' => \Voxel\Membership\Plan::get_price_period( $price ),
					'image' => $image,
					'features' => $this->get_settings_for_display( sprintf( 'ts_plan:%s:features', $plan->get_key() ) ),
					'link' => add_query_arg( [
						'action' => 'plans.choose_plan',
						'plan' => $price_key,
						'redirect_to' => $_GET['redirect_to'] ?? null,
						'_wpnonce' => wp_create_nonce( 'vx_choose_plan' ),
					], home_url('/?vx=1') ),
				];
			}
		}

		if ( empty( $prices ) ) {
			return;
		}

		$default_group = $groups[0]['_id'];
		$allow_autoselect = is_user_logged_in() && ! metadata_exists( 'user', get_current_user_id(), \Voxel\Stripe::is_test_mode() ? 'voxel:test_plan' : 'voxel:plan' );

		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/pricing-plan.php' );
	}

	public function get_style_depends() {
		return [ 'vx:pricing-plan.css' ];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
