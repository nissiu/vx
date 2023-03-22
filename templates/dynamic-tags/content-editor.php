<script type="text/html" id="dtags-content-editor">
	<div class="modal-output-fields">
		<div class="sub-heading">
			<h3>Dynamic content</h3>
			<p class="help-tip">
				<span>Tip: You can click on a field to view its options
				| </span>
				<a href="#" @click.prevent="mode = (mode==='plain'?'visual':'plain')">
					<span>Mode:</span> <u>{{ mode === 'plain' ? 'Plain' : 'Visual' }}</u>
				</a>
			</p>
		</div>
		<div class="output-fields-list modal-fields min-scroll">
			<div
				ref="editor"
				v-if="mode === 'visual'"
				v-html="$root.formatAsHTML($root.escapeContent($root.content))"
				class="dynamic-editor"
				:class="previewClass"
				@copy="onCopy($event)"
				@paste="onPaste($event)"
				@blur="save"
				contenteditable="true"
			></div>

			<p
				v-if="mode === 'visual'"
				@click.prevent="$root.showAvailableFields"
				class="editor-placeholder"
			>
				Type content here...
			</p>

			<div class="plain-editor" v-if="mode === 'plain'">
				<textarea
					v-model="$root.content"
					rows="4"
					class="min-scroll"
					placeholder="Type content here..."
				></textarea>
			</div>
		</div>
	</div>
</script>