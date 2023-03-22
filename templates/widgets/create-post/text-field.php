<script type="text/html" id="create-post-text-field">
	<div class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<div class="input-container">
			<input v-model="field.value" :placeholder="field.props.placeholder" type="text" class="ts-filter">
			<span v-if="field.props.suffix" class="input-suffix">{{ field.props.suffix }}</span>
		</div>
	</div>
</script>
