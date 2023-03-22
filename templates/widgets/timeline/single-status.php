<script type="text/html" id="timeline-single-status">
	<div class="ts-single-status">
		<template v-if="status.publisher.exists">
			<a :href="status.publisher.link" v-html="status.publisher.avatar"></a>
		</template>
		<template v-else>
			<a :href="status.user.link" v-html="status.user.avatar"></a>
		</template>
		<div :key="status.key" class="ts-status" :class="{'vx-pending': status._pending}">
			<template v-if="status.publisher.exists">
				<div class="ts-status-head flexify ts-parent">
					<a :href="status.publisher.link" class="ts_status-author">{{ status.publisher.name }}</a>
					<div>
						<span><?= _x( 'Posted an update', 'timeline', 'voxel' ) ?></span>
						<a :href="status.link">
							<span class="ts-status-time">{{ status.time }}</span>
						</a>
						<span v-if="status.edit_time" :title="'Edited on '+status.edit_time"><?= _x( '(edited)', 'timeline', 'voxel' ) ?></span>
					</div>
				</div>
			</template>

			<template v-else>
				<div  class="ts-status-head flexify ts-parent">
					<a :href="status.user.link" class="ts_status-author">{{ status.user.name }}</a>
					<div>
						<template v-if="status.post.exists && !status.post.is_profile">
							<span v-if="status.is_review"><?= _x( 'Reviewed', 'timeline', 'voxel' ) ?></span>
							<span v-else><?= _x( 'Posted on', 'timeline', 'voxel' ) ?></span>
							<a :href="status.post.link" class="ts-post-link">{{ status.post.title }}</a>
						</template>
						<template v-else>
						    <span><?= _x( 'Posted an update', 'timeline', 'voxel' ) ?></span>
						</template>
						<a :href="status.link">
							{{ status.time }}
						</a>
						<span v-if="status.edit_time" :title="'Edited on '+status.edit_time"><?= _x( '(edited)', 'timeline', 'voxel' ) ?></span>
					</div>
				</div>
			</template>

			<div class="ts-status-body ts-parent">
				<review-score v-if="status.review_score !== null" :score="status.review_score"></review-score>
				<template v-if="status.content">
					<p v-html="!truncated.exists || status._expanded ? status.content : truncated.content"></p>
					<span v-if="truncated.exists" @click.prevent="status._expanded = !status._expanded" class="ts-content-toggle">
						<template v-if="status._expanded">
							<?= _x( 'Read less &#9652;', 'timeline', 'voxel' ) ?>
						</template>
						<template v-else>
							<?= _x( 'Read more &#9662;', 'timeline', 'voxel' ) ?>
						</template>
					</span>
				</template>
				<ul v-if="status.files" class="ts-status-gallery simplify-ul flexify">
					<li v-for="file in status.files">
						<a :href="file.url" data-elementor-open-lightbox="yes" :data-elementor-lightbox-slideshow="status.files.length > 1 ? status.key : null">
							<img :src="file.preview" :alt="file.alt">
						</a>
					</li>
				</ul>
			</div>
			<div class="ts-status-footer ts-parent">
				<ul class="simplify-ul flexify">
					<li>
						<a href="#" @click.prevent="likeStatus" :class="{'ts-liked': status.liked_by_user}" ref="likeBtn" class="ts-like">
							<div class="ray-holder">
								<div class="ray"></div>
								<div class="ray"></div>
								<div class="ray"></div>
								<div class="ray"></div>
								<div class="ray"></div>
								<div class="ray"></div>
								<div class="ray"></div>
								<div class="ray"></div>
							</div>
							<template v-if="status.liked_by_user">
								<span v-html="$root.config.settings.ts_post_footer_liked_icon"></span>
							</template>
							<template v-else>
								<span v-html="$root.config.settings.ts_post_footer_like_icon"></span>
							</template>
						</a>
						<span class="ts-item-count" v-if="status.like_count">{{ status.like_count }}</span>
					</li>
					<li>
						<a href="#" @click.prevent="status.replies.visible = !status.replies.visible" class="">
							<span v-html="$root.config.settings.ts_post_footer_comment_icon"></span>

						</a>
						<span class="ts-item-count" v-if="status.reply_count">{{ status.reply_count }}</span>
					</li>
					<li class="tl-more" v-if="status.user_can_edit || status.user_can_moderate">
						<form-group
							:popup-key="'mod-status-'+status.id"
							:default-class="false"
							:show-save="false"
							:show-clear="false"
						>
							<template #trigger>
								<a href="#" @click.prevent="$root.activePopup = 'mod-status-'+status.id" class="">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_more_icon') ) ?: \Voxel\svg( 'more-alt.svg' ) ?>
								</a>
							</template>
							<template #popup>
								<div class="ts-popup-head flexify hide-d">
									<div class="ts-popup-name flexify">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_more_icon') ) ?: \Voxel\svg( 'more-alt.svg' ) ?>
										<p>More</p>
									</div>
									<ul class="flexify simplify-ul">
										<li class="flexify ts-popup-close">
											<a @click.prevent="$root.activePopup = null" href="#" class="ts-icon-btn">
												<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_close_ico') ) ?: \Voxel\svg( 'close.svg' ) ?>
											</a>
										</li>
									</ul>
								</div>
								<div class="ts-term-dropdown ts-md-group">
									<ul class="simplify-ul ts-term-dropdown-list min-scroll">
										<li v-if="status.user_can_edit && $root.config.postSubmission.editable">
											<a href="#" class="flexify" @click.prevent="$root.activePopup = 'create-status-'+status.id">
												<div class="ts-term-icon">
													<span v-html="$root.config.settings.ts_post_footer_edit_icon"></span>
												</div>
												<p><?= _x( 'Edit post', 'timeline', 'voxel' ) ?></p>
											</a>
										</li>
										<li v-if="status.user_can_edit || status.user_can_moderate">
											<a href="#" class="flexify" @click.prevent="deleteStatus">
												<div class="ts-term-icon">
													<span v-html="$root.config.settings.ts_post_footer_delete_icon"></span>
												</div>
												<p><?= _x( 'Remove post', 'timeline', 'voxel' ) ?></p>
											</a>
										</li>
									</ul>
								</div>
							</template>
						</form-group>
					</li>
				</ul>
				<create-status :status="status" :index="index" class="ts-edit-status"></create-status>
			</div>
			<div v-if="status.replies.visible" class="ts-status-comments">
				<status-replies :replies="status.replies" :status="status"></status-replies>
			</div>
			<?php if ( is_user_logged_in() ): ?>
				<create-reply v-if="status.replies.visible" :status="status"></create-reply>
			<?php endif ?>
			<div v-if="status.highlightedReplies && status.highlightedReplies.list.length && !status.replies.requested" class="ts-status-comments ts-single-thread">

				<status-replies :replies="status.highlightedReplies" :status="status"></status-replies>
				<a href="#" @click.prevent="status.replies.visible = !status.replies.visible" class="ts-load-more-comments ts-btn ts-btn-4">
					<span v-html="$root.config.settings.ts_post_footer_comment_icon"></span>
					<?= _x( 'View all comments', 'timeline', 'voxel' ) ?>
				</a>
			</div>
		</div>
	</div>
</script>
