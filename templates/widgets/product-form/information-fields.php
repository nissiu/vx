<script type="text/html" id="product-form-text-field">
	<div class="ts-form-group">
		<label>{{ field.label }}<small>{{ field.description }}</small></label>
		<input v-model="field.value" :placeholder="field.props.placeholder" type="text" class="ts-filter">
	</div>
</script>

<script type="text/html" id="product-form-email-field">
	<div class="ts-form-group">
		<label>{{ field.label }}<small>{{ field.description }}</small></label>
		<input v-model="field.value" :placeholder="field.props.placeholder" type="email" class="ts-filter">
	</div>
</script>

<script type="text/html" id="product-form-number-field">
	<div class="ts-form-group">
		<label>{{ field.label }}<small>{{ field.description }}</small></label>
		<input v-model="field.value" :placeholder="field.props.placeholder" type="number" class="ts-filter">
	</div>
</script>

<script type="text/html" id="product-form-phone-field">
	<div class="ts-form-group">
		<label>{{ field.label }}<small>{{ field.description }}</small></label>
		<input v-model="field.value" :placeholder="field.props.placeholder" type="tel" class="ts-filter">
	</div>
</script>

<script type="text/html" id="product-form-url-field">
	<div class="ts-form-group">
		<label>{{ field.label }}<small>{{ field.description }}</small></label>
		<input v-model="field.value" :placeholder="field.props.placeholder" type="url" class="ts-filter">
	</div>
</script>

<script type="text/html" id="product-form-textarea-field">
	<div class="ts-form-group">
		<label>{{ field.label }}<small>{{ field.description }}</small></label>
		<textarea v-model="field.value" :placeholder="field.props.placeholder" class="ts-filter min-scroll"></textarea>
	</div>
</script>

<script type="text/html" id="product-form-switcher-field">
	<div class="ts-form-group">
		<label>{{ field.label }}<small>{{ field.description }}</small></label>
		<div class="switch-slider">
			<div class="onoffswitch">
			    <input v-model="field.value" :id="'_switch-'+field.key" type="checkbox" class="onoffswitch-checkbox">
			    <label class="onoffswitch-label" :for="'_switch-'+field.key"></label>
			</div>
		</div>
	</div>
</script>

<script type="text/html" id="product-form-file-field">
	<div class="ts-form-group ts-file-upload inline-file-field">
		<label>{{ field.label }}<small>{{ field.description }}</small></label>
		<div class="ts-file-list" ref="fileList" v-pre>
			<div class="pick-file-input">
				<a href="#">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_upload_ico') ) ?: \Voxel\svg( 'upload.svg' ) ?>
					<?= _x( 'Upload', 'file field', 'voxel' ) ?>
				</a>
			</div>
		</div>
		<media-popup @save="onMediaPopupSave"></media-popup>
		<input ref="input" type="file" class="hidden" :multiple="field.props.maxCount > 1" :accept="accepts">
	</div>
</script>
