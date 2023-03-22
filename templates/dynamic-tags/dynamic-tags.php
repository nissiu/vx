<?php

wp_enqueue_script( 'sortable' );
wp_enqueue_script( 'vue-draggable' );
wp_enqueue_script( 'vx:dynamic-tags.js' );

$groups = array_map( function( $group_class ) {
	$group = new $group_class;

	if ( \Voxel\is_dev_mode() && isset( $_GET['post_type'] ) ) {
		$group->set_post_type( \Voxel\Post_Type::get( $_GET['post_type'] ) );
	}

	return $group;
}, \Voxel\config('dynamic_tags.groups') );

$config = \Voxel\Dynamic_Tags\Dynamic_Tags::get_frontend_config();
$modifiers = \Voxel\Dynamic_Tags\Dynamic_Tags::get_modifier_instances();
$visibility_rules = \Voxel\Dynamic_Tags\Dynamic_Tags::get_visibility_rule_instances();
$rules_config = [];
foreach ( $visibility_rules as $rule ) {
	$rules_config[ $rule->get_type() ] = $rule->get_editor_config();
}
?>

<script type="text/javascript">
	window.Dynamic_Tag_Groups = <?= wp_json_encode( $config ) ?>;
	window.Dynamic_Tag_Modifiers = <?= wp_json_encode( array_map( function( $modifier ) {
		return $modifier->get_editor_config();
	}, $modifiers ) ) ?>;
	window.Dynamic_Tag_Rules = <?= wp_json_encode( $rules_config ) ?>;
</script>

<?php require locate_template( 'templates/dynamic-tags/content-editor.php' ) ?>
<?php require locate_template( 'templates/dynamic-tags/visibility-editor.php' ) ?>
<?php require locate_template( 'templates/dynamic-tags/edit-tag.php' ) ?>
<?php require locate_template( 'templates/dynamic-tags/modifier.php' ) ?>
<?php require locate_template( 'templates/dynamic-tags/property-list.php' ) ?>
<?php require locate_template( 'templates/dynamic-tags/data-sources.php' ) ?>

<script type="text/html" id="dtags-template">
	<div v-if="visible" id="dynamic-tags-modal" class="ts-theme-options engine-modal-container">
		<div class="modal-backdrop" @click="discard"></div>
		<div class="engine-modal">
			<div class="engine-save-changes">
				<a href="#" class="ts-button ts-transparent" @click.prevent="discard">
					Discard
				</a>
				<a href="#" class="ts-button btn-shadow" @click.prevent="save">
					Save changes
				</a>
			</div>
			<div v-if="mode === 'visibility'" class="engine-modal-tab ts-dynamic-visibility">
				<visibility-editor ref="visibilityEditor"></visibility-editor>
			</div>
			<div v-else class="engine-modal-tab">
				<content-editor ref="contentEditor"></content-editor>

				<div class="bordered-columns field-select-section min-scroll">
					<data-sources
						v-if="!activeTag"
						@select="$refs.contentEditor.insertContent($event+'&nbsp;')"
					></data-sources>
					<div v-if="activeTag" class="edit-tag">
						<edit-tag :tag="activeTag"></edit-tag>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>

<div id="dtags-container"></div>
