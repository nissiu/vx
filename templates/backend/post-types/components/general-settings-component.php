<?php
/**
 * General settings - component template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="post-type-settings-template">
	<div class="ts-tab-content ts-container">
		<div class="ts-row wrap-row h-center">
			<div class="ts-col-1-2">
				<div class="ts-tab-heading">
					<h1>General</h1>
					<p>General post type settings</p>
				</div>

				<ul class="inner-tabs">
					<li :class="{'current-item': $root.subtab === 'base'}">
						<a href="#" @click.prevent="$root.setTab('general', 'base')">General</a>
					</li>
					<li :class="{'current-item': $root.subtab === 'submissions'}">
						<a href="#" @click.prevent="$root.setTab('general', 'submissions')">Post submission</a>
					</li>
					<li :class="{'current-item': $root.subtab === 'timeline'}">
						<a href="#" @click.prevent="$root.setTab('general', 'timeline')">Timeline & Reviews</a>
					</li>
					<li :class="{'current-item': $root.subtab === 'messages'}">
						<a href="#" @click.prevent="$root.setTab('general', 'messages')">Direct Messages</a>
					</li>
					<li :class="{'current-item': $root.subtab === 'map'}">
						<a href="#" @click.prevent="$root.setTab('general', 'map')">Map</a>
					</li>
					<li :class="{'current-item': $root.subtab === 'permalinks'}">
						<a href="#" @click.prevent="$root.setTab('general', 'permalinks')">Permalinks</a>
					</li>
				</ul>

				<div class="inner-tab">
					<div v-if="$root.subtab === 'base'" class="ts-row wrap-row">
						<div class="ts-form-group ts-col-1-1">
							<label>Singular name</label>
						 	<input type="text" v-model="$root.config.settings.singular">
						</div>
						<div class="ts-form-group ts-col-1-1">
							<label>Plural name</label>
						 	<input type="text" v-model="$root.config.settings.plural">
						</div>
						<div class="ts-form-group ts-col-1-1">
							<label>Post type key</label>
						 	<input type="text" v-model="$root.config.settings.key" maxlength="20" required disabled>
						</div>
						<?php \Voxel\Form_Models\Icon_Model::render( [
							'v-model' => '$root.config.settings.icon',
							'width' => '1/1',
							'label' => 'Icon',
						] ) ?>

						<div class="ts-form-group ts-col-1-1">
							<h3 class="mb0">Configuration</h3>
							<p>The previous 15 revisions of the post type settings are stored automatically.</p>
						</div>
						<div class="ts-form-group ts-col-1-1">
							<ul class="basic-ul">
								<li>
									<a :href="exportRevision('current')" class="ts-button ts-faded">
										<i class="las la-download icon-sm"></i>Export config file
									</a>
								</li>
								<li>
									<a href="#" @click.prevent="$root.$w.confirm('This will replace your existing post type configuration. Do you want to proceed?') ? $refs.importConfig.click() : ''" class="ts-button ts-transparent">
										<i class="las la-cloud-upload-alt icon-sm"></i>Import config file
									</a>
									<input type="file" ref="importConfig" @change="importConfig" class="hidden">
								</li>
								<li>
									<a href="#" @click.prevent="$root.setTab('general', 'revisions')" class="ts-button ts-transparent">
										<i class="las la-history icon-sm"></i>View revisions
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div v-else-if="$root.subtab === 'submissions'" class="ts-row wrap-row">
						<?php \Voxel\Form_Models\Switcher_Model::render( [
							'v-model' => '$root.config.settings.submissions.enabled',
							'label' => 'Enable post submissions',
							'description' => 'Allows users to submit posts of this post type through the frontend form',
						] ) ?>

						<?php \Voxel\Form_Models\Select_Model::render( [
							'v-model' => '$root.config.settings.submissions.status',
							'label' => 'When a new post is submitted, set its status to',
							'choices' => [
								'publish' => 'Published: Post is published and publicly available immediately',
								'pending' => 'Pending Review: Admin review and approval is required before it\'s published',
							],
						] ) ?>

						<?php \Voxel\Form_Models\Select_Model::render( [
							'v-model' => '$root.config.settings.submissions.update_status',
							'label' => 'When an existing post is edited',
							'choices' => [
								'publish' => 'Publish: Apply edits immediately and keep the post published',
								'pending' => 'Pending Review: Apply edits immediately and set the post status to pending',
								'pending_merge' => 'Pending Merge: Post remains published, but edits are not applied until the admin has reviewed and approved them.',
								'disabled' => 'Disabled: Posts cannot be edited',
							],
						] ) ?>

						<?php \Voxel\Form_Models\Switcher_Model::render( [
							'v-model' => '$root.config.settings.submissions.update_slug',
							'label' => 'Always update post slug when the post is updated',
						] ) ?>

						<?php \Voxel\Form_Models\Switcher_Model::render( [
							'v-model' => '$root.config.settings.submissions.deletable',
							'label' => 'Authors are allowed to delete their posts',
						] ) ?>
					</div>
					<div v-else-if="$root.subtab === 'timeline'" class="ts-row wrap-row">
						<div class="ts-form-group ts-col-1-1">
							<h3 class="mb0">Post timeline</h3>
							<p>Allows post author to publish to timeline as current post</p>
						</div>
						<?php \Voxel\Form_Models\Switcher_Model::render( [
							'v-model' => '$root.config.settings.timeline.enabled',
							'label' => 'Enable post timeline',
						] ) ?>

						<?php \Voxel\Form_Models\Select_Model::render( [
							'v-model' => '$root.config.settings.timeline.visibility',
							'label' => 'Visibility of timeline posts',
							'choices' => [
								'public' => 'Public: Visible to everyone',
								'logged_in' => 'Logged-in: Visible to all logged in users',
								'followers_only' => 'Followers: Visible to post followers only',
								'private' => 'Private: Visible to post author only',
							],
						] ) ?>

						<div class="ts-form-group ts-col-1-1">
							<h3 class="mb0">Post reviews</h3>
							<p>Allows other users to review post</p>
						</div>

						<?php \Voxel\Form_Models\Select_Model::render( [
							'v-model' => '$root.config.settings.timeline.reviews',
							'label' => 'Allow post reviews',
							'choices' => [
								'public' => 'From all logged-in users',
								'followers_only' => 'From followers only',
								'disabled' => 'Disabled',
							],
						] ) ?>

						<?php \Voxel\Form_Models\Select_Model::render( [
							'v-model' => '$root.config.settings.timeline.review_visibility',
							'label' => 'Visibility of post reviews',
							'choices' => [
								'public' => 'Public: Visible to everyone',
								'logged_in' => 'Logged-in: Visible to all logged in users',
								'followers_only' => 'Followers: Visible to post followers only',
								'private' => 'Private: Visible to post author only',
							],
						] ) ?>

						<div class="ts-form-group ts-col-1-1">
							<h3 class="mb0">Wall posts</h3>
							<p>Allows other users to publish on current post's wall</p>
						</div>

						<?php \Voxel\Form_Models\Select_Model::render( [
							'v-model' => '$root.config.settings.timeline.wall',
							'label' => 'Allow wall posts',
							'choices' => [
								'public' => 'From all logged-in users',
								'followers_only' => 'From followers only',
								'disabled' => 'Disabled',
							],
						] ) ?>

						<?php \Voxel\Form_Models\Select_Model::render( [
							'v-model' => '$root.config.settings.timeline.wall_visibility',
							'label' => 'Visibility of wall posts',
							'choices' => [
								'public' => 'Public: Visible to everyone',
								'logged_in' => 'Logged-in: Visible to all logged in users',
								'followers_only' => 'Followers: Visible to post followers only',
								'private' => 'Private: Visible to post author only',
							],
						] ) ?>
					</div>
					<div v-else-if="$root.subtab === 'messages'" class="ts-row wrap-row">
						<div class="ts-form-group ts-col-1-1">
							<h3 class="mb0">Direct Messages</h3>
							<p>Allow posts of this post type to send and receive messages from other users and posts</p>
						</div>
						<?php \Voxel\Form_Models\Switcher_Model::render( [
							'v-model' => '$root.config.settings.messages.enabled',
							'label' => 'Enable messages',
						] ) ?>
					</div>
					<div v-else-if="$root.subtab === 'map'" class="ts-row wrap-row">
						<div class="ts-form-group ts-col-1-1">
							<h3 class="mb0">Map markers</h3>
							<p>Determine how posts of this post type appear on the map</p>
						</div>
						<?php \Voxel\Form_Models\Select_Model::render( [
							'v-model' => '$root.config.settings.map.marker_type',
							'label' => 'Marker type',
							'choices' => [
								'icon' => 'Icon',
								'image' => 'Image',
								'text' => 'Text',
							],
						] ) ?>

						<?php \Voxel\Form_Models\Icon_Model::render( [
							'v-model' => '$root.config.settings.map.marker_icon',
							'v-if' => '$root.config.settings.map.marker_type === \'icon\'',
							'width' => '1/1',
							'label' => 'Marker icon',
						] ) ?>

						<div v-if="$root.config.settings.map.marker_type === 'image'" class="ts-form-group ts-col-1-1">
							<label>Get image from field:</label>
							<select v-model="$root.config.settings.map.marker_image">
								<option v-for="field in $root.getFieldsByType('image')" :value="field.key">
									{{ field.label }}
								</option>
							</select>
						</div>

						<?php \Voxel\Form_Models\DTag_Model::render( [
							'v-model' => '$root.config.settings.map.marker_text',
							'v-if' => '$root.config.settings.map.marker_type === \'text\'',
							'width' => '1/1',
							'label' => 'Marker text',
						] ) ?>
					</div>
					<div v-else-if="$root.subtab === 'permalinks'" class="ts-row wrap-row">
						<div class="ts-form-group ts-col-1-1">
							<h3 class="mb0">Permalinks</h3>
							<p>Set the base permalink structure for posts of this post type</p>
						</div>

						<?php \Voxel\Form_Models\Switcher_Model::render( [
							'v-model' => '$root.config.settings.permalinks.custom',
							'label' => 'Custom permalink base',
						] ) ?>

						<div v-if="$root.config.settings.permalinks.custom" class="ts-form-group ts-col-1-1">
							<label>Permalink front base</label>
						 	<input type="text" v-model="$root.config.settings.permalinks.slug">
							<p><?= home_url('/') ?>{{ $root.config.settings.permalinks.slug }}/sample-post</p>
						</div>
					</div>
					<div v-else-if="$root.subtab === 'revisions'" class="ts-row wrap-row">
						<div class="ts-form-group ts-col-1-1">
							<h3 class="mb0">Revisions</h3>
							<p>The previous 15 revisions of the post type settings are stored automatically.</p>
						</div>

						<div class="ts-form-group ts-col-1-1">
							<template v-if="$root.options.revisions.length">
								<div v-for="revision in $root.options.revisions" class="single-revision mb20">
									<p class="mb10">Revision by <strong>{{ revision.author }}</strong> on <strong>{{ revision.date }}</strong></p>
									<ul class="basic-ul">
										<li>
											<a :href="rollbackRevision(revision.timestamp)" onclick="return confirm('This will replace your existing post type configuration. Do you want to proceed?')" class="ts-button ts-faded">
												<i class="las la-cloud-upload-alt icon-sm"></i>Rollback
											</a>
										</li>
										<li>
											<a :href="exportRevision(revision.timestamp)" class="ts-button ts-transparent">
												<i class="las la-download icon-sm"></i>Export
											</a>
										</li>
										<li>
											<a href="#" @click.prevent="removeRevision(revision)" class="ts-button ts-transparent">
												<i class="las la-trash icon-sm"></i>Remove
											</a>
										</li>
									</ul>
								</div>
							</template>
							<template v-else>
								<div class="single-revision mb20">
									<p class="mb10">No revisions made to this post type yet.</p>
								</div>
							</template>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
