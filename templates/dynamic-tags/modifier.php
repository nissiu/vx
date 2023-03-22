<script type="text/html" id="dtags-modifier">
	<div class="modal-fields">
		<div class="single-field wide" :class="{ open: editor.activeModifier === modifier }">
			<div class="field-head" @click="toggleActive">
				<i v-if="['else','then'].indexOf(modifier.key) !== -1" class="las la-arrow-right"></i>
				<p class="field-name">{{ getLabel }}</p>
				<span class="field-type">{{ getTypeLabel }}</span>
				<div class="field-actions">
					<span
						v-if="modifier.type === 'control-structure' && ['else','then'].indexOf(modifier.key) === -1"
						title="Control structure"
						class="field-action all-center"
					>
						<i class="las la-project-diagram"></i>
					</span>
					<span @click.prevent="deleteModifier( modifier )" class="field-action all-center">
						<i class="lar la-trash-alt icon-sm"></i>
					</span>
				</div>
			</div>
			<div v-if="editor.activeModifier === modifier" class="field-body">
				<div v-if="editor.activeModifier.unknown">
					<div class="ts-row wrap-row">
						<div class="ts-form-group ts-col-1-1 ">
							<label><span>Unknown modifier.</span></label>
						</div>
					</div>
				</div>
				<div v-else>
					<div class="ts-row wrap-row">
						<?php foreach ( $modifiers as $modifier ): ?>
							<template v-if="modifier.key === <?= esc_attr( wp_json_encode( $modifier->get_key() ) ) ?>">
								<?php $modifier->render_settings() ?>
							</template>
						<?php endforeach ?>
						<?php foreach ( $groups as $group ): ?>
							<?php foreach ( $group->get_methods() as $method ): ?>
								<template v-if="tag.group.key === <?= esc_attr( wp_json_encode( $group->get_key() ) ) ?> && modifier.key === <?= esc_attr( wp_json_encode( $method->get_key() ) ) ?>">
									<?php $method->render_settings() ?>
								</template>
							<?php endforeach ?>
						<?php endforeach ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
