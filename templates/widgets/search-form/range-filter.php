<script type="text/html" id="search-form-range-filter">
	<template v-if="filter.props.display_as === 'inline'">
		<div class="ts-form-group ts-inline-filter">
			<label v-if="$root.config.showLabels">{{ filter.label }}</label>
			<div class="range-slider-wrapper" ref="sliderWrapper">
				<div class="range-value">{{ popupDisplayValue }}</div>
			</div>
		</div>
	</template>
	<form-group v-else :popup-key="filter.id" ref="formGroup" @save="onSave" @blur="saveValue" @clear="onClear" :wrapper-class="repeaterId">
		<template #trigger>
			<label v-if="$root.config.showLabels" class="">{{ filter.label }}</label>
			<div
				class="ts-filter ts-popup-target"
				@mousedown="$root.activePopup = filter.id; onEntry();"
				:class="{'ts-filled': filter.value !== null}"
			>
				<span v-html="filter.icon"></span>
				<div class="ts-filter-text">
					{{ filter.value ? displayValue : filter.props.placeholder }}
				</div>
				<div class="ts-down-icon"></div>
			</div>
		</template>
		<template #popup>
			<div class="ts-form-group">
				<label>{{ filter.label }}<small v-if="filter.description">{{ filter.description }}</small></label>
				<div class="range-slider-wrapper" ref="sliderWrapper">
					<div class="range-value">{{ popupDisplayValue }}</div>
				</div>
			</div>
		</template>
	</form-group>
</script>
