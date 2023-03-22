<script type="text/html" id="create-post-email-field">
	<div class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<div class="ts-input-icon flexify">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_email_icon') ) ?: \Voxel\svg( 'envelope.svg' ) ?>
			<input
				v-model="field.value"
				:placeholder="field.props.placeholder"
				type="email"
				class="ts-filter"
			>
		</div>
	</div>
</script>
