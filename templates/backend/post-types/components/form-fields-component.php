<?php
/**
 * Post fields - component template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="post-type-fields-template">
	<div class="ts-tab-content ts-container">
		<div class="ts-row h-center wrap-row">
			<div class="ts-col-3-4">
				<div class="ts-tab-heading">
					<h1>Fields</h1>
					<p>Post type fields are shown in the post submission form. </p>
				</div>
				<div class="inner-tab fields-layout">
					
					<div class="used-fields">
						<div class="sub-heading">
							<p>Used fields</p>
						</div>
						<div class="field-container" ref="fields-container">
							<field-list-item
								:field="$root.config.fields[0]"
								:show-delete="false"
								@click:edit="toggleActive( $root.config.fields[0] )"
								@click:delete="deleteField( $root.config.fields[0] )"
							></field-list-item>

							<draggable
								v-model="$root.config.fields"
								group="fields"
								handle=".field-head"
								item-key="key"
								@start="dragStart"
								@end="dragEnd"
							>
								<template #item="{element: field, index: index}">
									<field-list-item
										v-if="index !== 0"
										:field="field"
										:show-delete="true"
										@click:edit="toggleActive(field)"
										@click:delete="deleteField(field)"
									></field-list-item>
								</template>
							</draggable>
						</div>
					</div>
					<div class="ts-relative">
						<div class="available-fields-container">
							<div class="sub-heading">
								<p>Insert preset field</p>
							</div>
							<div class="field-container available-fields ts-row wrap-row">
								<template v-for="preset in field_presets">
									<div class="single-field ts-col-1-2" :class="{'vx-disabled': !canAddPreset(preset)}">
										<div @click.prevent="addField(preset)" class="field-head">
											<p class="field-name">{{ preset.label }}</p>
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

							<div class="sub-heading">
								<p>Insert a custom field</p>
							</div>
							<!-- <ul class="inner-tabs">
								<li class="current-item"><a href="#">All</a></li>
								<li><a href="#">Input</a></li>
								<li><a href="#">Choice</a></li>
							</ul> -->
							<div class="field-container available-fields ts-row wrap-row">
								<template v-for="field_type in field_types">
									<div v-if="!field_type.singular" class="single-field ts-col-1-2">
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
		</div>
		
	</div>

	<field-modal v-if="active" :field="active"></field-modal>
</script>

<script type="text/html" id="post-type-field-list-item">
	<div class="single-field wide" :class="{'ts-form-step': field.type === 'ui-step'}">
		<div class="field-head" @click="$emit('click:edit')">
			<div v-if="field.type === 'ui-step'" class="field-actions left-actions">
				<span class="field-action all-center">
					<a href="#" @click.prevent><i class="las la-angle-up"></i></a>
				</span>
			</div>
			<p class="field-name">{{ field.label }}</p>
			<span class="field-type">{{ field.type }}</span>
			<div class="field-actions">
				<span class="field-action all-center" v-if="field['enable-conditions']">
					<a href="#" @click.prevent="$emit('click:edit')" title="Conditional logic is enabled for this field">
						<i class="las la-code-branch icon-sm"></i>
					</a>
				</span>
				<span class="field-action all-center" v-if="showDelete" @click.stop.prevent="$emit('click:delete')">

						<i class="lar la-trash-alt icon-sm"></i>

				</span>
			</div>
		</div>
	</div>
</script>
