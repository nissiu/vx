<script type="text/html" id="create-post-switcher-field">
	<div class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<div class="switch-slider">
			<div class="onoffswitch">
				<input v-model="field.value" :id="switcherId" type="checkbox" class="onoffswitch-checkbox">
				<label class="onoffswitch-label" :for="switcherId"></label>
			</div>
		</div>
	</div>
</script>
