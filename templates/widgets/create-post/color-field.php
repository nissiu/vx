<script type="text/html" id="create-post-color-field">
	<div class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<div class="ts-cp-con">
			<input v-model="field.value" :placeholder="field.props.placeholder" type="color" class="ts-color-picker">
			<input type="text" v-model="field.value" class="color-picker-input" :placeholder="field.props.placeholder">
		</div>
	</div>
</script>
