<?php
/**
 * Search filters - component template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="post-type-search-filters-template">
    <div class="used-fields">
        <div class="sub-heading">
            <p>Used filters</p>
        </div>
        <div class="field-container" ref="fields-container">
            <draggable v-model="$root.config.search.filters" group="filters" handle=".field-head" item-key="key" @start="dragStart" @end="dragEnd">
                <template #item="{element: filter}">
                    <div :class="{open: isActive(filter)}" class="single-field wide">
                        <div class="field-head" @click="toggleActive(filter)">
                            <p class="field-name">{{ filter.label }}</p>
                            <span class="field-type">{{ filter.type }}</span>
                            <div class="field-actions">
                                <span class="field-action all-center">
                                    <a href="#" @click.prevent="deleteFilter(filter)">
                                        <i class="lar la-trash-alt icon-sm"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div v-if="isActive(filter)" class="field-body">
                            <div class="ts-row wrap-row">
                                <?= $filter_options_markup ?>
                            </div>
                        </div>
                    </div>
                </template>
            </draggable>
        </div>
    </div>
    <div class="ts-relative">
        <div class="available-fields-container">
            <div class="sub-heading">
                <p>Available filters</p>
            </div>
            <div class="field-container available-fields ts-row wrap-row">
                <template v-for="filter_type in filter_types">
                    <div v-if="canAddFilter(filter_type)" class="single-field ts-col-1-2">
                        <div @click.prevent="addFilter(filter_type)" class="field-head">
                            <p class="field-name">{{ filter_type.label }}</p>
                            <div class="field-actions">
                                <span class="field-action all-center">
                                    <a href="#">
                                        <i class="las la-plus"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</script>
