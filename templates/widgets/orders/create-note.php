<script type="text/html" id="orders-create-note">
	<form-group
		popup-key="commentForm"
		ref="commentForm"
		class="ts-no-padding"
		save-label="<?= esc_attr( _x( 'Post Comment', 'single order', 'voxel' ) ) ?>"
		clear-label="<?= esc_attr( _x( 'Cancel', 'single order', 'voxel' ) ) ?>"
		prevent-blur=".ts-media-library"
		@save="postComment"
		@clear="cancelComment"
		wrapper-class="prmr-popup"
	>
		<template #trigger>
			<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = 'commentForm'">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_order_comment') ) ?: \Voxel\svg( 'comment.svg' ) ?>
				<div class="ts-filter-text"><?= _x( 'Post a comment', 'single order', 'voxel' ) ?></div>
			</div>
		</template>
		<template #popup>
			<div class="ts-popup-head flexify ts-sticky-top">
			   <div class="ts-popup-name flexify">
				  <?= \Voxel\current_user()->get_avatar_markup() ?>
				  <p><?= \Voxel\current_user()->get_display_name() ?></p>
			   </div>
			</div>
			<div class="ts-compose-textarea">
				<textarea v-model="message" placeholder="<?= esc_attr( _x( "What's on your mind?", 'single order', 'voxel' ) ) ?>" rows="3" class="autofocus min-scroll"></textarea>
			</div>
			<field-file
				:field="files"
				:sortable="false"
				ref="commentFiles"
				class="ts-status-files"
			></field-file>
		</template>
	</form-group>
</script>

<script type="text/html" id="orders-file-field">
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
		<media-popup v-if="showLibrary" @save="onMediaPopupSave"></media-popup>
		<input ref="input" type="file" class="hidden" :multiple="field.props.maxCount > 1" :accept="accepts">
	</div>
</script>
