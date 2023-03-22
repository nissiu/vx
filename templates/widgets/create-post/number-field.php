<script type="text/html" id="create-post-number-field">
	<div v-if="field.props.display === 'stepper'" class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<div class="ts-stepper-input flexify">
			<button class="ts-stepper-left ts-icon-btn" @click.prevent="decrement">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_minus_icon') ) ?: \Voxel\svg( 'minus.svg' ) ?>
			</button>
			<input
				v-model="field.value"
				type="number"
				class="ts-input-box"
				:min="field.props.min"
				:max="field.props.max"
				:step="field.props.step"
				placeholder="0"
			>
			<button class="ts-stepper-right ts-icon-btn" @click.prevent="increment">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_plus_icon') ) ?: \Voxel\svg( 'plus.svg' ) ?>
			</button>
		</div>
	</div>
	<div v-else class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<div class="input-container">
			<input
				v-model="field.value"
				:placeholder="field.props.placeholder"
				type="number"
				class="ts-filter"
			>
			<span v-if="field.props.suffix" class="input-suffix">{{ field.props.suffix }}</span>
		</div>
	</div>
</script>
