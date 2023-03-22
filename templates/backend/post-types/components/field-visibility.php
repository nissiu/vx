<?php
/**
 * Field visibility component.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="post-type-field-visibility-template">
	<?php \Voxel\Form_Models\Select_Model::render( [
		'v-model' => 'field.visibility_behavior',
		'label' => 'Visibility',
		'choices' => [
			'show' => 'Show this field if',
			'hide' => 'Hide this field if',
		],
	] ) ?>

	<div class="ts-form-group ts-col-1-1">
		<div class="vx-visibility-rules" v-html="displayRules()"></div>
	</div>

	<div class="ts-form-group ts-col-1-1">
		<a href="#" @click.prevent="editRules" class="ts-button ts-faded">Edit rules</a>
	</div>

	<!-- <div class="ts-form-group ts-col-1-1">
		<pre debug>{{ field.visibility_behavior }}</pre>
		<pre debug>{{ field.visibility_rules }}</pre>
	</div> -->
</script>
