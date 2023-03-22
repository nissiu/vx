<script type="text/html" id="timeline-create-status">
	<form-group
		:popup-key="popupKey"
		ref="popup"
		:save-label="status ? <?= esc_attr( wp_json_encode( _x( 'Update', 'timeline', 'voxel' ) ) ) ?> : <?= esc_attr( wp_json_encode( _x( 'Publish', 'timeline', 'voxel' ) ) ) ?>"
		clear-label="<?= esc_attr( _x( 'Cancel', 'timeline', 'voxel' ) ) ?>"
		prevent-blur=".ts-media-library"
		@save="publish"
		@clear="cancel"
		:wrapper-class="[pending ? 'status-pending' : '', 'prmr-popup'].join(' ')"
	>
		<template #trigger>
			<div v-if="!status" class="ts-filter ts-popup-target" @mousedown="$root.activePopup = popupKey">
				<slot></slot>
			</div>
		</template>
		<template #popup>
			<div class="ts-popup-head flexify ts-sticky-top">
				<div class="ts-popup-name flexify">
					<span v-html="$root.config.user.avatar"></span>
					<p>{{ $root.config.user.name }}</p>
				</div>
			</div>
			<div class="ts-compose-textarea">
				<textarea
					v-model="message"
					placeholder="<?= esc_attr( _x( "What's on your mind?", 'timeline', 'voxel' ) ) ?>"
					class="autofocus min-scroll"
					:maxlength="$root.config.postSubmission.maxlength"
				></textarea>
			</div>
			<div v-if="$root.mode === 'post_reviews'" class="ts-form-group ts-review-field">
				<label><?= _x( 'Your rating', 'timeline', 'voxel' ) ?></label>
				<ul class="simplify-ul flexify">
					<li v-for="level in $root.ratingLevels" :class="[rating === level.score && 'rating-selected', level.key]">
						<a href="#" @click.prevent="toggleRating(level)" class="flexify">
							<span v-html="level.icon"></span>
							<p>{{ level.label }}</p>
						</a>
					</li>
				</ul>
			</div>
			<field-file
				v-show="$root.config.postSubmission.gallery"
				:field="files"
				:sortable="false"
				ref="files"
				class="ts-status-files"
			></field-file>
			
		</template>
	</form-group>
</script>
