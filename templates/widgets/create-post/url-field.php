<script type="text/html" id="create-post-url-field">
	<div class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<div class="ts-input-icon flexify">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_link_icon') ) ?: \Voxel\svg( 'link-alt.svg' ) ?>
			<input v-model="field.value" :placeholder="field.props.placeholder" type="url" class="ts-filter">
		</div>
	</div>
</script>
