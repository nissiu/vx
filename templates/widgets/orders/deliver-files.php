<script type="text/html" id="orders-deliver-files">
	<form-group
		popup-key="deliverFrom"
		ref="deliverFrom"
		class=""
		save-label="<?= esc_attr( _x( 'Deliver', 'single order', 'voxel' ) ) ?>"
		clear-label="<?= esc_attr( _x( 'Cancel', 'single order', 'voxel' ) ) ?>"
		prevent-blur=".ts-media-library"
		@save="submit"
		@clear="cancel"
		wrapper-class="prmr-popup"
		:default-class="false"
	>
		<template #trigger>
			<a href="#" @click.prevent class="ts-btn ts-btn-3 ts-btn-large ts-popup-target" @mousedown="$root.activePopup = 'deliverFrom'">
				<span><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_upload_ico') ) ?: \Voxel\svg( 'upload.svg' ) ?></span>
				<?= _x( 'Deliver files', 'single order', 'voxel' ) ?>
			</a>
		</template>
		<template #popup>
			<div class="ts-popup-head flexify ts-sticky-top">
			   <div class="ts-popup-name flexify">
				  <?= \Voxel\current_user()->get_avatar_markup() ?>
				  <p><?= \Voxel\current_user()->get_display_name() ?></p>
			   </div>
			</div>
			<div class="ts-compose-textarea">
				<textarea v-model="message" placeholder="<?= esc_attr( _x( 'Add description', 'single order', 'voxel' ) ) ?>" rows="3" class="autofocus min-scroll"></textarea>
			</div>
			<field-file
				:field="files"
				:sortable="false"
				:show-library="false"
				ref="files"
				class="ts-status-files"
			></field-file>
		</template>
	</form-group>
</script>
