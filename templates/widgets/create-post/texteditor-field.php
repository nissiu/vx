<script type="text/html" id="create-post-texteditor-field">
	<div v-if="field.props.editorType === 'plain-text'" class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<textarea v-model="field.value" :placeholder="field.props.placeholder" class="ts-filter min-scroll"></textarea>
	</div>
	<div v-else class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<div ref="toolbar" class="toolbar-container" :id="field.props.toolbarId"></div>
		<div ref="editor" class="editor-container mce-content-body" :id="field.props.editorId"></div>
	</div>
</script>
