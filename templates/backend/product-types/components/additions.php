<?php
/**
 * Edit product type additions in WP Admin.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="product-type-additions-template">
	<div class="ts-tab-content ts-container">
		<div class="ts-row wrap-row h-center">
			<div class="ts-col-3-4">
				<div class="ts-row">
					<div class="ts-tab-heading ts-col-1-1">
						<h1>Additions</h1>
						<p>Product additions can affect the price of the product. This area is used to setup pre-defined additions. To allow vendor to add custom additions, use Repeater field on your post type.
						</p>
					</div>
				</div>
				<div class="inner-tab fields-layout">
					
					<div class="used-fields">
						<div class="sub-heading">
							<p>Used additions</p>
						</div>
						<div class="field-container" ref="fields-container">
							<draggable
								v-model="$root.config.additions"
								group="additions"
								handle=".field-head"
								item-key="key"
								@start="dragStart"
								@end="dragEnd"
							>
								<template #item="{element: addition}">
									<div class="single-field wide">
										<div class="field-head" @click="toggleActive(addition)">
											<p class="field-name">{{ addition.label }}</p>
											<span class="field-type">{{ addition.type }}</span>
											<div class="field-actions">
												<span class="field-action all-center">
													<a href="#" @click.stop.prevent="deleteAddition(addition)">
														<i class="lar la-trash-alt icon-sm"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
								</template>
							</draggable>
						</div>
					</div>
					<div class="ts-relative">
						<div class="sub-heading">
							<p>Available additions</p>
						</div>
						<div class="available-fields-container">
							<div class="field-container available-fields ts-row wrap-row">
								<template v-for="addition_type in $root.options.addition_types">
									<div class="single-field ts-col-1-2">
										<div @click.prevent="insertAddition(addition_type)" class="field-head">
											<p class="field-name">{{ addition_type.type }}</p>
											<div class="field-actions">
												<span class="field-action all-center">
													<a href="#"><i class="las la-plus"></i></a>
												</span>
											</div>
										</div>
									</div>
								</template>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<addition-modal v-if="active" :addition="active"></addition-modal>
</script>

<script type="text/html" id="product-type-addition-modal-template">
	<teleport to="body">
		<div class="ts-field-modal ts-theme-options">

			<div class="modal-backdrop" @click="save"></div>
			<div class="modal-content min-scroll">
				<div class="modal-content-holder">
					<div class="field-modal-head">
						<h2>Addition options</h2>
						<a href="#" @click.prevent="save" class="ts-button btn-shadow"><i class="las la-check icon-sm"></i>Done</a>
					</div>
					<!-- <div class="field-modal-tabs">
						<ul class="inner-tabs">
							<li class="current-item">
								<a href="#" @click.prevent>Addition details</a>
							</li>
						</ul>
					</div> -->
					<div class="field-modal-body">
						<div class="ts-row wrap-row">
							<?= $addition_options_markup ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</teleport>
</script>
