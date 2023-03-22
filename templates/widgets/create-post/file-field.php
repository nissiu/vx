<script type="text/html" id="create-post-file-field">
	<div class="ts-form-group ts-file-upload inline-file-field" @dragenter="dragActive = true">
		<div class="drop-mask" v-show="dragActive" @dragleave.prevent="dragActive = false" @drop.prevent="onDrop" @dragenter.prevent @dragover.prevent></div>
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<div class="ts-file-list" ref="fileList" v-pre>
			<div class="pick-file-input">
				<a href="#">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_upload_ico') ) ?: \Voxel\svg( 'upload.svg' ) ?>
					<?= _x( 'Upload', 'file field', 'voxel' ) ?>
				</a>
			</div>
		</div>
		<media-popup v-if="showLibrary" @save="onMediaPopupSave" :multiple="field.props.maxCount > 1"></media-popup>
		<input
			ref="input"
			type="file"
			class="hidden"
			:multiple="field.props.maxCount > 1"
			:accept="accepts"
		>
	</div>
</script>
