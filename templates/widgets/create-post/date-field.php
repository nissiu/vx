<script type="text/html" id="create-post-date-field">
	<form-group
		:popup-key="field.id+':'+index"
		ref="formGroup"
		@save="onSave"
		@blur="saveValue"
		@clear="onClear"
		wrapper-class="prmr-popup"
	>
		<template #trigger>
			<template v-if="field.props.enable_timepicker">
				<label>
					{{ field.label }}
					<small>{{ field.description }}</small>
				</label>
				<div class="ts-double-input flexify">
					<div class="ts-form-group">
						<div class="ts-filter ts-popup-target" :class="{'ts-filled': field.value.date !== null}" @mousedown="$root.activePopup = field.id+':'+index">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('calendar_icon') ) ?: \Voxel\svg( 'calendar.svg' ) ?>
							<div class="ts-filter-text">
								{{ displayValue || field.props.placeholder }}
							</div>
						</div>
					</div>
					<div class="ts-form-group">
						<input placeholder="Time" type="time" v-model="field.value.time" class="ts-filter">
					</div>
				</div>
			</template>
			<template v-else>
				<label>
					{{ field.label }}
					<small>{{ field.description }}</small>
				</label>
				<div class="ts-filter ts-popup-target" :class="{'ts-filled': field.value.date !== null}" @mousedown="$root.activePopup = field.id+':'+index">
			 		<?= \Voxel\get_icon_markup( $this->get_settings_for_display('calendar_icon') ) ?: \Voxel\svg( 'calendar.svg' ) ?>
					<div class="ts-filter-text">
						{{ displayValue || field.props.placeholder }}
					</div>
				</div>
			</template>
		</template>
		<template #popup>
			<date-picker ref="picker" :field="field" :parent="this"></date-picker>
		</template>
	</form-group>
</script>

<script type="text/html" id="create-post-date-field-picker">
	<div class="ts-form-group" ref="calendar">
		<input type="hidden" ref="input">
	</div>
</script>
