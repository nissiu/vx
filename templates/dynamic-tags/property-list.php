<script type="text/html" id="dtags-property-list">
	<template v-for="property, key in properties">
		<div v-if="key !== ':default'" class="single-field" :class="{
			stack: property.type === 'object',
			open: ( activeStack === property ) || ( property.description && property._show_description ),
			'ts-col-1-1': depth > 0,
		}">
			<div class="field-head" @click.prevent="propertyClick( property, key )">
				<p class="field-name">{{ property.label }}</p>
				<span class="field-type">{{ key }}</span>
				<div class="field-actions">
					<a href="#" v-if="property.description" @click.stop.prevent="property._show_description = !property._show_description" class="field-action all-center">
						<i class="las la-question-circle"></i>
					</a>
					<a href="#" v-if="property.list" @click.stop.prevent="propertyClick( property, key, true )" class="field-action all-center" title="List all items">
						<i class="las la-list"></i>
					</a>
					<span v-if="property.type === 'object' && property.loopable" class="field-action all-center" title="Loopable">
						<i class="las la-redo-alt"></i>
					</span>
					<span v-if="property.type === 'object'" class="field-action all-center">
						<i class="las la-arrow-circle-down"></i>
					</span>
				</div>
			</div>
			<div v-if="property.description && property._show_description" class="field-body">
				<p>{{ property.description }}</p>
			</div>
			<div v-if="property.type === 'object' && activeStack === property" class="field-body">
				<div class="ts-row wrap-row">
					<property-list
						:properties="property.properties"
						:path="path.concat([key])"
						@select="$emit('select', $event)"
					></property-list>
				</div>
			</div>
		</div>
	</template>
</script>
