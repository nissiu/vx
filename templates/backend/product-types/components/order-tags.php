<?php
/**
 * Edit product type order tag.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="product-type-tags-template">

	<div class="ts-form-group">
		<label>Order tag can be applied by:</label>
		<select v-model="$root.config.settings.tags.editable_by">
			<option value="vendor">Vendor</option>
			<option value="customer">Customer</option>
			<option value="both">Vendor & customer</option>
		</select>
	</div>

	<div class="ts-form-group">
		<label>QR codes for tagging an order can be used:</label>
		<select v-model="$root.config.settings.tags.qr_limit">
			<option value="once">One time</option>
			<option value="unlimited">Unlimited times</option>
		</select>
	</div>

	<div class="ts-tab-subheading">
		<h3>List of tags</h3>
	</div>

	<div class="field-container" ref="fields-container">
		<draggable
			v-model="$root.config.tags"
			group="tags"
			handle=".field-head"
			item-key="key"
			@start="dragStart"
			@end="dragEnd"
		>
			<template #item="{element: tag}">
				<div class="single-field wide">
					<div class="field-head" @click="toggleActive(tag)">
						<p class="field-name">{{ tag.label }}</p>
						<span class="field-type">{{ tag.key }}</span>
						<div class="field-actions">
							<span v-if="tag.is_default" title="Default tag" class="field-action all-center">
								<i class="las la-bookmark icon-sm"></i>
							</span>
							<span v-if="tag.has_qr_code" title="QR code enabled" class="field-action all-center">
								<i class="las la-qrcode icon-sm"></i>
							</span>
							<span class="field-action all-center">
								<a href="#" @click.stop.prevent="deleteTag(tag)">
									<i class="lar la-trash-alt icon-sm"></i>
								</a>
							</span>
						</div>
					</div>
				</div>
			</template>
		</draggable>

		<a href="#" @click.prevent="insertTag" class="ts-button ts-outline">
			<i class="las la-plus icon-sm"></i>
			Create order tag
		</a>
	</div>
	<tag-modal v-if="active" :tag="active"></tag-modal>
</script>

<script type="text/html" id="product-type-tags-modal-template">
	<teleport to="body">
		<div class="ts-field-modal ts-theme-options">

			<div class="modal-backdrop" @click="save"></div>
			<div class="modal-content min-scroll">
				<div class="modal-content-holder">
					<div class="field-modal-head">
						<h2>Order tag options</h2>
						<a href="#" @click.prevent="save" class="ts-button btn-shadow"><i class="las la-check icon-sm"></i>Done</a>
					</div>
					<div class="field-modal-tabs">
						<ul class="inner-tabs">
							<li class="current-item">
								<a href="#" @click.prevent>Tag details</a>
							</li>
						</ul>
					</div>
					<div class="field-modal-body">
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Key_Model::render( [
								'label' => 'Key',
								'v-model' => 'tag.key',
								'ref' => 'keyInput'
							] ) ?>

							<?php \Voxel\Form_Models\Text_Model::render( [
								'label' => 'Label',
								'v-model' => 'tag.label',
							] ) ?>

							<?php \Voxel\Form_Models\Color_Model::render( [
								'label' => 'Primary color',
								'v-model' => 'tag.primary_color',
								'width' => '1/2',
							] ) ?>

							<?php \Voxel\Form_Models\Color_Model::render( [
								'label' => 'Seconday color',
								'v-model' => 'tag.secondary_color',
								'width' => '1/2',
							] ) ?>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'label' => 'Use as default tag?',
								'description' => 'This tag will be applied automatically to completed orders.',
								'v-model' => 'tag.is_default',
							] ) ?>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'label' => 'Can be applied with QR code?',
								'description' => 'If enabled, a QR code will be generated for this tag which can be used to apply it to the order.',
								'v-model' => 'tag.has_qr_code',
							] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</teleport>
</script>
