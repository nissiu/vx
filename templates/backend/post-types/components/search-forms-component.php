<?php
/**
 * Search filters - component template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="post-type-search-forms-template">
	<div class="ts-tab-content ts-container">
		<div class="ts-row wrap-row h-center">
			<div class="ts-col-3-4">
				<div class="ts-tab-heading">
					<h1>Search filters</h1>
					<p>Create filters available for this post type.</p>
				</div>
				<ul class="inner-tabs">
					<li :class="{'current-item': $root.subtab === 'general'}">
						<a href="#" @click.prevent="$root.setTab('filters', 'general')">Search filters</a>
					</li>
					<li :class="{'current-item': $root.subtab === 'order'}">
						<a href="#" @click.prevent="$root.setTab('filters', 'order')">Search order</a>
					</li>
					<li :class="{'current-item': $root.subtab === 'indexing'}">
						<a href="#" @click.prevent="$root.setTab('filters', 'indexing')">Indexing</a>
					</li>
					<li :class="{'current-item': $root.subtab === 'status'}">
						<a href="#" @click.prevent="$root.setTab('filters', 'status')">Indexing status</a>
					</li>
				</ul>
		
				<div v-if="$root.subtab === 'general'" class="inner-tab fields-layout">
					<search-filters></search-filters>
				</div>
				<div v-if="$root.subtab === 'order'" class="inner-tab fields-layout">
					<search-order></search-order>
				</div>
				<div v-if="$root.subtab === 'indexing'" class="inner-tab">
					<div class="ts-row wrap-row">
						<div class="ts-form-group ts-col-1-1" style="margin-bottom: 0;">
							<h3 class="mb0">Post statuses</h3>
							<p>Set what posts other than published ones should be indexed.</p>
						</div>

						<div class="ts-form-group ts-col-1-1 ts-checkbox mt0">
							<div class="ts-checkbox-container">
								<label class="container-checkbox vx-disabled">
									Published
									<input type="checkbox" checked disabled>
									<span class="checkmark"></span>
								</label>
								<label class="container-checkbox">
									Pending
									<input type="checkbox" value="pending" v-model="$root.config.settings.indexing.post_statuses">
									<span class="checkmark"></span>
								</label>
								<label class="container-checkbox">
									Rejected
									<input type="checkbox" value="rejected" v-model="$root.config.settings.indexing.post_statuses">
									<span class="checkmark"></span>
								</label>
								<label class="container-checkbox">
									Draft
									<input type="checkbox" value="draft" v-model="$root.config.settings.indexing.post_statuses">
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div v-if="$root.subtab === 'status'" class="inner-tab fields-layout">
					<div v-if="!$root.indexing.loaded">
						<p>Loading...</p>
						{{ $root.getIndexData() }}
					</div>
					<div v-else>
						<div class="ts-form-group">
							<label>Database table</label>
							<input type="text" :value="$root.indexing.table_name" readonly>
						</div>
						<div class="ts-form-group">
							<label>Total posts</label>
							<input type="text" :value="$root.indexing.items_total" readonly>
						</div>

						<div v-if="$root.indexing.running" class="ts-form-group">
							<label>Status</label>
							<input type="text" :value="$root.indexingStatus" readonly>
						</div>
						<div v-else class="ts-form-group">
							<label>Indexed posts</label>
							<input type="text" :value="$root.indexing.items_indexed" readonly>
						</div>
						<ul class="basic-ul" :class="{'vx-disabled': $root.indexing.running && !$root.indexing.run_finished}">
							<li style="width: 100%;">
								<a class="ts-button ts-faded" href="#" @click.prevent="$root.indexPosts">Index all posts</a>
							</li>
							<li style="width: 100%;">
								<a class="ts-button ts-transparent" href="#" @click.prevent="$root.forceIndexPosts">Recreate table and index all posts</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>