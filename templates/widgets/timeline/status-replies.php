<script type="text/html" id="timeline-status-replies">
	<ul class="status-comments-list simplify-ul">
		<template v-if="parent && !replies.list.length && replies.loading">
			<div class="ts-no-posts">
				<span class="ts-loader"></span>
			</div>
		</template>
		<template v-if="!parent && !replies.list.length">
			<div class="ts-no-posts">
				<template v-if="replies.loading">
					<span class="ts-loader"></span>
				</template>
				<template v-else>
					<span v-html="$root.config.settings.ts_post_footer_comment_icon"></span>
					<p><?= _x( 'No replies on this post', 'timeline', 'voxel' ) ?></p>
				</template>
			</div>
		</template>

		<template v-else>
			<li v-for="reply, index in replies.list" :key="reply.key" class="ts-single-status ts-reply" :class="{'vx-pending': reply._pending, 'highlighted': reply.highlighted}">
				<a :href="reply.user.link" class="ts-user-avatar" v-html="reply.user.avatar"></a>
				<div class="comment-body ts-status">
					<div class="ts-status-head flexify">
						<a :href="reply.user.link" class="ts_status-author">{{ reply.user.name }}</a>
						<div>
							<a :href="reply.link">
								{{ reply.time }}
							</a>
							<span v-if="reply.edit_time" :title="'Edited on '+reply.edit_time"><?= _x( '(edited)', 'timeline', 'voxel' ) ?></span>
						</div>
					</div>
					<div class="ts-status-body">
						<p v-html="reply.content"></p>
					</div>
					<div class="ts-status-footer">
						<ul class="simplify-ul flexify">
							<li>
								<a href="#" @click.prevent="likeReply(reply, index)" :class="{'ts-liked': reply.liked_by_user}" :ref="reply.key+':likeBtn'" class="ts-like">
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
									<template v-if="reply.liked_by_user">
										<span v-html="$root.config.settings.ts_post_footer_liked_icon"></span>
									</template>
									<template v-else>
										<span v-html="$root.config.settings.ts_post_footer_like_icon"></span>
									</template>
								</a>
								<span class="ts-item-count" v-if="reply.like_count">{{ reply.like_count }}</span>
							</li>
							<li v-if="reply.reply_count">
								<a href="#" @click.prevent="reply.replies.visible = !reply.replies.visible" class="">
									<span v-html="$root.config.settings.ts_post_footer_comment_icon"></span>

								</a>
								<span class="ts-item-count" v-if="reply.reply_count">{{ reply.reply_count }}</span>
							</li>
							<li>
								<a href="#" @click.prevent="showReplyBox(reply, status)" class="">
									<span v-html="$root.config.settings.ts_post_footer_reply_icon"></span>
								</a>
							</li>
							<li class="tl-more" v-if="reply.user_can_edit || reply.user_can_moderate">
								<form-group
									:popup-key="'mod-reply:'+status.id+'-'+(parent?parent.id:0)+'-'+reply.key"
									:default-class="false"
									:show-save="false"
									:show-clear="false"
								>
									<template #trigger>
										<a href="#" @click.prevent="$root.activePopup = 'mod-reply:'+status.id+'-'+(parent?parent.id:0)+'-'+reply.key" class="">
											<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_more_icon') ) ?: \Voxel\svg( 'more-alt.svg' ) ?>
										</a>
									</template>
									<template #popup>
										<div class="ts-popup-head flexify">
											<div class="ts-popup-name flexify">
												<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_more_icon') ) ?: \Voxel\svg( 'more-alt.svg' ) ?>
												<p><?= _x( 'More', 'timeline', 'voxel' ) ?></p>
											</div>
											<ul class="flexify simplify-ul">
												<li class="flexify ts-popup-close">
													<a @click.prevent="$root.activePopup = null" href="#" class="ts-icon-btn">
														<i aria-hidden="true" class="las la-times"></i>
													</a>
												</li>
											</ul>
										</div>
										<div class="ts-term-dropdown ts-md-group">
											<ul class="simplify-ul ts-term-dropdown-list min-scroll">
												<li v-if="reply.user_can_edit && $root.config.replySubmission.editable">
													<a href="#" class="flexify" @click.prevent="$root.activePopup = 'reply:'+status.id+'-'+(parent?parent.id:0)+'-'+reply.key">
														<div class="ts-term-icon">
															<span v-html="$root.config.settings.ts_post_footer_edit_icon"></span>
														</div>
														<p><?= _x( 'Edit reply', 'timeline', 'voxel' ) ?></p>
													</a>
												</li>
												<li v-if="reply.user_can_edit || reply.user_can_moderate">
													<a href="#" class="flexify" @click.prevent="deleteReply(reply, index)">
														<div class="ts-term-icon">
															<span v-html="$root.config.settings.ts_post_footer_delete_icon"></span>
														</div>
														<p><?= _x( 'Remove reply', 'timeline', 'voxel' ) ?></p>
													</a>
												</li>
											</ul>
										</div>
									</template>
								</form-group>
							</li>
						</ul>

						<!-- edit comment -->
						<create-reply :show-trigger="false" :status="status" :index="index" :reply="reply" :parent="parent"></create-reply>

						<!-- reply to comment -->
						<create-reply :show-trigger="false" :status="status" :parent="reply" :index="index"></create-reply>
					</div>
					<status-replies
						v-if="reply.replies.visible"
						:replies="reply.replies"
						:status="status"
						:parent="reply"
					></status-replies>
				</div>
			</li>
			<li v-if="replies.hasMore">
				<a
					href="#"
					v-if="replies.hasMore"
					@click.prevent="replies.page++; getReplies();"
					class="ts-load-more-comments ts-btn ts-btn-4"
					:class="{'vx-pending': replies.loading}"
				>
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_timeline_load_icon') ) ?: \Voxel\svg( 'reload.svg' ) ?>
					<?= _x( 'Load more comments', 'timeline', 'voxel' ) ?>
				</a>
			</li>
		</template>
	</ul>
</script>
