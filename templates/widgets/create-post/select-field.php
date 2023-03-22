<script type="text/html" id="create-post-select-field">
	<form-group wrapper-class="prmr-popup" :popup-key="field.id+':'+index" ref="formGroup" @save="onSave" @blur="saveValue" @clear="onClear">
		<template #trigger>
			<label>
				{{ field.label }}
				<small>{{ field.description }}</small>
			</label>
			<div class="ts-filter ts-popup-target" :class="{'ts-filled': field.value !== null}" @mousedown="$root.activePopup = field.id+':'+index">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_list_icon') ) ?: \Voxel\svg( 'list.svg' ) ?>
				<div class="ts-filter-text">
					<span>{{ field.props.choices[ field.value ] ? field.props.choices[ field.value ].label : field.props.placeholder }}</span>
				</div>
			</div>
		</template>
		<template #popup>
			<div class="ts-term-dropdown ts-md-group ts-multilevel-dropdown">
				<ul class="simplify-ul ts-term-dropdown-list min-scroll">
					<li v-for="choice in field.props.choices">
						<a href="#" class="flexify" @click.prevent="value = choice.value; onSave();">
							<div class="ts-radio-container">
								<label class="container-radio">
									<input
										type="radio"
										:value="choice.value"
										:checked="value === choice.value"
										disabled
										hidden
									>
									<span class="checkmark"></span>
								</label>
							</div>
							<p>{{ choice.label }}</p>
							<div class="ts-term-icon">
								<span v-html="choice.icon || field.props.default_icon"></span>
							</div>
						</a>
					</li>
				</ul>
			</div>
		</template>
	</form-group>
</script>
