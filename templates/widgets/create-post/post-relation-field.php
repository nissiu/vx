<script type="text/html" id="create-post-post-relation-field">
	<form-group wrapper-class="prmr-popup" :popup-key="field.id+':'+index" ref="formGroup" @save="onSave" @blur="saveValue" @clear="onClear">
		<template #trigger>
			<label>
				{{ field.label }}
				<small>{{ field.description }}</small>
			</label>
			<div class="ts-filter ts-popup-target" :class="{'ts-filled': field.value !== null}" @mousedown="$root.activePopup = field.id+':'+index; onOpen();">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_list_icon') ) ?: \Voxel\svg( 'list.svg' ) ?>
				<div class="ts-filter-text">
	 				<span>{{ field.value !== null ? displayValue : field.props.placeholder }}</span>
				</div>
			</div>
		</template>
		<template #popup>
			<div class="ts-term-dropdown ts-md-group ts-multilevel-dropdown">
				<ul class="simplify-ul ts-term-dropdown-list min-scroll">
					<template v-if="posts.list.length">
						<li v-for="post in posts.list">
							<a href="#" class="flexify" @click.prevent="selectPost(post)">
								<div class="ts-checkbox-container">
									<label :class="'container-'+(field.props.multiple ? 'checkbox' : 'radio')">
										<input
											:type="field.props.multiple ? 'checkbox' : 'radio'"
											:value="post.id"
											:checked="value[post.id]"
											disabled
											hidden
										>
										<span class="checkmark"></span>
									</label>
								</div>
								<p>{{ post.title }}</p>
								<div class="ts-term-icon">
									<span v-html="post.logo || post.icon"></span>
								</div>
							</a>
						</li>
						<li>
							<a href="#" v-if="posts.has_more" @click.prevent="loadPosts" class="ts-btn ts-btn-4" :class="{'vx-pending': posts.loading}">
								<span><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_timeline_load_icon') ) ?: \Voxel\svg( 'reload.svg' ) ?></span>
								<?= __( 'Load more', 'voxel' ) ?>
							</a>
						</li>
					</template>
					<template v-else>
						<li v-if="posts.loading"><a href="#" class="flexify"><p><?= __( 'Loading', 'voxel' ) ?></p></a></li>
						<li v-else><a href="#" class="flexify"><p><?= _x( 'No posts found', 'post relation field', 'voxel' ) ?></p></a></li>
					</template>
				</ul>
			</div>
		</template>
	</form-group>
</script>
