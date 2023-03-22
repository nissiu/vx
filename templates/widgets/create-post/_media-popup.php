<script type="text/html" id="create-post-media-popup">
	<a @click.prevent href="#" ref="popupTarget" @mousedown="openLibrary" class="ts-btn ts-btn-4 create-btn">
		<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_media_ico') ) ?: \Voxel\svg( 'gallery.svg' ) ?>
		<p><?= _x( 'Media library', 'media library', 'voxel' ) ?></p>
	</a>
	<teleport to="body">
		<transition name="form-popup">
			<form-popup
				ref="popup"
				v-if="active"
				class="ts-media-library prmr-popup"
				:target="customTarget || $refs.popupTarget"
				@blur="$emit('blur'); active = false; selected = {};"
				:save-label="saveLabel || <?= esc_attr( wp_json_encode( _x( 'Save', 'media library', 'voxel' ) ) ) ?>"
				@save="save"
				@clear="clear"
			>
				<div class="ts-form-group min-scroll ts-list-container">
					<div class="ts-file-list">
						<div
							v-for="file in files"
							class="ts-file"
							:style="getStyle(file)"
							:class="{selected: selected[ file.id ], 'ts-file-img': isImage(file)}"
							@click="selectFile(file)"
						>
							<div class="ts-file-info">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_upload_ico') ) ?: \Voxel\svg( 'upload.svg' ) ?><code>{{ file.name }}</code>
							</div>
							<div class="ts-remove-file ts-select-file">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_select_ico') ) ?: \Voxel\svg( 'checkmark.svg' ) ?>
							</div>
						</div>
					</div>

					<div v-if="!loading && !files.length" class="ts-form-group">
						<label><?= _x( 'You have no files in your media library.', 'media library', 'voxel' ) ?></label>
					</div>
					<div v-else>
						<a v-if="loading" href="#" class="ts-btn ts-btn-4 load-more-btn">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_load_ico') ) ?: \Voxel\svg( 'reload.svg' ) ?>
							<?= __( 'Loading', 'voxel' ) ?>
						</a>
						<a
							v-else-if="hasMore && !loading"
							@click.prevent="loadMore"
							href="#"
							class="ts-btn ts-btn-4"
						>	
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_load_ico') ) ?: \Voxel\svg( 'reload.svg' ) ?>
							<?= __( 'Load more', 'voxel' ) ?>
						</a>
					</div>
				</div>
			</form-popup>
		</transition>
	</teleport>
</script>
