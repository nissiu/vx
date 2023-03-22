<?php
/**
 * Repeater fields - component template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="post-type-repeater-fields-template">
	<div class="ts-repeater-fields">
		<div class="ts-row">
			<div class="used-fields ts-col-2-3">
				<div class="sub-heading">
					<p>Used fields</p>
				</div>
				<div class="field-container" ref="fields-container">
					<draggable
						v-model="field.fields"
						group="repeater-fields"
						handle=".field-head"
						item-key="key"
						@start="dragStart"
						@end="dragEnd"
					>
						<template #item="{element: subfield, index: index}">
							<div class="single-field wide" :class="{'ts-form-step': subfield.type === 'ui-step', open: active === subfield}">
								<div class="field-head" @click="toggleActive(subfield)">
									<div v-if="subfield.type === 'ui-step'" class="field-actions left-actions">
										<span class="field-action all-center">
											<a href="#" @click.prevent><i class="las la-angle-up"></i></a>
										</span>
									</div>
									<p class="field-name">{{ subfield.label }}</p>
									<span class="field-type">{{ subfield.type }}</span>
									<div class="field-actions">
										<span class="field-action all-center">
											<a href="#" @click.stop.prevent="deleteField(subfield)">
												<i class="lar la-trash-alt icon-sm"></i>
											</a>
										</span>
									</div>
								</div>
								<div v-if="active === subfield" class="field-body">
									<div class="ts-row wrap-row">
										<field-props :field="subfield" :repeater="field"></field-props>
									</div>
								</div>
							</div>
						</template>
					</draggable>
				</div>
			</div>
			<div class="ts-relative ts-col-1-3">
				<div class="available-fields-container">
					<div class="sub-heading">
						<p>Add a field</p>
					</div>
					<div class="field-container available-fields ts-row wrap-row">
						<template v-for="field_type in field_types">
							<div v-if="!field_type.singular && $root.options.repeatable[field_type.type]" class="single-field ts-col-1-2">
								<div @click.prevent="addField(field_type)" class="field-head">
									<p class="field-name">{{ field_type.label }}</p>
									<div class="field-actions">
										<span class="field-action all-center">
											<a href="#">
												<i class="las la-plus"></i>
											</a>
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
</script>
