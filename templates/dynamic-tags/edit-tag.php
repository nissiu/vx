<script type="text/html" id="dtags-edit-tag">
	<div class="field-options-control">
		<!-- <div class="sub-heading"><h3>Field settings</h3></div> -->
		
		<a href="#" class="ts-button ts-faded ts-btn-small icon-only" @click.prevent="deleteTag">
			<i class="las la-trash-alt icon-sm"></i>
		</a>

		<a href="#" class="ts-button btn-shadow ts-btn-small icon-only" @click.prevent="saveTag">
			<i class="las la-check icon-sm"></i>
		</a>
	</div>
	<div class="output-fields-list modal-fields field-options">
		<span class="dtag">
			<span class="dtag-content">{{tag.property.label}}</span>
			<p>{{ pathText }}</p>
		</span>
	</div>

	<div class="field-container modal-fields" ref="mods-container">
		<draggable v-model="tag.modifiers" group="modifiers" handle=".field-head" item-key="id" @start="onDragStart" @end="onDragEnd">
			<template #item="{element: mod, index: index}">
				<modifier :modifier="mod" :index="index" :editor="this" :tag="tag"></modifier>
			</template>
		</draggable>
	</div>

	<div class="modal-fields">
		<div class="single-field wide empty add-mod">
			<div class="field-head" @click.prevent="showMods = !showMods">
				<p class="field-name">Add mod</p>
			</div>
		</div>
	</div>

	<div v-if="showMods" class="modal-fields mod-list ts-row wrap-row">
		<template v-for="group in modGroups">
			<label>{{ group.label }}</label>
			<div
				v-for="modifier in group.modifiers"
				@click.prevent="useModifier( modifier )"
				class="single-field"
			>
				<div class="field-head">
					<p class="field-name">{{ modifier.label }}</p>
					<p class="field-type">{{ modifier.description }}</p>
				</div>
			</div>
		</template>

		<label>Conditionals</label>
		<template v-for="modifier in $root.modifiers">
			<div
				v-if="modifier.type === 'control-structure' && ['else','then'].indexOf(modifier.key) === -1"
				@click.prevent="useCondition( modifier )"
				class="single-field"
			>
				<div class="field-head">
					<p class="field-name">{{ modifier.label }}</p>
					<p class="field-type">{{ modifier.description }}</p>
				</div>
			</div>
		</template>
	</div>

	<!-- <pre>{{ tag }}</pre> -->
</script>