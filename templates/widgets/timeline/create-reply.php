<script type="text/html" id="timeline-create-reply">
	<form-group
		:popup-key="popupKey"
		ref="popup"
		:save-label="reply ? <?= esc_attr( wp_json_encode( _x( 'Update', 'timeline', 'voxel' ) ) ) ?> : <?= esc_attr( wp_json_encode( _x( 'Post comment', 'timeline', 'voxel' ) ) ) ?>"
		clear-label="<?= esc_attr( _x( 'Cancel', 'timeline', 'voxel' ) ) ?>"
		@save="publish"
		@clear="cancel"
		class="ts-form"
		:wrapper-class="pending ? 'reply-pending' : ''"
	>
		<template #trigger>
			<div v-if="showTrigger" class="ts-filter ts-popup-target" @mousedown="$root.activePopup = popupKey">
				<span v-html="$root.config.settings.ts_post_footer_comment_icon"></span>
				<div class="ts-filter-text"><?= _x( 'Post a comment', 'timeline', 'voxel' ) ?></div>
			</div>
		</template>
		<template #popup>
			<div class="ts-popup-head flexify ts-popup-sticky">
				<div class="ts-popup-name flexify">
					<?php if ( is_user_logged_in() ): ?>
						<?= \Voxel\current_user()->get_avatar_markup() ?>
						<p><?= \Voxel\current_user()->get_display_name() ?></p>
					<?php endif ?>
				</div>
			</div>
			<div class="ts-compose-textarea">
				<textarea
					v-model="message"
					placeholder="<?= esc_attr( _x( 'Your comment', 'timeline', 'voxel' ) ) ?>"
					class="autofocus min-scroll"
					:maxlength="$root.config.replySubmission.maxlength"
				></textarea>
			</div>
		</template>
	</form-group>
</script>
