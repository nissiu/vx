<script type="text/json" class="vxconfig"><?= wp_specialchars_decode( wp_json_encode( $config ) ) ?></script>
<div class="ts-form quick-search">
	<div v-if="false" class="ts-form-group quick-search-keyword">
		<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = 'quick-search'">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_search_icon') ) ?: \Voxel\svg( 'search.svg' ) ?>
			<div class="ts-filter-text"><?= $this->get_settings_for_display('ts_qr_text') ?></div>
			<span class="ts-shortcut"><?= \Voxel\get_visitor_os() === 'macOS' ? '⌘+K' : 'CTRL+K' ?></span>
		</div>
	</div>
	<form-group
		popup-key="quick-search"
		ref="formGroup"
		class="ts-form-group quick-search-keyword"
		wrapper-class="ts-quicksearch-popup"
	>
		<template #trigger>
			<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = 'quick-search'">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_search_icon') ) ?: \Voxel\svg( 'search.svg' ) ?>
				<div class="ts-filter-text"><?= $this->get_settings_for_display('ts_qr_text') ?></div>
				<span class="ts-shortcut"><?= \Voxel\get_visitor_os() === 'macOS' ? '⌘+K' : 'CTRL+K' ?></span>
			</div>
		</template>
		<template #popup>
			<form @submit.prevent="getResults">
				<!-- <div class="ts-popup-head flexify ts-sticky-top hide-d">
				  <div class="ts-popup-name flexify">
				    <p><?= $this->get_settings_for_display('ts_qr_text') ?></p>
				  </div>
				  <ul class="flexify simplify-ul">
				    <li class="flexify ts-popup-close">
				      <a href="#" class="ts-icon-btn" role="button" rel="nofollow" @click.prevent="$refs.formGroup.blur()">
				       <?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_close_ico') ) ?: \Voxel\svg( 'close.svg' ) ?>
				      </a>
				    </li>
				  </ul>
				</div> -->

				<div class="ts-sticky-top qs-top">
					<a href="#" class="ts-icon-btn hide-d" role="button" rel="nofollow" @click.prevent="$refs.formGroup.blur()">
					 <?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_close_ico') ) ?: \Voxel\svg( 'close.svg' ) ?>
					</a>
					<div class="ts-input-icon flexify">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_search_icon') ) ?: \Voxel\svg( 'search.svg' ) ?>
					<input type="text" @keydown.enter="saveCurrentTerm(); viewArchive();" :value="search" @input="search = $event.target.value" placeholder="<?= esc_attr( _x( 'Search', 'quick search', 'voxel' ) ) ?>" class="autofocus" maxlength="100"></div>
				</div>
				<div class="ts-form-group">
					<ul class="ts-generic-tabs flexify simplify-ul quick-cpt-select">
						<li v-for="postType in postTypes" :class="{'ts-tab-active': activeType === postType}">
							<a href="#" @click.prevent="activeType = postType; getResults();">{{ postType.label }}</a>
						</li>
					</ul>
				</div>
				<div class="ts-term-dropdown ts-md-group ts-multilevel-dropdown" :class="{'vx-pending': loading}">
					<template v-if="activeType.results.query.trim().length <= 2">
						<div v-if="!recent.length" class="ts-empty-user-tab">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_search_icon') ) ?: \Voxel\svg( 'search.svg' ) ?>
							<p><?= _x( 'No recent searches.', 'quick search', 'voxel' ) ?></p>
						</div>
						<ul v-else class="simplify-ul ts-term-dropdown-list quick-search-list">
							<template v-for="item in recent">
								<li>
									<a :href="item.link" @click="clickedRecent(item)" class="flexify">

										<div class="ts-term-icon">
											<span v-if="item.logo" v-html="item.logo"></span>
											<span v-else><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_search_icon') ) ?: \Voxel\svg( 'search.svg' ) ?></span>
										</div>
										<p>{{ item.title }}</p>
									</a>
								</li>

							</template>
							<li>
								<a href="#" class="flexify" @click.prevent="clearRecents">
									<div class="ts-term-icon">
										<span>
											<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_trash_ico') ) ?: \Voxel\svg( 'cross-circle.svg' ) ?>
										</span>
									</div>
									<p><?= _x( 'Clear searches', 'quick search', 'voxel' ) ?></p>
								</a>
							</li>
						</ul>
					</template>
					<div v-else-if="!activeType.results.items.length" class="ts-empty-user-tab">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_search_icon') ) ?: \Voxel\svg( 'search.svg' ) ?>
						<p v-if="loading"><?= __( 'Searching', 'voxel' ) ?></p>
						<p v-else><?= __( 'No results found', 'voxel' ) ?></p>
					</div>
					<ul v-else class="simplify-ul ts-term-dropdown-list quick-search-list">
						<template v-for="item in activeType.results.items">
							<li>
								<a :href="item.link" @click="saveSearchItem(item)" class="flexify">
									<div class="ts-term-icon">
										<span v-if="item.logo" v-html="item.logo"></span>
										<span v-else><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_search_icon') ) ?: \Voxel\svg( 'search.svg' ) ?></span>
									</div>
									<p>{{ item.title }}</p>
								</a>
							</li>
						</template>
						<li class="view-all">
							<a href="#" @click.prevent="saveCurrentTerm(); viewArchive();" class="flexify">
								<div class="ts-term-icon">
									<span><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_search_icon') ) ?: \Voxel\svg( 'search.svg' ) ?></span>
								</div>
								<p><?= _x( 'Search for', 'quick search', 'voxel' ) ?> <strong>{{ search }}</strong></p>
							</a>
						</li>
					</ul>
				</div>
			</form>
		</template>
		<template #controller>
			<span></span>
		</template>
	</form-group>
</div>
