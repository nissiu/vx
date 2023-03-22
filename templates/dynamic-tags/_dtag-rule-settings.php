<div class="ts-form-group ts-col-1-2">
	<label>Dynamic Tag</label>
	<a
		href="#"
		@click.prevent="activePopup = ( activePopup === [condition_key, group_key, 'tag'].join(';') ? null : [condition_key, group_key, 'tag'].join(';') ); activeTag = $root.parseTag( condition.tag ) || null;"
		class="ts-button ts-outline"
		:class="{'con-active': activePopup === [condition_key, group_key, 'tag'].join(';')}"
	>
		<div v-if="condition.tag" v-html="$root.displayTag(condition.tag)"></div>
		<span v-else>Choose tag</span>
	</a>
	<div v-if="activePopup === [condition_key, group_key, 'tag'].join(';')">
		<teleport to="#visibility-sidebar">
			<div v-if="activeTag" class="edit-tag">
				<edit-tag :tag="activeTag" @save="condition.tag = $event; activePopup = null;" @delete="condition.tag = null; activeTag = null;"></edit-tag>
			</div>
			<div v-else class="ts-visibility-source">
				<div class="field-options-control">
					<a href="#" class="ts-button ts-faded ts-btn-small icon-only" @click.prevent="condition.tag = null; activeTag = null;">
						<i class="las la-trash-alt icon-sm"></i>
					</a>
					<a href="#" class="ts-button btn-shadow ts-btn-small icon-only" @click.prevent="activePopup = null">
						<i class="las la-check icon-sm"></i>
					</a>
				</div>
				<data-sources @select="condition.tag = $event; dtagSelected($event);"></data-sources>
			</div>
		</teleport>
	</div>
</div>
<div v-if="condition.tag" class="ts-form-group ts-col-1-2">
	<label>Compare</label>
	<a
		href="#"
		@click.prevent="activePopup = ( activePopup === [condition_key, group_key, 'compare'].join(';') ? null : [condition_key, group_key, 'compare'].join(';') )"
		class="ts-button ts-outline"
		:class="{'con-active': activePopup === [condition_key, group_key, 'compare'].join(';')}"
	>
		<div v-if="$root.modifiers[ condition.compare ]">
			<span class="dtag">
				<span class="dtag-content">{{ $root.modifiers[ condition.compare ].label }}</span>
				<span v-if="condition.arguments && Object.values( condition.arguments ).filter(Boolean).length">
					&nbsp;{{ Object.values( condition.arguments ).filter(Boolean).join(', ') }}
				</span>
			</span>
		</div>
		<span v-else>Condition</span>
	</a>
	<div v-if="activePopup === [condition_key, group_key, 'compare'].join(';')">
		<teleport to="#visibility-sidebar">
			<div class="ts-visibility-source">
				<template v-if="!$root.modifiers[ condition.compare ]">
					<template v-for="modifier in $root.modifiers">
						<a href="#"
							v-if="modifier.type === 'control-structure' && ['else','then'].indexOf(modifier.key) === -1"
							@click.prevent="condition.compare = modifier.key; condition.arguments = $root._clone( modifier.arguments )"
							class="ts-button ts-faded"
						>
							{{ modifier.label }}
						</a>
					</template>
				</template>
				<template v-else>
					<div class="field-options-control">
					  <a href="#" @click.prevent="condition.compare = null; condition.arguments = null;" class="ts-button ts-faded ts-btn-small icon-only">
					    <i class="las la-trash-alt icon-sm"></i>
					  </a>
					  <a href="#" @click.prevent="activePopup = null;" class="ts-button btn-shadow ts-btn-small icon-only">
					    <i class="las la-check icon-sm"></i>
					  </a>
					</div>
					<div class="ts-row wrap-row">
						<?php foreach ( \Voxel\Dynamic_Tags\Dynamic_Tags::get_modifier_instances() as $modifier ): ?>
							<?php if ( $modifier->get_type() === 'control-structure' && ! in_array( $modifier->get_key(), [ 'then', 'else' ], true ) ): ?>
								<template v-if="condition.compare === <?= esc_attr( wp_json_encode( $modifier->get_key() ) ) ?>">
									<?php $modifier->render_settings( [ 'identifier' => 'condition' ] ) ?>
								</template>
							<?php endif ?>
						<?php endforeach ?>
					</div>
				</template>
			</div>
		</teleport>
	</div>
</div>
