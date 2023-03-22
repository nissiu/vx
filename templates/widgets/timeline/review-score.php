<script type="text/html" id="timeline-review-score">
	<div v-if="rating" class="ts-review-score" :class="rating.key">
		<span v-html="rating.icon"></span>
		<p>{{ rating.label }}</p>
	</div>
</script>
