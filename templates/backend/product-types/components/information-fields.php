<script type="text/html" id="product-type-fields-template">
	<div class="ts-tab-content ts-container">
		<div class="ts-row wrap-row h-center">
			<div class="ts-col-3-4">
				<div class="ts-row">
					<div class="ts-tab-heading ts-col-1-1">
						<h1>Information fields</h1>
						<p>Information fields are used to gather information from the client. They don't affect the price</p>
					</div>
				</div>

				<div class="inner-tab fields-layout">
					
					<div class="used-fields">
						<div class="sub-heading">
							<p>Used fields</p>
						</div>
						<div class="field-container" ref="fields-container">
							<draggable
								v-model="$root.config.fields"
								group="fields"
								handle=".field-head"
								item-key="key"
								@start="dragStart"
								@end="dragEnd"
							>
								<template #item="{element: field}">
									<div class="single-field wide">
										<div class="field-head" @click="active = field">
											<p class="field-name">{{ field.label }}</p>
											<span class="field-type">{{ field.type }}</span>
											<div class="field-actions">
												<span class="field-action all-center">
													<a href="#" @click.stop.prevent="deleteField(field)">
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
						<div class="available-fields-container">
							<div class="sub-heading">
								<p>Available fields</p>
							</div>
							<!-- <ul class="inner-tabs">
								<li class="current-item"><a href="#">All</a></li>
								<li><a href="#">Input</a></li>
								<li><a href="#">Choice</a></li>
							</ul> -->
							<div class="field-container available-fields ts-row wrap-row">
								<template v-for="field_type in field_types">
									<div class="single-field ts-col-1-2">
										<div @click.prevent="addField(field_type)" class="field-head">
											<p class="field-name">{{ field_type.type }}</p>
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

<script type="text/html" id="product-type-field-modal-template">
	<teleport to="body">
		<div class="ts-field-modal ts-theme-options">
			<div class="modal-backdrop" @click="save"></div>
			<div class="modal-content min-scroll">
				<div class="modal-content-holder">
					<div class="field-modal-head">
						<h2>Field options</h2>
						<a href="#" @click.prevent="save" class="ts-button btn-shadow">
							<i class="las la-check icon-sm"></i>Done
						</a>
					</div>
					<!-- <div class="field-modal-tabs">
						<ul class="inner-tabs">
							<li class="current-item">
								<a href="#" @click.prevent>Edit field</a>
							</li>
						</ul>
					</div> -->
					<div class="field-modal-body">
						<div class="ts-row wrap-row">
							<?= $field_options_markup ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</teleport>
</script>
