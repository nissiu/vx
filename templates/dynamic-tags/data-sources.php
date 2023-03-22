<script type="text/html" id="dtags-data-sources">
	<div>
		<div class="sub-heading">
			<h3>Available fields</h3>
		</div>
		<div class="modal-choose-source modal-fields">
			<ul class="inner-tabs">
				<li v-for="group in $root.groups" :class="{'current-item': $root.activeGroup === group}">
					<a href="#" @click.prevent="$root.activeGroup = group">{{ group.title }}</a>
				</li>
			</ul>
		</div>
	</div>
	<div v-if="$root.activeGroup">
		<div class="ts-form-group">
			<label>Search fields</label>
			<input type="text" v-model="search">
		</div>
		<div v-if="search.trim().length" class="modal-available-fields modal-fields">
			<p class="d-search-title">Searching all fields for "{{search}}"</p>
			<div v-for="properties, group_key in searchProperties()">
				<p class="d-search-title">{{ $root.groups[ group_key ].title }}</p><br>
				<property-list
					:properties="properties"
					:path="['@'+group_key]"
					@select="$emit('select', $event)"
				></property-list>
			</div>
		</div>
		<div v-else class="modal-available-fields modal-fields">
			<property-list
				:properties="$root.activeGroup.properties"
				:path="['@'+$root.activeGroup.key]"
				@select="$emit('select', $event)"
			></property-list>

			<div class="method-list">
				<div v-for="method in $root.activeGroup.methods" class="single-field">
					<div class="field-head" @click.prevent="useMethod(method)">
						<p class="field-name">{{ method.label }}</p>
						<span class="field-type">{{ method.key }}()</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
