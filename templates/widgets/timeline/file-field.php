<script type="text/html" id="timeline-file-field">
<!-- 	<div class="ts-form-group">
		<a href="#" class="ts-btn ts-btn-4 create-btn">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_media_ico') ) ?: \Voxel\svg( 'gallery.svg' ) ?>
			<p><?= _x( 'Attach images', 'attach images', 'voxel' ) ?></p>
		</a>
	</div> -->
	<div class="ts-form-group ts-file-upload">

		<label>{{ field.label }}</label>
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
